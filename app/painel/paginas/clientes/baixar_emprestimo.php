<?php 
$tabela = 'receber';
require_once("../../../conexao.php");

@session_start();
$id_usuario = @$_SESSION['id'];

$id = $_POST['id'];
$data_baixa = $_POST['data_baixa_emprestimo'];
$forma_pgto = $_POST['forma_pgto_emprestimo'];
$valor_final = $_POST['valor_final_emprestimo'];
$status_baixa = $_POST['status_baixa'];

$valor_final = str_replace('.', '', $valor_final);
$valor_final = str_replace(',', '.', $valor_final);

$pdo->query("UPDATE emprestimos SET status = '$status_baixa' WHERE id = '$id' ");
$pdo->query("DELETE FROM receber WHERE referencia = 'Empréstimo' and id_ref = '$id' and pago != 'Sim'");


$query2 = $pdo->query("SELECT * from emprestimos where id = '$id'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$cliente = @$res2[0]['cliente'];

if($valor_final > 0){
	$pdo->query("INSERT INTO receber SET cliente = '$cliente', referencia = 'Empréstimo', id_ref = '$id', parcela = '0', usuario_lanc = '$id_usuario', data = curDate(), data_venc = curDate(), descricao = 'Baixa Empréstimo', frequencia = '0', data_pgto = '$data_baixa', pago = 'Sim', forma_pgto = '$forma_pgto', valor = '$valor_final', usuario_pgto = '$id_usuario', hora = curTime(), hora_alerta = '$hora_random'");
	$id = $pdo->lastInsertId();
	echo 'Salvo com Sucesso*'.$id;

}



?>