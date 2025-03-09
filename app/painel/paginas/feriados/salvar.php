<?php 
$tabela = 'feriados';
require_once("../../../conexao.php");


$data = $_POST['data'];
$id = $_POST['id'];

//validacao nome
$query = $pdo->query("SELECT * from $tabela where data = '$data'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if(@count($res) > 0 and $id != $id_reg){
	echo 'Data já Cadastrada!';
	exit();
}


if($id == ""){
$query = $pdo->prepare("INSERT INTO $tabela SET data = :data ");
	
}else{
$query = $pdo->prepare("UPDATE $tabela SET data = :data where id = '$id'");
}
$query->bindValue(":data", "$data");
$query->execute();

echo 'Salvo com Sucesso';
 ?>