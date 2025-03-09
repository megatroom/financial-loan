<?php 
require_once("../conexao.php");
$total_verificados = 0;
$total_aprovados = 0;

//verificar se tem conta paga
	$query = $pdo->query("SELECT * from receber where pago != 'Sim' and ref_pix != ''");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$linhas = @count($res);
	if($linhas > 0){
		for($i=0; $i<$linhas; $i++){
			$total_verificados += 1;
			$ref_pix = $res[$i]['ref_pix'];	
		        require('pagamentos/consultar_pagamento.php');   
		        if($status_api == 'approved'){
		        	$total_aprovados += 1;
		        }   
			}
	}

echo '<span style="font-size:25px"><b><img src="images/verificadas.png" width="60px"> Verificadas:</b> <span style="color:blue">'.$total_verificados.'</span></span><br>';
echo '<span style="font-size:25px"><b><img src="images/aprovadas.png" width="60px"> Aprovadas:</b> <span style="color:green">'.$total_aprovados.'</span></span><br>';
?>