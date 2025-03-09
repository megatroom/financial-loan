<?php 
require_once("../conexao.php");
$data_atual = date('Y-m-d');

//verificar se tem conta vencidas
	$query = $pdo->query("SELECT * from receber where pago = 'Não'");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$linhas = @count($res);
	if($linhas > 0){
		for($i=0; $i<$linhas; $i++){			
			
			$hash = $res[$i]['hash'];	
			$hash2 = $res[$i]['hash2'];						

			if($hash != ""){				
				require("apis/cancelar_agendamento.php");
			}

			if($hash2 != ""){
				$hash = $hash2;
				require("apis/cancelar_agendamento.php");
			}
						
		        
		}
	}


echo 'Total de '.$linhas.' mensagens da api canceladas!!';

?>