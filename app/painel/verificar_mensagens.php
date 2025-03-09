<?php 
require_once("../conexao.php");
$total_vencendo = 0;
$total_vencidas = 0;


	$query = $pdo->query("SELECT * from receber where pago = 'Não' and data_venc = curDate()");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_vencendo = @count($res);

		$query = $pdo->query("SELECT * from receber where pago = 'Não' and data_venc < curDate()");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_vencidas = @count($res);


echo '<span style="font-size:19px; margin-right:50px"><b><img src="images/vencendo.png" width="30px"> Vencendo Hoje:</b> <span style="color:blue">'.$total_vencendo.'</span></span>';
echo '<span style="font-size:19px"><b><img src="images/vencidas.png" width="30px"> Vencidas:</b> <span style="color:red">'.$total_vencidas.'</span></span><br>';
?>