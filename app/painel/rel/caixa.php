<?php 
include('../../conexao.php');

include('data_formatada.php');

//pegar o saldo atual
$total_recebido = 0;
$total_pago = 0;
$query = $pdo->query("SELECT * from receber where pago = 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
for($i=0; $i<$linhas; $i++){
		$valor_rec = $res[$i]['valor'];
		$total_recebido += $valor_rec;
	}
}


$query = $pdo->query("SELECT * from pagar where pago = 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
for($i=0; $i<$linhas; $i++){
		$valor_pag = $res[$i]['valor'];
		$total_pago += $valor_pag;
	}
}

$saldo_do_caixa = $saldo_inicial + $total_recebido - $total_pago;
$saldo_do_caixaF = number_format($saldo_do_caixa, 2, ',', '.');

if($saldo_do_caixa >= 0){
	$classe_saldo_final = 'green';
}else{
	$classe_saldo_final = 'red';
}




//pegar o saldo do inicio do dia
$total_recebido = 0;
$total_pago = 0;
$query = $pdo->query("SELECT * from receber where pago = 'Sim' and data_pgto != curDate()");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
for($i=0; $i<$linhas; $i++){
		$valor_rec = $res[$i]['valor'];
		$total_recebido += $valor_rec;
	}
}


$query = $pdo->query("SELECT * from pagar where pago = 'Sim' and data_pgto != curDate()");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
for($i=0; $i<$linhas; $i++){
		$valor_pag = $res[$i]['valor'];
		$total_pago += $valor_pag;
	}
}


$saldo_do_caixa_inicio = $saldo_inicial + $total_recebido - $total_pago;
$saldo_do_caixa_inicioF = number_format($saldo_do_caixa_inicio, 2, ',', '.');

if($saldo_do_caixa_inicio >= 0){
	$classe_saldo_inicio = 'green';
}else{
	$classe_saldo_inicio = 'red';
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
						<b><big>RELATÓRIO DE CAIXA</span></big></b><br> </span> <br> <?php echo mb_strtoupper($data_hoje) ?>
				</td>
			</tr>		
		</table>
	</div>

<br>


		<table id="cabecalhotabela" style="border-bottom-style: solid; font-size: 8px; margin-bottom:10px; width: 100%; table-layout: fixed;">
			<thead>
				
				<tr id="cabeca" style="margin-left: 0px; background-color:#CCC">
					<td style="width:25%">DESCRIÇÃO</td>
					<td style="width:10%">VALOR</td>
					<td style="width:10%">ENTRADA</td>
					<td style="width:10%">VENCIMENTO</td>
					<td style="width:10%">PAGAMENTO</td>
					<td style="width:20%">MOVIMENTADO POR</td>	
					<td style="width:15%">REFERÊNCIA</td>		
					
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



		<table style="width: 100%; table-layout: fixed; font-size:8px; text-transform: uppercase;">
			<thead>
				<tbody>

					     <tr style="font-size: 9px">
<td style="width:25%"><b>SALDO INICIAL HOJE</b></td>
<td style="width:10%; color:<?php echo $classe_saldo_inicio ?>">R$ <?php echo $saldo_do_caixa_inicioF ?></td>
<td style="width:10%"></td>
<td style="width:10%"></td>
<td style="width:10%"></td>
<td style="width:20%"></td>
<td style="width:15%"></td>

    </tr>

    <tr >
    	<td><hr></td>
    	<td><hr></td>
    </tr>
    
					<?php

$total_valor = 0;
$total_valorF = 0;
$total_pendentes = 0;
$total_pendentesF = 0;
$total_pagas = 0;
$total_pagasF = 0;
$pendentes = 0;
$pagas = 0;

$query = $pdo->query("SELECT * from receber WHERE data_pgto = curDate()");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
for($i=0; $i<$linhas; $i++){
	$id = $res[$i]['id'];
$descricao = $res[$i]['descricao'];
$valor = $res[$i]['valor'];
$data_lanc = $res[$i]['data'];
$data_venc = $res[$i]['data_venc'];
$data_pgto = $res[$i]['data_pgto'];
$usuario_lanc = $res[$i]['usuario_lanc'];
$usuario_pgto = $res[$i]['usuario_pgto'];
$pago = $res[$i]['pago'];
$obs = $res[$i]['obs'];
$referencia = $res[$i]['referencia'];
$forma_pgto = $res[$i]['forma_pgto'];	

	$data_lancF = implode('/', array_reverse(@explode('-', $data_lanc)));
$data_vencF = implode('/', array_reverse(@explode('-', $data_venc)));
$data_pgtoF = implode('/', array_reverse(@explode('-', $data_pgto)));
$valorF = number_format($valor, 2, ',', '.');


$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario_pgto'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if(@count($res2) > 0){
	$nome_usu_pgto = $res2[0]['nome'];
}else{
	$nome_usu_pgto = '';
}


if($pago == 'Sim'){
	$classe_pago = 'verde.jpg';	
	$total_pagas += $valor;
	$pagas += 1;
}else{
	$classe_pago = 'vermelho.jpg';	
	$total_pendentes += $valor;
	$pendentes += 1;
}


$total_pagasF = @number_format($total_pagas, 2, ',', '.');
$total_pendentesF = @number_format($total_pendentes, 2, ',', '.');


if($data_pgtoF == '00/00/0000'){
	$data_pgtoF = 'Pendente';
}
  	 ?>

  	 
      <tr>
<td style="width:25%">
<img style="margin-top: 0px" src="<?php echo $url_sistema ?>painel/images/verde.jpg" width="8px">
	<?php echo $descricao ?></td>
<td style="width:10%; color:green">R$ <?php echo $valorF ?></td>
<td style="width:10%"><?php echo $forma_pgto ?></td>
<td style="width:10%"><?php echo $data_vencF ?></td>
<td style="width:10%"><?php echo $data_pgtoF ?></td>
<td style="width:20%"><?php echo $nome_usu_pgto ?></td>
<td style="width:15%"><?php echo $referencia ?></td>

    </tr>

<?php } } ?>




<?php

$total_valor = 0;
$total_valorF = 0;
$total_pendentes = 0;
$total_pendentesF = 0;
$total_pagas = 0;
$total_pagasF = 0;
$pendentes = 0;
$pagas = 0;

$query = $pdo->query("SELECT * from pagar WHERE data_pgto = curDate()");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
for($i=0; $i<$linhas; $i++){
	$id = $res[$i]['id'];
$descricao = $res[$i]['descricao'];
$valor = $res[$i]['valor'];
$data_lanc = $res[$i]['data'];
$data_venc = $res[$i]['data_venc'];
$data_pgto = $res[$i]['data_pgto'];
$usuario_lanc = $res[$i]['usuario_lanc'];
$usuario_pgto = $res[$i]['usuario_pgto'];
$pago = $res[$i]['pago'];
$obs = $res[$i]['obs'];
$referencia = $res[$i]['referencia'];
	

	$data_lancF = implode('/', array_reverse(@explode('-', $data_lanc)));
$data_vencF = implode('/', array_reverse(@explode('-', $data_venc)));
$data_pgtoF = implode('/', array_reverse(@explode('-', $data_pgto)));
$valorF = number_format($valor, 2, ',', '.');


$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario_pgto'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if(@count($res2) > 0){
	$nome_usu_pgto = $res2[0]['nome'];
}else{
	$nome_usu_pgto = '';
}


if($pago == 'Sim'){
	$classe_pago = 'verde.jpg';	
	$total_pagas += $valor;
	$pagas += 1;
}else{
	$classe_pago = 'vermelho.jpg';	
	$total_pendentes += $valor;
	$pendentes += 1;
}


$total_pagasF = @number_format($total_pagas, 2, ',', '.');
$total_pendentesF = @number_format($total_pendentes, 2, ',', '.');


if($data_pgtoF == '00/00/0000'){
	$data_pgtoF = 'Pendente';
}
  	 ?>

  	 
      <tr>
<td style="width:25%">
<img style="margin-top: 0px" src="<?php echo $url_sistema ?>painel/images/vermelho.jpg" width="8px">
	<?php echo $descricao ?></td>
<td style="width:10%; color:red">R$ <?php echo $valorF ?></td>
<td style="width:10%"></td>
<td style="width:10%"><?php echo $data_vencF ?></td>
<td style="width:10%"><?php echo $data_pgtoF ?></td>
<td style="width:20%"><?php echo $nome_usu_pgto ?></td>
<td style="width:15%"><?php echo $referencia ?></td>

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

						<td style="font-size: 10px; width:300px; text-align: right;"></td>

						

						<td style="font-size: 10px; width:70px; text-align: right;"></td>

							<td style="font-size: 10px; width:70px; text-align: right;"></td>


								<td style="font-size: 10px; width:50px; text-align: right;"></td>

									<td style="font-size: 12px; width:210px; text-align: right;"><b>SALDO ATUAL: <span style="color:<?php echo $classe_saldo_final ?>">R$ <?php echo $saldo_do_caixaF ?></span></td>
						
					</tr>
				</tbody>
			</thead>
		</table>

</body>

</html>


