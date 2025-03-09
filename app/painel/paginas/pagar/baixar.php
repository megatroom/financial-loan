<?php 
$tabela = 'pagar';
require_once("../../../conexao.php");

@session_start();
$id_usuario = @$_SESSION['id'];

$id = $_POST['id'];

$pdo->query("UPDATE $tabela SET pago = 'Sim', usuario_pgto = '$id_usuario', data_pgto = curDate() WHERE id = '$id' ");
echo 'Baixado com Sucesso';
?>