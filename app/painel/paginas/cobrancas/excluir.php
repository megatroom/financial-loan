<?php 
$tabela = 'cobrancas';
require_once("../../../conexao.php");

$id = $_POST['id'];

if($token != "" and $instancia != ""){
//recuperar o hash e excluir o agendamento das mensagens
$query2 = $pdo->query("SELECT * from receber where referencia = 'Cobrança' and id_ref = '$id'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
for($i=0; $i<@count($res2); $i++){
$hash = @$res2[$i]['hash'];
require("../../apis/cancelar_agendamento.php");

$hash = @$res2[$i]['hash2'];
require("../../apis/cancelar_agendamento.php");

}
}

$pdo->query("DELETE FROM $tabela WHERE id = '$id' ");
$pdo->query("DELETE FROM receber WHERE referencia = 'Cobrança' and id_ref = '$id' ");
$pdo->query("DELETE FROM pagar WHERE referencia = 'Cobrança' and id_ref = '$id' ");

echo 'Excluído com Sucesso';



?>