<?php 
include('../../conexao.php');

include('data_formatada.php');


?>
<!DOCTYPE html>
<html>
<head>

<style>

@import url('https://fonts.cdnfonts.com/css/tw-cen-mt-condensed');
@page { margin: 145px 20px 25px 20px; }
#header { position: fixed; left: 0px; top: -110px; bottom: 100px; right: 0px; height: 35px; text-align: center; padding-bottom: 100px; }
#content {margin-top: 0px;}
#footer { position: fixed; left: 0px; bottom: -60px; right: 0px; height: 80px; }
#footer .page:after {content: counter(page, my-sec-counter);}
body {font-family: 'Tw Cen MT', sans-serif;}

.marca{
	position:fixed;
	left:50;
	top:100;
	width:80%;
	opacity:8%;
}

</style>

</head>
<body>
<?php 
if($marca_dagua == 'Sim'){ ?>
<img class="marca" src="<?php echo $url_sistema ?>img/logo.jpg">	
<?php } ?>


<div id="header" >

	<div style="border-style: solid; font-size: 10px; height: 50px;">
		<table style="width: 100%; border: 0px solid #ccc;">
			<tr>
				<td style="border: 1px; solid #000; width: 7%; text-align: left;">
					<img style="margin-top: 7px; margin-left: 7px;" id="imag" src="<?php echo $url_sistema ?>img/logo.jpg" width="160px">
				</td>
				<td style="width: 30%; text-align: left; font-size: 13px;">
					
				</td>
				<td style="width: 1%; text-align: center; font-size: 13px;">
				
				</td>
				<td style="width: 47%; text-align: right; font-size: 9px;padding-right: 10px;">
						<b><big>RELATÓRIO DE DÉBITOS </span></big></b><br>  <br> <?php echo mb_strtoupper($data_hoje) ?>
				</td>
			</tr>		
		</table>
	</div>

<br>


		<table id="cabecalhotabela" style="border-bottom-style: solid; font-size: 11px; margin-bottom:10px; width: 100%; table-layout: fixed;">
			<thead>
				
				<tr id="cabeca" style="margin-left: 0px; background-color:#CCC">
					<td style="width:50%">CLIENTE</td>
					<td style="width:25%">TELEFONE</td>
					<td style="width:25%">TOTAL DÉBITO</td>
						
					
				</tr>
			</thead>
		</table>
</div>

<div id="footer" class="row">
<hr style="margin-bottom: 0;">
	<table style="width:100%;">
		<tr style="width:100%;">
			<td style="width:60%; font-size: 10px; text-align: left;"><?php echo $nome_sistema ?> Telefone: <?php echo $telefone_sistema ?></td>
			<td style="width:40%; font-size: 10px; text-align: right;"><p class="page">Página  </p></td>
		</tr>
	</table>
</div>

<div id="content" style="margin-top: 0;">



		<table style="width: 100%; table-layout: fixed; font-size:10px; text-transform: uppercase;">
			<thead>
				<tbody>
					<?php

$total_valor = 0;
$total_debitos = 0;
$total_debitosF = 0;
$query = $pdo->query("SELECT * from clientes");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
for($i=0; $i<$linhas; $i++){
	$id = $res[$i]['id'];
	$nome = $res[$i]['nome'];
	$telefone = $res[$i]['telefone'];

	$total_valor = 0;

	$query2 = $pdo->query("SELECT * from receber where cliente = '$id' and pago != 'Sim' and data_venc < curDate()");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$linhas2 = @count($res2);
	if($linhas2 > 0){
		for($i2=0; $i2<$linhas2; $i2++){
			$descricao = $res2[$i2]['descricao'];
			$data_venc = $res2[$i2]['data_venc'];
			$valor_receber = $res2[$i2]['valor'];
			$referencia = $res2[$i2]['referencia'];
			$parcela = $res2[$i2]['parcela'];
		

			$valor_receberF = number_format($valor_receber, 2, ',', '.');
			$data_vencF = implode('/', array_reverse(explode('-', $data_venc)));

			$total_valor += $valor_receber;

		}

		$total_debitos += $total_valor;

	}else{
		continue;
	}

	$total_valorF = number_format($total_valor, 2, ',', '.');
	$total_debitosF = number_format($total_debitos, 2, ',', '.');


  	 ?>

  	 
      <tr>
<td style="width:50%"><b><?php echo $nome ?><b></td>
<td style="width:25%"><?php echo $telefone ?></td>
<td style="width:25%; color:red">R$ <?php echo $total_valorF ?></td>

    </tr>




<?php

$query2 = $pdo->query("SELECT * from receber where cliente = '$id' and pago != 'Sim' and data_venc < curDate()");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$linhas2 = @count($res2);
	if($linhas2 > 0){
		for($i2=0; $i2<$linhas2; $i2++){
			$descricao = $res2[$i2]['descricao'];
			$data_venc = $res2[$i2]['data_venc'];
			$valor_receber = $res2[$i2]['valor'];
			$referencia = $res2[$i2]['referencia'];
			$parcela = $res2[$i2]['parcela'];

			if($referencia == ""){
				$referenciaF = $descricao;
			}else{
				$referenciaF = $referencia. " Parcela ".$parcela;
			}

			

			$valor_receberF = number_format($valor_receber, 2, ',', '.');
			$data_vencF = implode('/', array_reverse(explode('-', $data_venc)));

			$total_valor += $valor_receber;

			?>

			<tr style="font-size: 9px;">
				<td style="width:50%"><?php echo $referenciaF ?></td>
				<td style="width:25%">Venceu: <?php echo $data_vencF ?></td>
				<td style="width:25%">R$ <?php echo $valor_receberF ?></td>
			</tr>


			<?php 
		}

		echo '<tr>
		<td><hr style="width:100%"></td>
		<td><hr style="width:100%"></td>
		<td><hr style="width:100%"></td>
		</tr>';

	}else{
		continue;
	}

 } } ?>
				</tbody>
	
			</thead>
		</table>
	


</div>
<hr>


<table>
			<thead>
				<tbody>
					<tr>

						<td style="font-size: 10px; width:300px; text-align: right;"></td>				

						

							<td style="font-size: 10px; width:70px; text-align: right;"></td>


								<td style="font-size: 10px; width:70; text-align: right;"></td>

									<td style="font-size: 10px; width:120px; text-align: right;"></td>

									<td style="font-size: 10px; width:140px; text-align: right;"><b>Total Débitos: <span style="color:red">R$ <?php echo $total_debitosF ?></span></td>
						
					</tr>
				</tbody>
			</thead>
		</table>
	

</body>

</html>


