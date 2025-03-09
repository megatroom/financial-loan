<?php 
require_once("../../../conexao.php");

$id = $_POST['id'];
$mensagem_whats = $_POST['mensagem'];

$query2 = $pdo->query("SELECT * from clientes where id = '$id'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome = $res2[0]['nome'];
$telefone = $res2[0]['telefone'];

$tel_cliente = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);
$telefone_envio = $tel_cliente;

//mensagem da cobrança
$mensagem = '*'.$nome_sistema.'* %0A%0A';
$mensagem .= $mensagem_whats;

require('../../apis/texto.php');


?>