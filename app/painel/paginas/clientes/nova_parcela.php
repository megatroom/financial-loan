<?php 
@session_start();
$id_usuario = @$_SESSION['id'];
$tabela = 'receber';
require_once("../../../conexao.php");

$data_atual = date('Y-m-d');

$id = $_POST['id'];
$descricao = $_POST['descricao'];
$valor = $_POST['valor'];
$data_venc = $_POST['data_venc'];

$query2 = $pdo->query("SELECT * from emprestimos where id = '$id'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$cliente = @$res2[0]['cliente'];

//buscar a ultima parcela
$query2 = $pdo->query("SELECT * from receber where referencia = 'Empréstimo' and id_ref = '$id' order by id desc limit 1");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$ultima_parcela = @$res2[0]['parcela'];
$nova_parcela = $ultima_parcela + 1;
$i = $nova_parcela;

$pdo->query("INSERT INTO receber SET cliente = '$cliente', referencia = 'Empréstimo', id_ref = '$id', valor = '$valor', parcela = '$nova_parcela', usuario_lanc = '$id_usuario', data = curDate(), data_venc = '$data_venc', pago = 'Não', descricao = '$descricao', frequencia = '0', recorrencia = '', hora_alerta = '$hora_random' ");
$ult_id_conta = $pdo->lastInsertId();


$pdo->query("UPDATE emprestimos set parcelas = '$nova_parcela' where id = '$id'");


$novo_vencimento = @$data_venc;
$valor_parcela_final = @$valor;

echo 'Salvo com Sucesso';
?>