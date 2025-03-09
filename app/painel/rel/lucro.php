<?php 



include('../../conexao.php');


include('data_formatada.php');


$dataFinal = $_GET['dataFinal'];
$dataInicial = $_GET['dataInicial'];
$id_usu = $_GET['id_usu'];
$nivel_usu = $_GET['nivel_usu'];
$cliente = $_GET['cliente'];
$corretor = $_GET['corretor'];



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
				<td style="width: 20%; text-align: left; font-size: 13px;">
				
				</td>
				<td style="width: 5%; text-align: center; font-size: 13px;">
				
				</td>
				<td style="width: 55%; text-align: right; font-size: 9px;padding-right: 10px;">
						<b><big>RELATÓRIO DE EMPRÉSTIMOS </big></b><br>
						<?php echo mb_strtoupper($datas) ?>
						<br>
						 <?php echo mb_strtoupper($data_hoje) ?>
				</td>
			</tr>		
		</table>
	</div>

<br>


		<table id="cabecalhotabela" style="border-bottom-style: solid; font-size: 9px; margin-bottom:10px; width: 100%; table-layout: fixed;">
			<thead>
				
				<tr id="cabeca" style="margin-left: 0px; background-color:#CCC">
					<td style="width:25%">CLIENTE</td>
					<td style="width:10%">EMPRESTADO</td>
					<td style="width:10%">PARCELAS</td>
					<td style="width:10%">DATA</td>
					<td style="width:17%">JÚROS %</td>					
					<td style="width:13%">FREQUÊNCIA PGTO</td>
					<td style="width:9%">TOTAL PAGO</td>
					<td style="width:8%">LUCRO</td>
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

		<table style="width: 100%; table-layout: fixed; font-size:9px; text-transform: uppercase;">
			<thead>
				<tbody>
					<?php 
$valor_emprestado = 0;
@$valor_do_lucro_total = 0;  


if($corretor == ""){
	$sql_corretor = '';
}else{
	$sql_corretor = " and usuario = '$corretor' ";
}

if($cliente == ""){
	$sql_cliente = '';
}else{
	$sql_cliente = " and cliente = '$cliente' ";
}


$query = $pdo->query("SELECT * from emprestimos where data >= '$dataInicial' and data <= '$dataFinal' $sql_corretor $sql_cliente  order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);		


if($linhas > 0){
for($i=0; $i<$linhas; $i++){
$id = $res[$i]['id'];
$valor = $res[$i]['valor'];
$parcelas = $res[$i]['parcelas'];
$juros_emp = $res[$i]['juros_emp'];
$data_venc = $res[$i]['data_venc'];
$data = $res[$i]['data'];
$cliente = $res[$i]['cliente'];
$juros = $res[$i]['juros'];
$multa = $res[$i]['multa'];
$usuario = $res[$i]['usuario'];
$obs = $res[$i]['obs'];
$frequencia = $res[$i]['frequencia'];
$tipo_juros = $res[$i]['tipo_juros'];


$query2 = $pdo->query("SELECT * from receber where referencia = 'Empréstimo' and id_ref = '$id' ");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$parcelas = @count($res2);


$valor_emprestado += $valor ;

$data_vencF = date('d', @strtotime($data_venc));
$dataF = implode('/', array_reverse(@explode('-', $data)));
$valorF = @number_format($valor, 2, ',', '.');
$jurosF = @number_format($juros, 2, ',', '.');
$multaF = @number_format($multa, 2, ',', '.');
$valor_emprestadoF = @number_format($valor_emprestado, 2, ',', '.');

$query2 = $pdo->query("SELECT * from clientes where id = '$cliente'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = @$res2[0]['nome'];

$query2 = $pdo->query("SELECT * from usuarios where id = '$usuario'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_usuario = @$res2[0]['nome'];


$classe_debito = '';
//verificar débito
$query2 = $pdo->query("SELECT * from receber where referencia = 'Empréstimo' and id_ref = '$id' and pago = 'Não' and data_venc < curDate()");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if(@count($res2) > 0){
	$classe_debito = 'text-danger';
}


$valor_total_pago = 0;
//verificar parcelas pagas
$query2 = $pdo->query("SELECT * from receber where referencia = 'Empréstimo' and id_ref = '$id' and pago = 'Sim'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$parcelas_pagas = @count($res2);
for($i2=0; $i2<$parcelas_pagas; $i2++){
	$valor_parcela = @$res2[$i2]['valor'];
	$valor_total_pago += $valor_parcela;
}

$lucro_emprestimo = $valor_total_pago - $valor;

$valor_total_pagoF = @number_format($valor_total_pago, 2, ',', '.');
$lucro_emprestimoF = @number_format($lucro_emprestimo, 2, ',', '.');

if($lucro_emprestimo > 0){
	$classe_lucro = 'green';
}else{
	$classe_lucro = 'red';
}

  	 ?>

  	 
      <tr>
<td style="width:25%;" class="<?php echo $classe_debito ?>"><?php echo $nome_cliente ?></td>
<td style="width:10%">R$ <?php echo $valorF ?></td>
<td style="width:10%"><?php echo $parcelas_pagas ?> / <?php echo $parcelas ?></td>
<td style="width:10%"><?php echo $dataF ?></td>
<td style="width:15%;"><?php echo $juros_emp ?>% <small><span class="text-primary">(<?php echo $tipo_juros ?>)</span></small></td>
<td style="width:13%;"><?php echo $frequencia ?></td>
<td style="width:9%; color:green">R$ <?php echo $valor_total_pagoF ?></td>
<td style="width:8%; color:<?php echo $classe_lucro ?>">R$ <?php echo $lucro_emprestimoF ?></td>

    </tr>

<?php 

$valor_do_lucro_total += $lucro_emprestimo;
if($valor_do_lucro_total > 0){
	$classe_lucro_final = 'green';
}else{
	$classe_lucro_final = 'red';
}

}

}


$valor_do_lucro_totalF = @number_format($valor_do_lucro_total, 2, ',', '.');

 ?>
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
					

						<td style="font-size: 10px; width:180px; text-align: right; ">TOTAL LUCRO: <span style="color:<?php echo $classe_lucro_final ?>">R$ <?php echo @$valor_do_lucro_totalF ?></span></td>

						


						
					</tr>
				</tbody>
			</thead>
		</table>

</body>

</html>


