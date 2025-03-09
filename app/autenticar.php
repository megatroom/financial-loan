<?php

@session_set_cookie_params(['httponly' => true]);

@session_start();

@session_regenerate_id(true);

require_once("conexao.php");

$usuario = filter_var(@$_POST['usuario'], @FILTER_SANITIZE_STRING);

$senha = filter_var(@$_POST['senha'], @FILTER_SANITIZE_STRING);



$query = $pdo->prepare("SELECT * from usuarios where email = :email order by id desc");

$query->bindValue(":email", "$usuario");

$query->execute();

$res = $query->fetchAll(PDO::FETCH_ASSOC);

$linhas = @count($res);



if ($linhas > 0) {





	if (!password_verify($senha, $res[0]['senha_crip'])) {

		echo '<script>window.alert("Dados Incorretos!!")</script>';

		echo '<script>window.location="index.php"</script>';

		exit();

	}



	if ($res[0]['ativo'] != 'Sim') {

		echo '<script>window.alert("Seu acesso foi desativado!!")</script>';

		echo '<script>window.location="index.php"</script>';

	}



	$_SESSION['nome'] = $res[0]['nome'];

	$_SESSION['id'] = $res[0]['id'];

	$_SESSION['nivel'] = $res[0]['nivel'];

	$_SESSION['token_ATEFDFSFSFAF'] = 'FDSFFDASFDSGFE';



	echo '<script>window.location="painel"</script>';

} else {

	echo '<script>window.alert("Dados Incorretos!!")</script>';

	echo '<script>window.location="index.php"</script>';

}





?>