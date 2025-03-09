<?php



//definir fuso horário

date_default_timezone_set('America/Sao_Paulo');



//dados conexão bd local

$servidor = getenv('DB_HOST') ?: 'localhost';

$banco = 'escritor_emprestimo';

$usuario = 'escritor_emprestimo';

$senha = 'Hl)O=qKRv4Sl';



$url_sistema = "https://$_SERVER[HTTP_HOST]/";

$url = explode("//", $url_sistema);

if ($url[1] == 'localhost/') {

    $url_sistema = "http://$_SERVER[HTTP_HOST]/emprestimos/";

}



try {

    $pdo = new PDO("mysql:dbname=$banco;host=$servidor;charset=utf8", "$usuario", "$senha");

} catch (Exception $e) {

    echo 'Erro ao conectar ao banco de dados!<br>';

    echo $e;

}







//variaveis para os disparos de notificações

$hora_rand = rand(8, 10);

$minutos_rand = rand(0, 59);

if ($hora_rand < 10) {

    $hora_rand = '0' . $hora_rand;

}

if ($minutos_rand < 10) {

    $minutos_rand = '0' . $minutos_rand;

}



$hora_random = $hora_rand . ':' . $minutos_rand . ':00';



//variaveis globais

$nome_sistema = 'Nome Sistema';

$email_sistema = 'contato@hugocursos.com.br';

$telefone_sistema = '(31)97527-5084';



$query = $pdo->query("SELECT * from config");

$res = $query->fetchAll(PDO::FETCH_ASSOC);

$linhas = @count($res);

if ($linhas == 0) {

    $pdo->query("INSERT INTO config SET nome = '$nome_sistema', email = '$email_sistema', telefone = '$telefone_sistema', logo = 'logo.png', logo_rel = 'logo.jpg', icone = 'icone.png', ativo = 'Sim', dias_criar_parcelas = 'Final de Semana', verificar_pagamentos = 'Não', seletor_api = 'menuia', recursos = 'Empréstimos e Cobranças', cobrar_automatico = 'Sim'");

} else {

    $nome_sistema = $res[0]['nome'];

    $email_sistema = $res[0]['email'];

    $telefone_sistema = $res[0]['telefone'];

    $endereco_sistema = $res[0]['endereco'];

    $instagram_sistema = $res[0]['instagram'];

    $logo_sistema = $res[0]['logo'];

    $logo_rel = $res[0]['logo_rel'];

    $icone_sistema = $res[0]['icone'];

    $ativo_sistema = $res[0]['ativo'];

    $juros_sistema = $res[0]['juros'];

    $multa_sistema = $res[0]['multa'];

    $juros_emprestimo = $res[0]['juros_emp'];

    $taxa_sistema = $res[0]['taxa_sistema'];

    $instancia = $res[0]['instancia'];

    $token = $res[0]['token'];

    $dias_aviso = $res[0]['dias_aviso'];

    $cnpj_sistema = $res[0]['cnpj'];

    $marca_dagua = $res[0]['marca_dagua'];

    $dias_criar_parcelas = $res[0]['dias_criar_parcelas'];

    $pix_sistema = $res[0]['pix_sistema'];

    $saldo_inicial = $res[0]['saldo_inicial'];

    $verificar_pagamentos = $res[0]['verificar_pagamentos'];

    $seletor_api = $res[0]['seletor_api'];

    $assinatura = @$res[0]['assinatura'];

    $recursos = @$res[0]['recursos'];

    $cobrar_automatico = @$res[0]['cobrar_automatico'];

    $public_key = @$res[0]['public_key'];

    $access_token = @$res[0]['access_token'];



    $instancia_whatsapp = $res[0]['instancia'];

    $token_whatsapp = $res[0]['token'];



    if ($assinatura == "") {

        $assinatura = "sem-foto.png";

    }



    if ($ativo_sistema != 'Sim' and $ativo_sistema != '') { ?>

        <style type="text/css">
            @media only screen and (max-width: 700px) {

                .imgsistema_mobile {

                    width: 300px;

                }



            }
        </style>

        <div style="text-align: center; margin-top: 100px">

            <img src="img/bloqueio.png" class="imgsistema_mobile">

        </div>

        <?php

        exit();

    }



}

?>
