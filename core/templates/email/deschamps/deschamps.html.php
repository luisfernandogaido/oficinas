<?php
use templates\email\Deschamps;

/** @var Deschamps $this */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<table align="center" cellpadding="0" cellspacing="0"
       style="margin:0;color:#333333;max-width:685px;padding:0px 20px;margin:38px auto 0px auto">
    <tbody style="margin:0">
    <tr style="margin:0">
        <td style=" margin:0;
                    padding:0;
                    text-align:left;
                    font-family: system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Helvetica Neue,Helvetica,Arial,
                                 sans-serif;
                    font-size:17px;
                    line-height:26px">

            <?= $this->body ?>
            <?php if ($this->rodape): ?>
                <a style="  margin:0;
                            color:#0070c9;
                            text-decoration:underline;
                            font-weight:bold;
                            padding-top:20px;
                            padding-bottom:20px"
                   href="https://gaido.space/app/index.php">Cancelar inscrição</a>
                |
                <a style="  margin:0;
                        color:#0070c9;
                        text-decoration:underline;
                        font-weight:bold;
                        padding-top:20px;
                        padding-bottom:20px"
                   href="https://gaido.space/app/index.php">Indicar Newsletter</a>

                <div style="margin:0;font-size:11px;color:#777777">
                    Rua Ory Pinheiro Brisola, 4-35, Bauru/SP, 17051-300
                </div>
            <?php endif; ?>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>