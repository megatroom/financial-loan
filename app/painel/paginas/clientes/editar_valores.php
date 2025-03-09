<?php 
$tabela = 'receber';
require_once("../../../conexao.php");

$id = $_POST['id'];
$valor = $_POST['valor'];
$valor = str_replace('.', '', $valor);
$valor = str_replace(',', '.', $valor);
$data = $_POST['data'];

$pdo->query("UPDATE $tabela SET data_venc = '$data', valor = '$valor' WHERE id = '$id' ");
echo 'Editado com Sucesso';
?>