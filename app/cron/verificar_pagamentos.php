<?php 
@session_start();
require_once("../conexao.php");

$query = $pdo->query("SELECT * from receber where pago != 'Sim' and pago is not null and ref_pix is not null ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_contas = @count($res);
for ($i = 0; $i < $total_contas; $i++) {
	$valor = $res[$i]['valor'];
	$descricao = $res[$i]['descricao'];
	$id = $res[$i]['id'];	
	$cliente = $res[$i]['cliente'];
	$vencimento = $res[$i]['data_venc'];
	$ref_pix = $res[$i]['ref_pix'];

	$valorF = @number_format($valor, 2, ',', '.');

	

	require('../painel/pagamentos/consultar_pagamento.php');     
	echo $status_api;

		
}
echo '<br>';
echo $total_contas;

 ?>