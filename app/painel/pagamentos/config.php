<?php
require("../../conexao.php");
require("tokens.php");
$modoProducao = true; // Defina isso como true para usar credenciais de produção e false para usar credenciais de teste

if ($modoProducao) {
    $NOME_SITE = $nome_sistema; // Nome do seu site em produção
    $TOKEN_MERCADO_PAGO = $access_token; // Token do Mercado Pago em produção
    $TOKEN_MERCADO_PAGO_PUBLICO = $public_key; // Token público do Mercado Pago em produção (PUBLIC KEY)
    
} else {
    $NOME_SITE = "Meu pix (Modo Teste)"; // Nome do seu site em modo de teste
    $TOKEN_MERCADO_PAGO = "APP_USR-7645692252055791-101021-ba91ccf6cd290bc3115e3270a30edb1e-131939455"; // Token do Mercado Pago em teste
    $TOKEN_MERCADO_PAGO_PUBLICO = "TEST-da159e2a-4731-4fe4-abf0-5d890ab6c0e0"; // Token público do Mercado Pago em teste
    
}


$DESCRICAO_PAGAMENTO = "Cobranças e Empréstimos"; // OBRIGATÓRIO: DESCRIÇÃO PAGAMENTO O PAGAMENTO

$MSG_APOS_PAGAMENTO = "Recebemos seu pagamento.";

$URL_REDIRECIONAR = "Sim"; // LINK PARA DIRECIONAR 6 SEGUNDOS APÓS RECEBER O PAGAMENTO (Coloque Sim caso queira que ele redirecione para o comprovante)

$PAGAMENTO_MINIMO = "0"; // NÃO OBRIGATORIO: VALOR PARA PAGAMENTO MINIMO. EXEMPLO: 10,00 / 20,40

$EMAIL_NOTIFICACAO = ""; // OBRIGATÓRIO. SE NÃO FOR CONFIGURADO O CLIENTE DEVERÁ INFORMAR.

$CPF_PADRAO = ""; // É OBRIGATÓRIO O CPF. SE NÃO FOI CONFIGURADO AQUI O CLIENTE DEVERÁ INFORMAR. 

//$URL_NOTIFICACAO = $url_sistema."painel/pagamentos/webhook.php";  // URL AO HOSPDAR
$URL_NOTIFICACAO = "https://google.com";  // URL LOCAL

$VALOR_PADRAO = "5,00"; // EX: 20,00

$ATIVAR_PIX = "1";

$ATIVAR_BOLETO = "1";

$ATIVAR_CARTAO_CREDITO = "1";

$ATIVAR_CARTAO_DEBIDO = "1";