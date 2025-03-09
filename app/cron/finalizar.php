<?php 
require_once("../conexao.php");
//finalizar os emprestimos que estiverem com as parcelas todas pagas, pode chamar ele uma vez ao dia

$emprestimos_finalizados = 0;
$query = $pdo->query("SELECT * from emprestimos where status is null");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
	for($i=0; $i<$linhas; $i++){
	$id = $res[$i]['id'];

	$query2 = $pdo->query("SELECT * FROM receber where referencia = 'Empréstimo' and id_ref = '$id' and pago = 'Não'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = @count($res2);	
		if($total_reg == 0){
			$pdo->query("UPDATE emprestimos SET status = 'Finalizado' where id = '$id'");
			$emprestimos_finalizados += 1;
		}
	}

}

echo 'Empréstimos verificados : '.$linhas;
echo '<br>';
echo 'Empréstimos Finalizados : '.$emprestimos_finalizados;
 ?>