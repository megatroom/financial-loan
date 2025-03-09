<?php 
if(@$home == 'ocultar'){
	echo "<script>window.location='../index.php'</script>";
    exit();
}

//total de clientes
$query = $pdo->query("SELECT * from clientes ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_clientes = @count($res);

//total de empréstimos mês
$query = $pdo->query("SELECT * from emprestimos where data >= '$data_mes' and data <= '$data_final_mes' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_emprestimos_mes_inicio = @count($res);
$total_emprestimos_mes = @count($res);


//total de cobrancas mês
$query = $pdo->query("SELECT * from cobrancas where data >= '$data_mes' and data <= '$data_final_mes' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_cobrancas_mes_inicio = @count($res);
$total_cobrancas_mes = @count($res);


//total vencidos
$query = $pdo->query("SELECT * from receber where data_venc < curDate() and pago != 'Sim' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_parcelas_debitos = @count($res);
$total_emprestimos_vencidos = 0;
for($i=0; $i<$total_parcelas_debitos; $i++){
	$valor = $res[$i]['valor'];
	$total_emprestimos_vencidos += $valor;
}
$total_emprestimos_vencidosF = number_format($total_emprestimos_vencidos, 2, ',', '.');


//total de empréstimos receber mes pendentes
$query = $pdo->query("SELECT * from receber where data_venc >= '$data_mes' and data_venc <= '$data_final_mes'  and pago != 'Sim' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
$total_receber_mes = 0;
for($i=0; $i<$linhas; $i++){
	$valor = $res[$i]['valor'];
	$total_receber_mes += $valor;
}
$total_receber_mesF = number_format($total_receber_mes, 2, ',', '.');



//total a receber no mes
$query = $pdo->query("SELECT * from receber where data_venc >= '$data_mes' and data_venc <= '$data_final_mes'  ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
$total_receber_mes_emprestimos = 0;
for($i=0; $i<$linhas; $i++){
	$valor = $res[$i]['valor'];
	$total_receber_mes_emprestimos += $valor;
}
$total_receber_mes_emprestimosF = number_format($total_receber_mes_emprestimos, 2, ',', '.');


//total recebido no mes
$query = $pdo->query("SELECT * from receber where data_venc >= '$data_mes' and data_venc <= '$data_final_mes'  and pago = 'Sim' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
$total_recebido = 0;
for($i=0; $i<$linhas; $i++){
	$valor = $res[$i]['valor'];
	$total_recebido += $valor;
}
$total_recebidoF = number_format($total_recebido, 2, ',', '.');


if($total_recebido > 0 and $total_receber_mes_emprestimos > 0){
    $porcentagem_receber = ($total_recebido / $total_receber_mes_emprestimos) * 100;
}else{
    $porcentagem_receber = 0;
}



//total de contas a pagar mês
$query = $pdo->query("SELECT * from pagar where data_venc >= '$data_mes' and data_venc <= '$data_final_mes' and referencia = 'Conta' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_pendentes_mes = @count($res);


//total de contas pagas mês
$query = $pdo->query("SELECT * from pagar where data_venc >= '$data_mes' and data_venc <= '$data_final_mes' and referencia = 'Conta' and pago = 'Sim' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_pagas_mes = @count($res);

if($contas_pendentes_mes > 0 and $contas_pagas_mes > 0){
    $porcentagem_pagar = ($contas_pagas_mes / $contas_pendentes_mes) * 100;
}else{
    $porcentagem_pagar = 0;
}


//total de clientes débitos
$total_clientes_debitos = 0;
$query = $pdo->query("SELECT * from clientes ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
for($i=0; $i<$linhas; $i++){
	$id_cliente = $res[$i]['id'];

	$query2 = $pdo->query("SELECT * from receber where data_venc < curDate() and pago != 'Sim' and cliente = '$id_cliente' ");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$total_clientes_deb = @count($res2);
	if($total_clientes_deb > 0){
		$total_clientes_debitos += 1;
	}
}

if($total_clientes_debitos > 0 and $total_clientes > 0){
    $porcentagem_clientes = ($total_clientes_debitos / $total_clientes) * 100;
    
}else{
    $porcentagem_clientes = 0;
}




//dados para o gráfico de linhas
$meses = 6;
$data_inicio_apuracao = date('Y-m-d', strtotime("-$meses months",strtotime($data_mes)));
$datas_apuracao = '';
$nome_mes = '';
$datas_apuracao_final = '';

$datas_apuracao_final = '';
$total_emprestimos_mes_grafico = '';
for($cont=0; $cont<$meses; $cont++){

$datas_apuracao = date('Y-m-d', strtotime("+$cont months",strtotime($data_inicio_apuracao)));

	$mes = date('m', strtotime($datas_apuracao));
	$ano = date('Y', strtotime($datas_apuracao));

	if($mes == '01'){
		$nome_mes = 'Janeiro';
	}

	if($mes == '02'){
		$nome_mes = 'Fevereiro';
	}

	if($mes == '03'){
		$nome_mes = 'Março';
	}

	if($mes == '04'){
		$nome_mes = 'Abril';
	}

	if($mes == '05'){
		$nome_mes = 'Maio';
	}

	if($mes == '06'){
		$nome_mes = 'Junho';
	}

	if($mes == '07'){
		$nome_mes = 'Julho';
	}

	if($mes == '08'){
		$nome_mes = 'Agosto';
	}

	if($mes == '09'){
		$nome_mes = 'Setembro';
	}

	if($mes == '10'){
		$nome_mes = 'Outubro';
	}

	if($mes == '11'){
		$nome_mes = 'Novembro';
	}

	if($mes == '12'){
		$nome_mes = 'Dezembro';
	}

	if($mes == '04' || $mes == '06' || $mes == '09' || $mes == '11'){
		$data_final_mes1 = '30';
	}else if($mes == '2'){
		$data_final_mes1 = '28';
	}else{
		$data_final_mes1 = '31';
	}
	
	$data_final_mes_completa = $ano.'-'.$mes.'-'.$data_final_mes1;	
	//percorrer os meses totalizando os valores
	$query = $pdo->query("SELECT * from emprestimos where data >= '$datas_apuracao' and data <= '$data_final_mes_completa' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_emprestimos_mes = @count($res);

$total_emprestimos_mes_grafico .= $total_emprestimos_mes.'*';
$datas_apuracao_final .= $nome_mes.'*';
}






//novos cards

$total_capital_emprestado = 0;
//total de capital emprestado mês
$query = $pdo->query("SELECT * from emprestimos ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_cap = @count($res);
for($i=0; $i<$total_cap; $i++){
	$valor = $res[$i]['valor'];
	$status = $res[$i]['status'];
	if($status != 'Finalizado'){
		$total_capital_emprestado += $valor;
	}
	
}
$total_capital_emprestadoF = number_format($total_capital_emprestado, 2, ',', '.');


$total_emprestado_mes = 0;
//total de capital emprestado mês
$query = $pdo->query("SELECT * from emprestimos where data >= '$data_mes' and data <= '$data_final_mes'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_cap = @count($res);

for($i=0; $i<$total_cap; $i++){
	$valor = $res[$i]['valor'];
	$status = $res[$i]['status'];
	if($status != 'Finalizado'){
		$total_emprestado_mes += $valor;
	}
	
}
$total_emprestado_mesF = number_format($total_emprestado_mes, 2, ',', '.');



//total de capital emprestado mês
$query = $pdo->query("SELECT * from emprestimos where status = 'Finalizado'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$emprestimos_finalizados = @count($res);

 ?>



<div class="main-page margin-mobile">

	<?php if($ativo_sistema == ''){ ?>
<div style="background: #ffc341; color:#3e3e3e; padding:10px; font-size:14px; margin-bottom:10px">
<div><i class="fa fa-info-circle"></i> <b>Aviso: </b> Prezado Cliente, não identificamos o pagamento de sua última mensalidade, entre em contato conosco o mais rápido possivel para regularizar o pagamento, caso contário seu acesso ao sistema será desativado.</div>
</div>
<?php } ?>

	<div class="col_3">

		<?php if($recursos != "Cobranças"){ ?>
		<a href="index.php?pagina=emprestimos">
		<div class="col-md-3 widget widget1">
			<div class="r3_counter_box">
				<i class="pull-left fa fa-dollar icon-rounded"></i>
				<div class="stats">
					<h5><strong><?php echo @$total_emprestimos_mes_inicio ?></strong></h5>
					<span><small>Empréstimos Mês</small></span>
				</div>
			</div>
		</div>
		</a>
		<?php }else{ ?>

			<a href="index.php?pagina=cobrancas">
		<div class="col-md-3 widget widget1">
			<div class="r3_counter_box">
				<i class="pull-left fa fa-dollar icon-rounded"></i>
				<div class="stats">
					<h5><strong><?php echo @$total_cobrancas_mes_inicio ?></strong></h5>
					<span><small>Cobranças Mês</small></span>
				</div>
			</div>
		</div>
		</a>
		<?php } ?>



		<a href="index.php?pagina=receber_vencidas">
		<div class="col-md-3 widget widget1">
			<div class="r3_counter_box">
				<i class="pull-left fa fa-money user1 icon-rounded"></i>
				<div class="stats">					
					<h5><strong><span class="sem_valor" ></span> <span class="ocultar_valor">R$ <?php echo @$total_emprestimos_vencidosF ?></span></strong></h5>
					<span><small>Total Vencido</small></span>
				</div>
			</div>
		</div>
		</a>
		<a href="index.php?pagina=receber">
		<div class="col-md-3 widget widget1">
			<div class="r3_counter_box">
				<i class="pull-left fa fa-money dollar2 icon-rounded"></i>
				<div class="stats">
					<h5><strong>
						<span class="sem_valor" ></span> <span class="ocultar_valor">
						<?php if($total_receber_mes < 1000){ ?>R$ <?php } ?><?php echo @$total_receber_mesF ?></strong>
					</span>
					</h5>
					<span><small>R$ à Receber Mês</small></span>
				</div>
			</div>
		</div>
		</a>
		<a href="index.php?pagina=receber_vencidas">
		<div class="col-md-3 widget widget1">
			<div class="r3_counter_box">
				<i class="pull-left fa fa-calendar-o dollar1 icon-rounded"></i>
				<div class="stats">
					<h5><strong><?php echo $total_parcelas_debitos ?></strong></h5>
					<span><small>Parcelas Débito</small></span>
				</div>
			</div>
		</div>
		</a>
		<a href="index.php?pagina=clientes">
		<div class="col-md-3 widget">
			<div class="r3_counter_box">
				<i class="pull-left fa fa-users dollar2 icon-rounded"></i>
				<div class="stats">
					<h5><strong><?php echo $total_clientes ?></strong></h5>
					<span><small>Total Clientes</small></span>
				</div>
			</div>
		</div>
	</a>
		<div class="clearfix"> </div>
	</div>



		<?php if($recursos != "Cobranças"){ ?>
		<div class="col_3">
		<a href="index.php?pagina=emprestimos">
		<div class="col-md-3 widget widget1">
			<div class="r3_counter_box">
				<i class="pull-left fa fa-dollar icon-rounded"></i>
				<div class="stats">
					<h5><strong><span class="sem_valor" ></span> <span class="ocultar_valor">R$ <?php echo @$total_capital_emprestadoF ?></span></strong></h5>
					<span><small>Capital Emprestado</small></span>
				</div>
			</div>
		</div>
		</a>
		<a href="index.php?pagina=receber_vencidas">
		<div class="col-md-3 widget widget1">
			<div class="r3_counter_box">
				<i class="pull-left fa fa-money user1 icon-rounded"></i>
				<div class="stats">
					<h5><strong><span class="sem_valor" ></span> <span class="ocultar_valor">R$ <?php echo @$total_emprestado_mesF ?></span></strong></h5>
					<span><small>Emprestado Mês</small></span>
				</div>
			</div>
		</div>
		</a>
		<a href="index.php?pagina=receber">
		<div class="col-md-3 widget widget1">
			<div class="r3_counter_box">
				<i class="pull-left fa fa-money dollar2 icon-rounded"></i>
				<div class="stats">
					<h5><strong><span class="sem_valor" ></span> <span class="ocultar_valor">R$ <?php echo @$total_recebidoF ?></span></strong></h5>
					<span><small>Recebidos Mês</small></span>
				</div>
			</div>
		</div>
		</a>
		<a href="index.php?pagina=receber_vencidas">
		<div class="col-md-3 widget widget1">
			<div class="r3_counter_box">
				<i class="pull-left fa fa-calendar-o dollar1 icon-rounded"></i>
				<div class="stats">
					<h5><strong><?php echo @$emprestimos_finalizados ?></strong></h5>
					<span><small>Emp. Finalizados</small></span>
				</div>
			</div>
		</div>
		</a>
		<a href="index.php?pagina=clientes">
		<div class="col-md-3 widget">
			<div class="r3_counter_box">
				<i class="pull-left fa fa-users user1 icon-rounded"></i>
				<div class="stats">
					<h5><strong><?php echo $total_clientes_debitos ?></strong></h5>
					<span><small>Clientes Débito</small></span>
				</div>
			</div>
		</div>
	</a>
		<div class="clearfix"> </div>
	</div>
<?php } ?>

	
	<div class="row-one widgettable">
		<div class="col-md-8 content-top-2 card">
			<div class="agileinfo-cdr">
				<div class="card-header">
					<h3>Recebimentos Últimos Meses</h3>
				</div>
				
				<div id="Linegraph" style="width: 98%; height: 350px">
				</div>
				
			</div>
		</div>
		<div class="col-md-4 stat">
			<div class="content-top-1">
				<div class="col-md-6 top-content">
					<h5>Recebidos Mês</h5>
					<label style="font-size:14px"><span class="sem_valor" ></span> <span class="ocultar_valor"><?php echo $total_receber_mes_emprestimosF ?> / <?php echo $total_recebidoF ?></span></label>
				</div>
				<div class="col-md-6 top-content1">	   
					<div id="demo-pie-1" class="pie-title-center" data-percent="<?php echo $porcentagem_receber ?>"> <span class="pie-value"></span> </div>
				</div>
				<div class="clearfix"> </div>
			</div>
			<div class="content-top-1">
				<div class="col-md-6 top-content">
					<h5>Despesas Mês</h5>
					<label><?php echo $contas_pendentes_mes ?> / <?php echo $contas_pagas_mes ?></label>
				</div>
				<div class="col-md-6 top-content1">	   
					<div id="demo-pie-2" class="pie-title-center" data-percent="<?php echo $porcentagem_pagar ?>"> <span class="pie-value"></span> </div>
				</div>
				<div class="clearfix"> </div>
			</div>
			<div class="content-top-1">
				<div class="col-md-6 top-content">
					<h5>Clientes / Débitos</h5>
					<label><?php echo $total_clientes ?> / <?php echo @$total_clientes_debitos ?></label>
				</div>
				<div class="col-md-6 top-content1">	   
					<div id="demo-pie-3" class="pie-title-center" data-percent="<?php echo @$porcentagem_clientes ?>"> <span class="pie-value"></span> </div>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
		


		<div class="clearfix"> </div>
	</div>
	
	
	

	
</div>


<input type="hidden" id="mostrar_v">


<script type="text/javascript">
	$(document).ready(function() {    
    $('.ocultar_valor').addClass('ocultar');
    $('.sem_valor').text('R$ ...');
    
    
} );
</script>

<!-- for index page weekly sales java script -->
<script src="js/SimpleChart.js"></script>
<script>

	var meses = "<?=$datas_apuracao_final?>";
	var dados = meses.split("*"); 

	var totais = "<?=$total_emprestimos_mes_grafico?>";
	var dados_totais = totais.split("*"); 


		var maior_valor_linha = Math.max(...dados_totais);
    	maior_valor = parseFloat(maior_valor_linha) + 3;

    	var menor_valor = Math.min(...dados_totais);
    	

	var dados_grafico = {
		linecolor: "#065c1f",
		title: "Monday",
		values: [
		{ X: dados[0], Y: dados_totais[0] },
		{ X: dados[1], Y: dados_totais[1] },
		{ X: dados[2], Y: dados_totais[2] },
		{ X: dados[3], Y: dados_totais[3] },
		{ X: dados[4], Y: dados_totais[4] },
		{ X: dados[5], Y: dados_totais[5] }
		
		]
	};
	


	var range = {
    		linecolor: "transparent",
    		title: "",
    		values: [
    		{ X: dados[0], Y: menor_valor },
    		{ X: dados[1], Y: menor_valor },
    		{ X: dados[2], Y: menor_valor },
    		{ X: dados[3], Y: menor_valor },
    		{ X: dados[4], Y: menor_valor },
    		{ X: dados[5], Y: maior_valor },
    		
    		]
    	};
			
		
		$("#Linegraph").SimpleChart({
			ChartType: "Line",
			toolwidth: "50",
			toolheight: "25",
			axiscolor: "#E6E6E6",
			textcolor: "#6E6E6E",
			showlegends: false,
			data: [dados_grafico, range],
			legendsize: "140",
			legendposition: 'bottom',
			xaxislabel: 'Meses',
			title: '',
			yaxislabel: 'Totais'
		});
				

</script>
<!-- //for index page weekly sales java script -->


<script type="text/javascript">
	function mostrar_valores(){
		$('.sem_valor').text('');
		var mostrar = $('#mostrar_v').val();

		$('.sem_valor').removeClass('ocultar');
		$('.ocultar_valor').removeClass('mostrar');

		if(mostrar == ""){			

			$('.sem_valor').addClass('ocultar');
			$('.ocultar_valor').addClass('mostrar');
			$('#mostrar_v').val('mostrando');
		}else{
			
			$('.sem_valor').text('R$ ...');
			$('.sem_valor').addClass('mostrar');
			$('.ocultar_valor').addClass('ocultar');
			$('#mostrar_v').val('');
			
		}
		
		
	}
</script>