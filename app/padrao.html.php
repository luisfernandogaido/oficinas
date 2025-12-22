<div id="home">
    <?php if ($podeDivulgar): ?>
        <a class="item" href="os/index.php">
            <span class="icon request"></span>
            <span class="desc">
                <h2>Painel</h2>
                <p>
                    Analise novas OS e acompanhe tudo que está acontecendo na bancada.
                </p>
        </span>
        </a>
        <a class="item" href="workspaces/share.php">
            <span class="icon workspace-share"></span>
            <span class="desc">
                <h2>Divulgue seu estabelecimento</h2>
                <p>
                    Copie seu link ou gere seu QR Code para alguém próximo de você.
                </p>
        </span>
        </a>
    <?php endif; ?>
    <a class="item" href="spread/index.php" style="display: none">
        <span class="icon viral"></span>
        <span class="desc">
                <h2>Espalhe</h2>
                <p>
                    Compartilhe com mais pessoas.
                    Dessa forma, poderemos manter e melhorar o serviço.
                </p>
        </span>
    </a>
    <a class="item" href="workspaces/workspace.php">
        <span class="icon workspace-auto"></span>
        <span class="desc">
                <h2>Informações básicas</h2>
                <p>
                    Informe nome, logo e endereço da sua oficina.
                </p>
        </span>
    </a>
    <button class="item instalar hidden">
        <span class="icon install"></span>
        <span class="desc">
                <h2>Criar a atalho</h2>
                <p>Colocaremos um ícone na sua área de trabalho.</p>
        </span>
    </button>
</div>
