<?php
use bd\Formatos;
use bd\My;
use JetBrains\PhpStorm\NoReturn;
use modelo\Assinatura;
use modelo\Usuario;
use modelo\WhatsappValidacao;

class Aut
{
    const int FREQUENCIA_VALIDACAO = 300;
    const int EXPIRACAO_TOKEN = 4 * 3600;
    const int TENTATIVAS_LOGIN = 5;
    const int TTL_LOGIN = 900;

    public static ?int $codigo = null;
    public static ?int $codConta = null;
    public static ?string $perfil = null;
    public static ?int $codPersonificador = null;
    public static ?int $ultimaValidacao = null;
    public static ?Usuario $usuario = null;
    public static ?Assinatura $assinatura;

    /**
     * @param string $email
     * @param string|null $senha
     * @return void
     * @throws Exception
     */
    public static function login(string $email, ?string $senha = null): void
    {
        $redis = new Redis();
        $key = Sistema::$app . ':' . 'login:' . $email;
        $tentativas = $redis->get($key) ?: 0;
        if ($tentativas >= self::TENTATIVAS_LOGIN) {
            throw new Exception('Usuário bloqueado.');
        }
        $c = My::con();
        $sql = <<< LOGIN
            SELECT u.codigo, u.senha
            FROM usuario u
            INNER JOIN conta c ON u.cod_conta = c.codigo
            WHERE u.email = ?
        LOGIN;
        $com = $c->prepare($sql);
        $com->execute([$email]);
        $l = $com->get_result()->fetch_assoc();
        if (!$l) {
            $redis->incr($key);
            $redis->expire($key, self::TTL_LOGIN);
            throw new Exception('Usuário/senha inválidos.');
        }
        if ($senha !== null && !password_verify($senha, $l['senha'])) {
            $redis->incr($key);
            $redis->expire($key, self::TTL_LOGIN);
            throw new Exception('Usuário/senha inválidos.');
        }
        self::valida($l['codigo']);
        if ($senha != null) {
            self::regeraId();
        }
    }

    /**
     * @param string $token
     * @return bool
     * @throws Exception
     */
    public static function loginWithWhatsapp(string $token): bool
    {
        //todo pelo amor de deus! coloque contagem de logins por ip e redis, analogamente ao método login.
        $wv = WhatsappValidacao::byToken($token);
        if (!$wv) {
            throw new Exception('Token de login de Whatsapp inválido.');
        }
        if (!$wv->identificacao) {
            throw new Exception('Token de validação de Whatsapp não serve para login.');
        }
        if (!$wv->validacao) {
            return false;
        }
        $delta = new DateTime()->getTimestamp() - new DateTime($wv->validacao)->getTimestamp();
        if ($delta > self::EXPIRACAO_TOKEN) {
            throw new Exception('Token de login de Whatsapp expirado.');
        }
        $u = new Usuario($wv->codUsuario);
        self::login($u->email);
        return true;
    }

    /**
     * @param int $codigo
     * @param bool $ativa
     * @return void
     * @throws Exception
     */
    public static function valida(int $codigo, bool $ativa = false): void
    {
        $usuario = new Usuario($codigo);
        if ($usuario->status == Usuario::STATUS_INATIVO) {
            throw new Exception('Usuário desativado.');
        }
        if (!$ativa && $usuario->status == Usuario::STATUS_PENDENTE) {
            throw new Exception('Usuário pendente de ativação.');
        }
        if ($usuario->perfil != Usuario::PERFIL_MASTER && !$usuario->contaAtiva) {
            throw new Exception('Conta inativa.');
        }
        self::$usuario = $usuario;
        self::$assinatura = Assinatura::vigente(self::$usuario->codigo, self::$usuario->codConta);
        self::salva();
    }

    /**
     * @return void
     */
    public static function salva(): void
    {
        if (!self::$usuario) {
            self::logout();
            return;
        }
        $_SESSION['usuario'] = serialize(self::$usuario);
        $_SESSION['assinatura'] = serialize(self::$assinatura);
        $_SESSION['ultima_validacao'] = $_SERVER['REQUEST_TIME'];
        if (self::$codPersonificador) {
            $_SESSION['cod_personificador'] = self::$codPersonificador;
        } elseif (isset($_SESSION['cod_personificador'])) {
            unset($_SESSION['cod_personificador']);
        }
        self::ini();
    }

    /**
     * @return void
     */
    public static function ini(): void
    {
        if (!isset($_SESSION['usuario'])) {
            return;
        }
        try {
            self::$usuario = unserialize($_SESSION['usuario']);
            self::$codigo = self::$usuario->codigo;
            self::$codConta = self::$usuario->codConta;
            self::$perfil = self::$usuario->perfil;
            self::$assinatura = unserialize($_SESSION['assinatura']);
            self::$codPersonificador = $_SESSION['cod_personificador'] ?? null;
            self::$ultimaValidacao = $_SESSION['ultima_validacao'];
            if (($_SERVER['REQUEST_TIME'] - self::$ultimaValidacao) > self::FREQUENCIA_VALIDACAO) {
                self::valida(self::$codigo);
            }
        } catch (Throwable $e) {
            error_log($e);
            self::logout();
        }
    }

    /**
     * @return void
     */
    public static function logout(): void
    {
        session_regenerate_id(true);
        session_destroy();
        self::$codigo = null;
        self::$codConta = null;
        self::$perfil = null;
        self::$codPersonificador = null;
        self::$ultimaValidacao = null;
        self::$usuario = null;
        self::$assinatura = null;
    }

    /**
     * @return void
     */
    public static function filtraLogadoTrata(): void
    {
        try {
            self::filtraLogado();
        } catch (Exception $ex) {
            self::trata($ex);
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    public static function filtraLogado(): void
    {
        if (!self::logado()) {
            throw new Exception('Não autenticado.', 1);
        }
    }

    /**
     * @return bool
     */
    public static function logado(): bool
    {
        return self::$usuario != null;
    }

    #[NoReturn]
    private static function trata(Exception $e): void
    {
        $url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $query = http_build_query([
            'erro' => $e->getMessage(),
            'url' => $url,
        ]);
        header('Location: ' . SITE . 'app/index.php?' . $query);
        exit;
    }

    /**
     * @param string ...$perfisAutorizados
     * @return void
     */
    public static function filtraPerfilTrata(string ...$perfisAutorizados): void
    {
        try {
            self::filtraPerfil(...$perfisAutorizados);
        } catch (Exception $e) {
            self::trata($e);
        }
    }


    /**
     * @param string ...$perfisAutorizados
     * @return void
     * @throws Exception
     */
    public static function filtraPerfil(string ...$perfisAutorizados): void
    {
        self::filtraLogado();
        if (self::isGaido()) {
            return;
        }
        if (in_array(self::$usuario->perfil, $perfisAutorizados)) {
            return;
        }
        throw new Exception('Não autorizado.', 2);
    }

    /**
     * @param int $codConta
     * @return void
     */
    public static function filtraContaTrata(int $codConta): void
    {
        try {
            self::filtraConta($codConta);
        } catch (Exception $e) {
            self::trata($e);
        }
    }

    /**
     * @param int $codConta
     * @return void
     * @throws Exception
     */
    public static function filtraConta(int $codConta): void
    {
        if (self::$perfil != Usuario::PERFIL_MASTER && $codConta != self::$codConta) {
            throw new Exception('Sem permissão à conta.');
        }
    }

    /**
     * @param int|null $codUsuario
     */
    public static function filtraUsuarioTrata(?int $codUsuario): void
    {
        try {
            self::filtraUsuario($codUsuario);
        } catch (Exception $e) {
            self::trata($e);
        }
    }

    /**
     * @param int|null $codUsuario
     * @throws Exception
     */
    public static function filtraUsuario(?int $codUsuario): void
    {
        Aut::filtraLogado();
        if (Aut::$codigo != $codUsuario && Aut::$codigo != 1) {
            throw new Exception('usuário sem permissão');
        }
    }

    /**
     * @return void
     */
    public static function filtraGaidoTrata(): void
    {
        try {
            self::filtraGaido();
        } catch (Exception $ex) {
            self::trata($ex);
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    public static function filtraGaido(): void
    {
        if (self::$codigo != 1 && self::$codPersonificador != 1) {
            throw new Exception('Requer alta autorização.');
        }
    }

    /**
     * @return bool
     */
    public static function isGaido(): bool
    {
        return self::$codigo == 1;
    }

    /**
     * @return void
     * @throws Exception
     */
    public static function filtraAssinatura(): void
    {
        if (self::isGaido()) {
            return;
        }
        if (self::$assinatura == null) {
            throw new Exception('Requer assinatura.');
        }
        $status = self::$assinatura->status;
        if ($status != Assinatura::STATUS_ATIVA) {
            throw new Exception("Assinatura '$status'");
        }
    }

    /**
     * @return void
     */
    public static function filtraAssinaturaTrata(): void
    {
        try {
            self::filtraAssinatura();
        } catch (Throwable) {
            header('Location: ' . SITE . 'app/assinatura/assine.php');
            exit;
        }
    }

    /**
     * @param int $codUsuario
     * @return void
     * @throws Exception
     */
    public static function personifica(int $codUsuario): void
    {
        if (Sistema::$adminPersonifica) {
            Aut::filtraPerfil(Usuario::PERFIL_MASTER, Usuario::PERFIL_ADMIN);
        } else {
            Aut::filtraPerfil(Usuario::PERFIL_MASTER);
        }
        if ($codUsuario == 1) {
            throw new Exception('Não é possível personificar este usuário.');
        }
        if ($codUsuario == Aut::$codigo) {
            throw new Exception('Não é possível personificar a si mesmo.');
        }
        $usuario = new Usuario($codUsuario);
        self::$codPersonificador = self::$codPersonificador ?: self::$codigo;
        self::$usuario = $usuario;
        self::valida($codUsuario);
        self::salva();
    }

    /**
     * @return void
     * @throws Exception
     */
    public static function despersonifica(): void
    {
        if (!self::$codPersonificador) {
            return;
        }
        self::valida(self::$codPersonificador);
        self::$codPersonificador = null;
        self::salva();
    }

    /**
     * @param string|null $email
     * @param string|null $celular
     * @return string
     * @throws Exception
     */
    public static function new(?string $email, ?string $celular): string
    {
        $token = token();
        $c = My::con();
        if ($email) {
            $com0 = $c->prepare('SELECT EXISTS(SELECT * FROM usuario WHERE email = ?) existe');
            $com0->execute([$email]);
            $l0 = $com0->get_result()->fetch_assoc();
            if (!$l0['existe']) {
                throw new Exception('e-mail não cadastrado');
            }
        }
        $sql = <<< SQL
            insert into aut
            (token, criacao, email, celular)
            values
            (?, now(), ?, ?)
        SQL;
        $celular = Formatos::telefoneBd($celular);
        $com = $c->prepare($sql);
        $com->execute([$token, $email, $celular]);
        return $token;
    }

    /**
     * Devolve o usuário com o email do token informado e opcionalmente invalida o token para usos posteriores.
     *
     * @param string $token
     * @param bool $marcarComoUsado
     * @return Usuario
     * @throws Exception
     */
    public static function token(string $token, bool $marcarComoUsado): Usuario
    {
        $c = My::con();
        $query = <<< QUERY
            select email from aut
            where token = ? AND
                  (uso IS NULL OR TIMESTAMPDIFF(SECOND , criacao, now()) <= ?) 
        QUERY;
        $com = $c->prepare($query);
        $com->execute([$token, self::EXPIRACAO_TOKEN]);
        $l = $com->get_result()->fetch_assoc();
        if (!$l) {
            throw new Exception('Token já utilizado. Utilize outro.');
        }
        if ($marcarComoUsado) {
            $com = $c->prepare('update aut set uso = NOW() where token = ?');
            $com->execute([$token]);
        }
        return Usuario::porEmail($l['email']);
    }

    /**
     * Autentica a sessão corrente com o usuário associado ao token informado.
     *
     * @param string|null $token
     * @return bool
     */
    public static function tokenUse(?string $token): bool
    {
        if (!$token) {
            return false;
        }
        try {
            //todo por que raios um token já usado deveria ser passível de uso futuro?!
            $usuario = self::token($token, false);
            gmail(
                'internauta',
                'luisfernandogaido@gmail.com',
                'token use ' . Sistema::$app,
                "$usuario->codigo $usuario->nome",
                false,
            );
            self::valida($usuario->codigo, true);
            return false;
        } catch (Throwable $e) {
            error_log($e);
            return true;
        }
    }

    /**
     * Dados um perfil e uma conta de empresa, cria e loga automaticamente um usuário com base na sessão caso o usuário
     * não esteja logado. Ideal para jogar um usuário imediatamente para o banco de dados mesmo que ele não tenha criado
     * uma conta ainda, aprimorando a experiência e a coleta de informação desde o primeiro contato do usuário com o
     * sistema.
     *
     * @param int $codConta
     * @param string $perfil
     * @return void
     * @throws Exception
     */
    public static function registraProvisorio(int $codConta, string $perfil): void
    {
        if (self::logado()) {
            return;
        }
        $uasBlocked = [
            'WhatsApp',
            'facebookexternalhit',
            'compatible; Google',
            'LinkedInBot',
        ];
        foreach ($uasBlocked as $ua) {
            if (str_contains($_SERVER['HTTP_USER_AGENT'], $ua)) {
                throw new Exception("registraProvisorio: ua '$ua' blocked");
            }
        }
        $uniqid = uniqid();
        $usuario = new Usuario(0);
        $usuario->codConta = $codConta;
        $usuario->nome = $uniqid;
        $usuario->email = "$uniqid@$uniqid";
        $usuario->senha = $uniqid;
        $usuario->perfil = $perfil;
        $usuario->status = Usuario::STATUS_PROVISORIO;
        $usuario->apelido = $uniqid;
        $usuario->salva();
        Aut::login($usuario->email);
    }

    /**
     * @return bool
     */
    public static function provisorio(): bool
    {
        return self::$usuario->isProvisorio();
    }

    /**
     * @return string|null
     *
     */
    public static function nomeReal(): ?string
    {
        return self::$usuario->nomeReal() ?? null;
    }

    /**
     * @return void
     */
    private static function regeraId(): void
    {
        if (!isset($_SESSION['iniciada'])) {
            session_regenerate_id(true);
            $_SESSION['iniciada'] = true;
        }
    }
}