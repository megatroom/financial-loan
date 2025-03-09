<?php 

if($dias_criar_parcelas != ""){

	if($dias_criar_parcelas != "DomingoSegunda"){

		$diasemana = array("Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sábado");
		$diasemana_numero = date('w', strtotime($novo_vencimento));

		if($dias_criar_parcelas == "Final de Semana"){
			if ($diasemana_numero == 6) { 
			    $novo_vencimento = date('Y-m-d', strtotime("+2 days",strtotime($novo_vencimento )));
			}
		}

		if ($diasemana_numero == 0 ) {
			$novo_vencimento = date('Y-m-d', strtotime("+1 days",strtotime($novo_vencimento )));
		}

	}else{
		$diasemana = array("Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sábado");
		$diasemana_numero = date('w', strtotime($novo_vencimento));

		if($dias_criar_parcelas == "Final de Semana"){
			if ($diasemana_numero == 1) { 
			    $novo_vencimento = date('Y-m-d', strtotime("+1 days",strtotime($novo_vencimento )));
			}
		}

		if ($diasemana_numero == 0 ) {
			$novo_vencimento = date('Y-m-d', strtotime("+2 days",strtotime($novo_vencimento )));
		}
	}

}


for ($if=0; $if < 7; $if++) { 
		
	
	$query_f = $pdo->query("SELECT * from feriados where data = '$novo_vencimento'");
	$res_f = $query_f->fetchAll(PDO::FETCH_ASSOC);
	$total_reg_f = @count($res_f);
	if($total_reg_f > 0){
		$novo_vencimento = date('Y-m-d', strtotime("+1 days",strtotime($novo_vencimento)));
	}

	}



if($dias_criar_parcelas != ""){


	$diasemana = array("Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sábado");
	$diasemana_numero = date('w', strtotime($novo_vencimento));

	if($dias_criar_parcelas == "Final de Semana"){
		if ($diasemana_numero == 6) { 
		    $novo_vencimento = date('Y-m-d', strtotime("+2 days",strtotime($novo_vencimento )));
		}
	}

	if ($diasemana_numero == 0 ) {
		$novo_vencimento = date('Y-m-d', strtotime("+1 days",strtotime($novo_vencimento )));
	}

}

 ?>