<?php 
include('../../conexao.php');

include('data_formatada.php');


$cliente = $_GET['cliente'];
$status = $_GET['status'];


if($status == ""){
	$sql_status = ' status is null';
}

if($status == "Ativos"){
	$sql_status = ' status is null';
}

if($status == "Finalizado"){
	$sql_status = " status = 'Finalizado'";
}

if($status == "Perdido"){
	$sql_status = " status = 'Perdido'";
}

$nome_cliente = '';
if($cliente != ""){
	$query2 = $pdo->query("SELECT * from clientes where id = '$cliente'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$nome_cliente = 'Cliente: '.@$res2[0]['nome'];
}


$capital_emprestadoF = 0;

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
						<b><big>RELATÓRIO DE EMPRÉSTIMOS <?php echo mb_strtoupper($status) ?> </big></b><br>
						<?php echo mb_strtoupper($nome_cliente) ?>
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
					<td style="width:20%">CLIENTE</td>
					<td style="width:10%">R$ VALOR</td>
					<td style="width:10%">PROJEÇÃO LUCRO</td>
					<td style="width:10%">PARCELAS</td>
					<td style="width:10%">DATA</td>
					<td style="width:17%">JÚROS</td>
					<td style="width:10%">JÚROS RECEBIDOS</td>
					<td style="width:13%">FREQUÊNCIA PGTO</td>
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

		<table style="width: 100%; table-layout: fixed; font-size:8px; text-transform: uppercase;">
			<thead>
				<tbody>
					<?php 
$total_soma_juros = 0;		
$total_soma_juros_proj = 0;	
$capital_emprestado = 0;				
$ativos = 0;
$inativos = 0;
if($cliente == ""){
	$query = $pdo->query("SELECT * from emprestimos where $sql_status order by id desc");
}else{
	$query = $pdo->query("SELECT * from emprestimos where $sql_status and cliente = '$cliente'  order by id desc");
}
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
$status = $res[$i]['status'];

$classe_finalizado = '';
if($status == 'Finalizado'){	
	$classe_finalizado = '<small><span style="color:blue">(Finalizado)</span></small>';
}

if($status == 'Perdido'){	
	$classe_finalizado = '<small><span style="color:red">(Perdido)</span></small>';
}


$data_vencF = date('d', @strtotime($data_venc));
$dataF = implode('/', array_reverse(@explode('-', $data)));
$valorF = @number_format($valor, 2, ',', '.');
$jurosF = @number_format($juros, 2, ',', '.');
$multaF = @number_format($multa, 2, ',', '.');

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


$total_juros = 0;
//verificar parcelas pagas
$query2 = $pdo->query("SELECT * from receber where referencia = 'Empréstimo' and id_ref = '$id' and pago = 'Sim'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$parcelas_pagas = @count($res2);

//percorrer as parcelas do empréstimo
if($parcelas_pagas > 0){
	for($i2=0; $i2<$parcelas_pagas; $i2++){
		$valor_p1 = @$res2[$i2]['valor'];
		$parcela_sem_juros1 = @$res2[$i2]['parcela_sem_juros'];
		$projecao1 = ($valor_p1 - $parcela_sem_juros1);
		$total_juros += $projecao1;
	}
}
$total_jurosF = number_format($total_juros, 2, ',', '.');

//projecao lucro emprestimo
$query2 = $pdo->query("SELECT * FROM receber where referencia = 'Empréstimo' and id_ref = '$id'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$num_parcelas = @$res2[0]['parcela'];
$valor_p = @$res2[0]['valor'];
$parcela_sem_juros = @$res2[0]['parcela_sem_juros'];
$projecao = ($valor_p - $parcela_sem_juros) * $parcelas;
$projecaoF = number_format($projecao, 2, ',', '.');
$texto_projecao = '<span> R$ '.$projecaoF.'</span>';

if($tipo_juros == 'Somente Júros'){
	$texto_projecao = '';
}


$total_soma_juros += $total_juros;
$total_soma_jurosF = number_format($total_soma_juros, 2, ',', '.');

$total_soma_juros_proj += $projecao;
$total_soma_juros_projF = number_format($total_soma_juros_proj, 2, ',', '.');

$capital_emprestado += $valor;
$capital_emprestadoF = number_format($capital_emprestado, 2, ',', '.');
  	 ?>

  	 
      <tr>
<td style="width:20%;" class="<?php echo $classe_debito ?>"><?php echo $nome_cliente ?> <?php echo $classe_finalizado ?></td>
<td style="width:10%">R$ <?php echo $valorF ?> </td>
<td style="width:10%"><?php echo $texto_projecao ?></td>
<td style="width:10%"><?php echo $parcelas_pagas ?> / <?php echo $parcelas ?></td>
<td style="width:10%"><?php echo $dataF ?></td>
<td style="width:17%;"><?php echo $juros_emp ?>% <small><span class="text-primary">(<?php echo $tipo_juros ?>)</span></small></td>
<td style="width:10%; color:green">R$ <?php echo $total_jurosF ?></td>
<td style="width:13%;"><?php echo $frequencia ?></td>

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
										
						

							<td style="font-size: 9px; width:180px; text-align: right;">TOTAL EMPRÉSTIMOS: <?php echo $linhas ?></td>

							<td style="font-size: 9px; width:180px; text-align: right;">CAPITAL EMPRESTADO: <span style="color:red">R$ <?php echo $capital_emprestadoF ?></span></td>

							<td style="font-size: 9px; width:180px; text-align: right;">TOTAL PROJEÇÃO JÚROS: <span style="color:blue"> R$ <?php echo @$total_soma_juros_projF ?></span></td>

							<td style="font-size: 9px; width:180px; text-align: right;">TOTAL JÚROS RECEBIDOS: <span style="color:green">R$ <?php echo @$total_soma_jurosF ?></span></td>
						
					</tr>
				</tbody>
			</thead>
		</table>

</body>

</html>


