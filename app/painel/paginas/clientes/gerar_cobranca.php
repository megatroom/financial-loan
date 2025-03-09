<?php 
$tabela = 'receber';
require_once("../../../conexao.php");
$data_atual = date('Y-m-d');

$parcela = $_POST['parcela'];
$valor = $_POST['valor'];
$data = $_POST['data'];
$telefone = $_POST['telefone'];
$multa = $_POST['multa'];
$juros = $_POST['juros'];
$id_conta = $_POST['id_par'];
$dias_vencido = @$_POST['dias_vencido'];

$tel_cliente = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);
$telefone_envio = $tel_cliente;

$valorF = @number_format($valor, 2, ',', '.');
$jurosF = @number_format($juros, 2, ',', '.');
$multaF = @number_format($multa, 2, ',', '.');
$dataF = implode('/', array_reverse(@explode('-', $data)));

if(strtotime($data) < strtotime($data_atual)){
	$titulo_mensagem = 'Sua Parcela Venceu!';
}else{
	$titulo_mensagem = 'Lembrete de Pagamento!';
}

$link_pgto = $url_sistema.'pagar/'.$id_conta;


//mensagem da cobrança
$mensagem = '*'.$nome_sistema.'* %0A';
$mensagem .= '_'.$titulo_mensagem.'_ %0A';
$mensagem .= 'Parcela: *'.$parcela.'* %0A';
if($multa > 0){
	$mensagem .= 'Multa Atraso: *R$ '.$multaF.'* %0A';
}

if($juros > 0){
	$mensagem .= 'Júros Atraso: *R$ '.$jurosF.'* %0A';
	$mensagem .= 'Dias Atraso: *'.$dias_vencido.'* %0A';
}
$mensagem .= 'Valor: *R$ '.$valorF.'* %0A';
$mensagem .= 'Vencimento: *'.$dataF.'* %0A%0A';
if($pix_sistema != ""){
	$mensagem .= '*Chave Pix:* %0A';
	$mensagem .= $pix_sistema;	
}else{
	$mensagem .= '*Link Pagamento:* %0A';
	$mensagem .= $link_pgto;
}

//api wordmensagens (com urlenconde)
//$mensagem .= urlencode($link_pgto);

require('../../apis/texto.php');

?>