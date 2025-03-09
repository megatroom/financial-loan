<?php 
require_once("../../../conexao.php");

$valor = $_POST['valor'];
$multa = $_POST['multa'];
$juros = $_POST['juros'];
$forma_pgto = $_POST['forma_pgto'];

if($valor == ""){
	$valor = 0;
}

if($multa == ""){
	$multa = 0;
}

if($juros == ""){
	$juros = 0;
}

$query = $pdo->query("SELECT * FROM formas_pgto where nome = '$forma_pgto'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$taxa = @$res[0]['taxa'];

if($taxa == ""){
	$taxa = 0;
}

$valor_final = $valor + $multa + $juros;

if($taxa_sistema == 'Cliente'){
	$subtotal = $valor_final + $valor_final * $taxa / 100;
}else{
	$subtotal = $valor_final - $valor_final * $taxa / 100;
}
$subtotalF = number_format($subtotal, 2, ',', '.');
echo $subtotalF;
?>