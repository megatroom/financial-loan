<?php 
require_once("../conexao.php");
$data_atual = date('Y-m-d');

$hora_atual = date('H');
$hora_alerta = $hora_atual + 1;

//variaveis para os disparos de notificações
$hora_rand = rand($hora_alerta, $hora_alerta);
$minutos_rand = rand(0, 59);
if($hora_rand < 10){
	$hora_rand = '0'.$hora_rand;
}
if($minutos_rand < 10){
	$minutos_rand = '0'.$minutos_rand;
}	

$hora_random_script = $hora_rand.':'.$minutos_rand.':00';

//verificar se tem conta vencidas
	$query = $pdo->query("SELECT * from receber where pago = 'Não' and data_venc = curDate()");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$linhas = @count($res);
	if($linhas > 0){
		for($i=0; $i<$linhas; $i++){	
					
			$id_conta = $res[$i]['id'];	
			$pdo->query("UPDATE receber set hora_alerta = '$hora_random_script', alerta = null where id = '$id_conta'");
		        
			}
	}


echo 'Total de '.$linhas.' lembretes de pagamentos agendados para serem disparados dentro de uma hora !!';

?>