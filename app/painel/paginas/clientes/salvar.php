<?php 
$tabela = 'clientes';
require_once("../../../conexao.php");

$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$data_nasc = $_POST['data_nasc'];
$data_nasc = implode('-', array_reverse(explode('/', $data_nasc)));
$endereco = $_POST['endereco'];
$obs = $_POST['obs'];
$cpf = $_POST['cpf'];
$pix = $_POST['pix'];
$indicacao = $_POST['indicacao'];
$bairro = $_POST['bairro'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$cep = $_POST['cep'];
$id = $_POST['id'];
$pessoa = $_POST['pessoa'];
$status = $_POST['status'];

$nome_sec = $_POST['nome_sec'];
$telefone_sec = $_POST['telefone_sec'];
$endereco_sec = $_POST['endereco_sec'];
$grupo = $_POST['grupo'];

if($cpf != ""){
	//validacao cpf
$query = $pdo->query("SELECT * from $tabela where cpf = '$cpf'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if(@count($res) > 0 and $id != $id_reg){
	echo 'CPF já Cadastrado!';
	exit();
}
}


//validacao telefone
$query = $pdo->query("SELECT * from $tabela where telefone = '$telefone'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_reg = @$res[0]['id'];
if(@count($res) > 0 and $id != $id_reg){
	echo 'Telefone já Cadastrado!';
	exit();
}

if($id == ""){
$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, email = :email, cpf = :cpf, telefone = :telefone, data_cad = curDate(), endereco = :endereco, data_nasc = '$data_nasc', obs = :obs, pix = :pix, indicacao = :indicacao, bairro = :bairro, estado = :estado, cidade = :cidade, cep = :cep, pessoa = :pessoa, nome_sec = :nome_sec, telefone_sec = :telefone_sec, endereco_sec = :endereco_sec, grupo = :grupo, status = :status ");
	
}else{
$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, email = :email, cpf = :cpf, telefone = :telefone, endereco = :endereco, data_nasc = '$data_nasc', obs = :obs, pix = :pix, indicacao = :indicacao, bairro = :bairro, estado = :estado, cidade = :cidade, cep = :cep, pessoa = :pessoa, nome_sec = :nome_sec, telefone_sec = :telefone_sec, endereco_sec = :endereco_sec, grupo = :grupo, status = :status where id = '$id'");
}
$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":endereco", "$endereco");
$query->bindValue(":cpf", "$cpf");
$query->bindValue(":obs", "$obs");
$query->bindValue(":pix", "$pix");
$query->bindValue(":indicacao", "$indicacao");
$query->bindValue(":bairro", "$bairro");
$query->bindValue(":cidade", "$cidade");
$query->bindValue(":estado", "$estado");
$query->bindValue(":cep", "$cep");
$query->bindValue(":pessoa", "$pessoa");

$query->bindValue(":nome_sec", "$nome_sec");
$query->bindValue(":telefone_sec", "$telefone_sec");
$query->bindValue(":endereco_sec", "$endereco_sec");
$query->bindValue(":grupo", "$grupo");
$query->bindValue(":status", "$status");
$query->execute();

echo 'Salvo com Sucesso';
 ?>