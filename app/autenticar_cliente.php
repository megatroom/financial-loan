<?php 
@session_set_cookie_params(['httponly' => true]);
@session_start();
@session_regenerate_id(true);
require_once("conexao.php");
$cpf = filter_var(@$_POST['cpf'], @FILTER_SANITIZE_STRING);

$query = $pdo->prepare("SELECT * from clientes where cpf = :cpf order by id desc");
$query->bindValue(":cpf", "$cpf");
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);

if($linhas > 0){
	
	$_SESSION['nome'] = $res[0]['nome'];
	$_SESSION['id'] = $res[0]['id'];	
	$_SESSION['token_IFDSFDSFFSAS'] = 'IODIIIJFDFDSS';

	echo '<script>window.location="painel_cliente"</script>';
}else{
	echo '<script>window.alert("Dados Incorretos!!")</script>'; 
	echo '<script>window.location="acesso"</script>';  
}


 ?>
