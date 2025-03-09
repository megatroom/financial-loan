<?php 
$tabela = 'receber';
require_once("../../../conexao.php");
@session_start();
$id_usuario = @$_SESSION['id'];

$valor = $_POST['valor'];
$valor = str_replace('.', '', $valor);
$valor = str_replace(',', '.', $valor);
$descricao = $_POST['descricao'];
$data_venc = $_POST['data_venc'];
$obs = $_POST['obs'];
$cliente = $_POST['cliente'];
$id = $_POST['id'];


if($id == ""){
$query = $pdo->prepare("INSERT INTO $tabela SET descricao = :descricao, valor = :valor, data = curDate(), data_venc = '$data_venc', usuario_lanc = '$id_usuario', referencia = 'Conta', pago = 'Não', obs = :obs, cliente = '$cliente', hora_alerta = '$hora_random' ");
	
}else{
$query = $pdo->prepare("UPDATE $tabela SET descricao = :descricao, valor = :valor, data = curDate(), data_venc = '$data_venc', usuario_lanc = '$id_usuario', referencia = 'Conta', pago = 'Não', obs = :obs, cliente = '$cliente' where id = '$id'");
}
$query->bindValue(":descricao", "$descricao");
$query->bindValue(":valor", "$valor");
$query->bindValue(":obs", "$obs");
$query->execute();

echo 'Salvo com Sucesso';
 ?>