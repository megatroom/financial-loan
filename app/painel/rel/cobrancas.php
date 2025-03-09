<?php 
include('../../conexao.php');

include('data_formatada.php');


$dataFinal = $_GET['dataFinal'];
$dataInicial = $_GET['dataInicial'];
$cliente = $_GET['cliente'];

$nome_cliente = '';
if($cliente != ""){
	$query2 = $pdo->query("SELECT * from clientes where id = '$cliente'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$nome_cliente = 'Cliente: '.@$res2[0]['nome'];
}

$dataInicialF = implode('/', array_reverse(@explode('-', $dataInicial)));
$dataFinalF = implode('/', array_reverse(@explode('-', $dataFinal)));

$datas = "";
if($dataInicial == $dataFinal){
	$datas = 'Período de Apuração: '.$dataInicialF;
}else{
	$datas = 'Período de Apuração: '.$dataInicialF.' à '.$dataFinalF;
}

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
	top:130;
	width:80%;
	opacity:10%;
}

.text-danger{
	color:red;
}

.text-primary{
	color:blue;
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
				<td style="border: 1px; solid #000; width: 20%; text-align: left;">
					<img style="margin-top: 5px; margin-left: 7px;" id="imag" src="<?php echo $url_sistema ?>img/logo.jpg" width="160px">
				</td>
				<td style="width: 10%; text-align: left; font-size: 13px;">
				
				</td>
				<td style="width: 5%; text-align: center; font-size: 13px;">
				
				</td>
				<td style="width: 65%; text-align: right; font-size: 9px;padding-right: 10px;">
						<b><big>COBRANÇAS RECORRENTES <?php echo mb_strtoupper($nome_cliente) ?> </big></b><br>
						<?php echo mb_strtoupper($datas) ?>
						<br>
						 <?php echo mb_strtoupper($data_hoje) ?>
				</td>
			</tr>		
		</table>
	</div>

<br>


		<table id="cabecalhotabela" style="border-bottom-style: solid; font-size: 10px; margin-bottom:10px; width: 100%; table-layout: fixed;">
			<thead>
				
				<tr id="cabeca" style="margin-left: 0px; background-color:#CCC">
					<td style="width:30%">CLIENTE</td>
					<td style="width:10%">R$ VALOR</td>
					<td style="width:10%">PARCELAS</td>
					<td style="width:10%">DATA</td>					
					<td style="width:10%">PROX VENC</td>
					<td style="width:15%">FREQUÊNCIA PGTO</td>
				</tr>
			</thead>
		</table>
</div>

<div id="footer" class="row">
<hr style="margin-bottom: 0;">
	<table style="width:100%;">
		<tr style="width:100%;">
			<td style="width:60%; font-size: 10px; text-align: left;"><?php echo $nome_sistema ?> / Telefone: <?php echo $telefone_sistema ?> / Email: <?php echo $email_sistema ?></td>
			<td style="width:40%; font-size: 10px; text-align: right;"><p class="page">Página  </p></td>
		</tr>
	</table>
</div>

<div id="content" style="margin-top: 0;">

		<table style="width: 100%; table-layout: fixed; font-size:10px; text-transform: uppercase;">
			<thead>
				<tbody>
					<?php 
$ativos = 0;
$inativos = 0;
if($cliente == ""){
	$query = $pdo->query("SELECT * from cobrancas where data >= '$dataInicial' and data <= '$dataFinal' order by id desc");
}else{
	$query = $pdo->query("SELECT * from cobrancas where data >= '$dataInicial' and data <= '$dataFinal' and cliente = '$cliente' order by id desc");
}

$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
for($i=0; $i<$linhas; $i++){
	$id = $res[$i]['id'];
$valor = $res[$i]['valor'];
$parcelas = $res[$i]['parcelas'];
$data_venc = $res[$i]['data_venc'];
$data = $res[$i]['data'];
$cliente = $res[$i]['cliente'];

$multa = $res[$i]['multa'];
$usuario = $res[$i]['usuario'];
$obs = $res[$i]['obs'];
$frequencia = $res[$i]['frequencia'];


$data_vencF = date('d', @strtotime($data_venc));
$dataF = implode('/', array_reverse(@explode('-', $data)));
$valorF = @number_format($valor, 2, ',', '.');

$multaF = @number_format($multa, 2, ',', '.');

$query2 = $pdo->query("SELECT * from clientes where id = '$cliente'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = @$res2[0]['nome'];

$query2 = $pdo->query("SELECT * from usuarios where id = '$usuario'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_usuario = @$res2[0]['nome'];


$classe_debito = '';
//verificar débito
$query2 = $pdo->query("SELECT * from receber where referencia = 'Cobranca' and id_ref = '$id' and pago = 'Não' and data_venc < curDate()");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if(@count($res2) > 0){
	$classe_debito = 'text-danger';
}


//verificar parcelas pagas
$query2 = $pdo->query("SELECT * from receber where referencia = 'Cobranca' and id_ref = '$id' and pago = 'Sim'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$parcelas_pagas = @count($res2);


if($parcelas > 1){
	$parcelas_nome = $parcelas_pagas .' / '. $parcelas;
}else{
	$parcelas_nome = 'Recorrente ';
}


$query2 = $pdo->query("SELECT * FROM receber where referencia = 'Cobrança' and id_ref = '$id' and pago != 'Sim' order by id asc limit 1");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$data_ultimo_venc = @$res2[0]['data_venc'];
$data_ultimo_vencF = implode('/', array_reverse(explode('-', $data_ultimo_venc)));

  	 ?>

  	 
      <tr>
<td style="width:30%;" class="<?php echo $classe_debito ?>"><?php echo $nome_cliente ?></td>
<td style="width:10%">R$ <?php echo $valorF ?></td>
<td style="width:10%"><?php echo $parcelas_nome ?></td>
<td style="width:10%"><?php echo $dataF ?></td>
<td style="width:10%;" class="<?php echo $classe_debito ?>"><?php echo $data_ultimo_vencF ?></td>
<td style="width:15%;"><?php echo $frequencia ?></td>

    </tr>

<?php } } ?>
				</tbody>
	
			</thead>
		</table>
	


</div>
<hr>
		<table>
			<thead>
				<tbody>
					<tr>
						<td style="font-size: 10px; width:180px; text-align: right;"> </td>

						<td style="font-size: 10px; width:180px; text-align: right;"></td>

						<td style="font-size: 10px; width:180px; text-align: right;"></td>

							<td style="font-size: 10px; width:180px; text-align: right;">TOTAL COBRANÇAS: <?php echo $linhas ?></td>
						
					</tr>
				</tbody>
			</thead>
		</table>

</body>

</html>


