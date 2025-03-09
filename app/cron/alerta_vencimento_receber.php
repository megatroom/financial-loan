<?php 
require_once("../conexao.php");

$query = $pdo->query("SELECT * from receber where data_venc = curDate() and pago != 'Sim' and (alerta is null or alerta != 'Sim') and cliente > 0 and cliente is not null and hora_alerta <= curTime() ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_pagar_vencidas = @count($res);
for ($i = 0; $i < $contas_pagar_vencidas; $i++) {
	$valor = $res[$i]['valor'];
	$descricao = $res[$i]['descricao'];
	$id = $res[$i]['id'];	
	$cliente = $res[$i]['cliente'];
	$vencimento = $res[$i]['data_venc'];
	$parcela = $res[$i]['parcela'];

	$vencimentoF = implode('/', array_reverse(@explode('-', $vencimento)));

	$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if (@count($res2) > 0) {
		$nome_cliente = $res2[0]['nome'];
		$telefone_cliente = $res2[0]['telefone'];
	} else {
		$nome_cliente = 'Sem Registro';
		$telefone_cliente = "";
	}

$telefone_envio = '55' . preg_replace('/[ ()-]+/', '', $telefone_cliente);

$link_pgto = $url_sistema.'pagar/'.$id;
$valorF = @number_format($valor, 2, ',', '.');

$mensagem = '💰_Sua Parcela vence Hoje '.$nome_sistema.'_ %0A';

if($parcela > 0){
		$mensagem .= 'Parcela: *'.$parcela.'* %0A';
	}

$mensagem .= 'Valor: *'.$valorF.'* %0A';
$mensagem .= 'Vencimento: *'.$vencimentoF.'* %0A%0A';
if($pix_sistema != ""){
    $mensagem .= '*Chave Pix:* %0A';
    $mensagem .= $pix_sistema;  
}else{
    $mensagem .= '*Link Pagamento:* %0A';
    $mensagem .= $link_pgto;
}


require('texto.php');

		if(@$status_mensagem == "Mensagem enviada com sucesso." and $seletor_api == 'menuia'){
			$pdo->query("UPDATE receber SET alerta = 'Sim' where id = '$id'");
		}

		if($seletor_api != 'menuia'){
			$pdo->query("UPDATE receber SET alerta = 'Sim' where id = '$id'");
		}


}

echo $contas_pagar_vencidas;

 ?>