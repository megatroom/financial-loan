<?php 

include('../../conexao.php');
require_once("data_formatada.php");

$token_rel = @$_GET['token'];
if($token_rel != 'A5030'){
echo '<script>window.location="../../"</script>';
exit();
}

$id = $_GET['id'];

$data_atual = date('Y-m-d');


$query = $pdo->query("SELECT * from emprestimos where id = '$id'");

$res = $query->fetchAll(PDO::FETCH_ASSOC);

$linhas = @count($res);

if($linhas == 0){

	echo 'Empréstimo não encontrado!';

	exit();

}else{

$valor = $res[0]['valor'];
$parcelas = $res[0]['parcelas'];
$juros_emp = $res[0]['juros_emp'];
$data_venc = $res[0]['data_venc'];
$data = $res[0]['data'];
$cliente = $res[0]['cliente'];
$juros = $res[0]['juros'];
$multa = $res[0]['multa'];
$usuario = $res[0]['usuario'];
$obs = $res[0]['obs'];
$frequencia = $res[0]['frequencia'];
$tipo_juros = $res[0]['tipo_juros'];
$status = $res[0]['status'];
$cliente = $res[0]['cliente'];

$mostrar_baixa = 'ocultar';
if($tipo_juros == 'Somente Júros'){
	$mostrar_baixa = '';
}

$classe_finalizado = '';
if($status == 'Finalizado'){
	$mostrar_baixa = 'ocultar';
	$classe_finalizado = '<small><span style="color:blue">(Finalizado)</span></small>';
}

$data_vencF = date('d', @strtotime($data_venc));
$dataF = implode('/', array_reverse(explode('-', $data)));
$valorF = @number_format($valor, 2, ',', '.');
$jurosF = @number_format($juros, 2, ',', '.');
$multaF = @number_format($multa, 2, ',', '.');

$query2 = $pdo->query("SELECT * from clientes where id = '$cliente'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = @$res2[0]['nome'];
$telefone_cliente = $res2[0]['telefone'];
	$email_cliente = $res2[0]['email'];
	$cpf_cliente = $res2[0]['cpf'];	
	$endereco_cliente = $res2[0]['endereco'];
	$data_nasc_cliente = $res2[0]['data_nasc'];
	$bairro_cliente = $res2[0]['bairro'];
	$cidade_cliente = $res2[0]['cidade'];
	$estado_cliente = $res2[0]['estado'];
	$cep_cliente = $res2[0]['cep'];

$query2 = $pdo->query("SELECT * from usuarios where id = '$usuario'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_usuario = @$res2[0]['nome'];


$classe_debito = '';
//verificar débito
$query2 = $pdo->query("SELECT * from receber where referencia = 'Empréstimo' and id_ref = '$id' and pago = 'Não' and data_venc < curDate()");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_atras = @count($res2);
if(@count($res2) > 0){
	$classe_debito = 'text-danger';
}

$atrasadas = '0';
if($total_atras > 0){
	$atrasadas = '('.$total_atras.')';
}



//verificar total parcelas
$query2 = $pdo->query("SELECT * from receber where referencia = 'Empréstimo' and id_ref = '$id'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_de_parcelas = @count($res2);

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
$total_jurosF = @number_format($total_juros, 2, ',', '.');


$query2 = $pdo->query("SELECT * FROM receber where referencia = 'Empréstimo' and id_ref = '$id' and pago != 'Sim'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$valor_parc = @$res2[0]['valor'];

$total_a_pagar = $valor + $valor_parc;

}



//projecao lucro emprestimo
$query2 = $pdo->query("SELECT * FROM receber where referencia = 'Empréstimo' and id_ref = '$id'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$num_parcelas = @$res2[0]['parcela'];
$valor_p = @$res2[0]['valor'];
$parcela_sem_juros = @$res2[0]['parcela_sem_juros'];
$projecao = ($valor_p - $parcela_sem_juros) * $parcelas;
$projecaoF = @number_format($projecao, 2, ',', '.');
$texto_projecao = 'R$ '.$projecaoF.' /';

if($tipo_juros == 'Somente Júros'){
	$texto_projecao = 'Júros Pagos :';
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

				<td style="border: 1px; solid #000; width: 40%; text-align: left;">

					<img style="margin-top: 7px; margin-left: 7px;" id="imag" src="<?php echo $url_sistema ?>img/logo.jpg" width="175px">

				</td>

				

				<td style="width: 5%; text-align: center; font-size: 13px;">

				

				</td>

				<td style="width: 40%; text-align: right; font-size: 9px;padding-right: 10px;">

						<b><big>DETALHAMENTO EMPRÉSTIMO <?php echo @mb_strtoupper($status) ?></big></b><br> <b>CLIENTE: </b><?php echo mb_strtoupper($nome_cliente) ?> <br> <?php echo mb_strtoupper($data_hoje) ?>

				</td>

			</tr>		

		</table>

	</div>

</div>




<table id="cabecalhotabela" style="border-style: solid; font-size: 9px; margin-bottom:10px; width: 100%; table-layout: fixed; margin-top:-40px;">
			<thead>
				
				<tr id="cabeca" style="margin-left: 0px; background-color:#f2f0f0">
					<td colspan="4" style="width:100%; font-size: 10px"><b>DADOS DO CLIENTE</b> </td>					
				</tr>
				<tr >
					<td style="width:10%; border-right: 1px solid #000;border-bottom: : 1px solid #000;">NOME: </td>
					<td style="width:60%; border-right: : 1px solid #000; border-bottom: : 1px solid #000;">
						<?php echo @mb_strtoupper($nome_cliente) ?>
					</td>

					<td style="width:10%; border-bottom: : 1px solid #000; border-right: 1px solid #000;">TELEFONE: </td>
					<td style="width:20%; border-bottom: : 1px solid #000; ">
						<?php echo @mb_strtoupper($telefone_cliente) ?>
					</td>
					
					
    			</tr>
    			<tr >

    				<td style="width:10%; border-right: 1px solid #000;border-bottom: : 1px solid #000;">ENDEREÇO: </td>
					<td style="width:60%; border-right: : 1px solid #000;">
						<?php echo @mb_strtoupper($endereco_cliente) ?> 
						<?php echo @mb_strtoupper($numero_cliente) ?> 
						<?php echo @mb_strtoupper($bairro_cliente) ?> 
						<?php echo @mb_strtoupper($cidade_cliente) ?> 
						<?php echo @mb_strtoupper($estado_cliente) ?>

					</td>
					
					
					<td style="width:10%; border-right: 1px solid #000;">CPF: </td>
					<td style="width:20%; ">
						<?php echo @mb_strtoupper($cpf_cliente) ?>
					</td>
    			</tr>
			</thead>
		</table>






		<table id="cabecalhotabela" style="border-bottom-style: solid; font-size: 12px; margin-bottom:10px; width: 100%; table-layout: fixed;">

			<thead>

				

				<tr id="cabeca" style="margin-left: 0px; background-color:#CCC; font-weight: bold">

					
					<td style="width:20%">VALOR EMPRÉSTIMO</td>

					<td style="width:12%">PARCELAS</td>

					<td style="width:14%">ATRASADAS</td>

					<td style="width:17%">DATA EMPRÉSTIMO</td>

					<td style="width:18%">JÚROS</td>

					<td style="width:19%">PROJEÇÃO / LUCRO</td>					

					

				</tr>



				<tr id="cabeca" style="margin-left: 0px;">

					<td style="width:20%">R$ <?php echo $valorF ?> </td>
					<td style="width:12%"><?php echo $parcelas_pagas ?> / <?php echo $total_de_parcelas ?></td>
					<td style="width:14%; "><span style="color:red"><?php echo $atrasadas ?> </span>Parcela(s)</td>
					<td style="width:17%"><?php echo $dataF ?> </td>
					<td style="width:18%"><?php echo $juros_emp ?>% <small><span style="color:blue">(<?php echo $tipo_juros ?>)</span></small></td>
					<td style="width:19%"><small><span style="color:blue"><?php echo $texto_projecao ?></span>  <span style="color:green">R$ <?php echo $total_jurosF ?></span></small> </td>

				</tr>

			</thead>

		</table>




	


		<?php if($obs != ""){ ?>
		<table>

			<thead>
				<tbody>
					<tr>
						<td style="font-size: 10px; width:100%; text-align: left;"><b>Observações:</b> <?php echo $obs ?></td>					

					</tr>
				</tbody>
			</thead>
		</table>

	<?php } ?>





<div style="border-bottom: 1px solid #000; margin-top: 30px">

				<div style="font-size: 11px; margin-bottom: 7px"><b>DETALHAMENTO DAS PARCELAS </b> 
					<?php if($total_atras > 0){ ?>
						<span style="color:red">(INADIMPLÊNTE)</span>
					<?php } ?>
				</div>

			</div>	

			<table id="cabecalhotabela" style="border-bottom-style: solid; font-size: 11px; margin-bottom:10px; width: 100%; table-layout: fixed;">
			<thead>
				
				<tr id="cabeca" style="margin-left: 0px; background-color:#CCC">					
					<td style="width:15%">VALOR</td>					
					<td style="width:15%">VENCIMENTO</td>
					<td style="width:15%">PAGAMENTO</td>
					<td style="width:25%">FORMA PGTO</td>						
					<td style="width:35%">RECEBIDO POR</td>		
					
				</tr>
			</thead>
		</table>



		<table style="width: 100%; table-layout: fixed; font-size:10px; text-transform: uppercase;">
			<thead>
				<tbody>
					<?php

$total_valor = 0;
$total_valorF = 0;
$total_pendentes = 0;
$total_pendentesF = 0;
$total_pagas = 0;
$total_pagasF = 0;
$pendentes = 0;
$pagas = 0;
$total_vencidas = 0;
$total_vencidas_valor = 0;
$total_vencidas_valorF = 0;

$query = $pdo->query("SELECT * from receber where referencia = 'Empréstimo' and id_ref = '$id' order by id asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
for($i=0; $i<$linhas; $i++){
	$id_par = $res[$i]['id'];
$valor = $res[$i]['valor'];
$parcela = $res[$i]['parcela'];
$data_venc = $res[$i]['data_venc'];
$data_pgto = $res[$i]['data_pgto'];
$pago = $res[$i]['pago'];
$ref_pix = $res[$i]['ref_pix'];
$dias_frequencia = $res[$i]['frequencia'];
$referencia = $res[$i]['referencia'];
$id_ref = $res[$i]['id_ref'];
$nova_parcela = $parcela + 1;
$descricao = $res[$i]['descricao'];
$recorrencia = $res[$i]['recorrencia'];
$cliente = $res[$i]['cliente'];
$usuario_baixa = $res[$i]['usuario_pgto'];
$forma_pgto = $res[$i]['forma_pgto'];

//dados do cliente
$query2 = $pdo->query("SELECT * from clientes where id = '$cliente'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = $res2[0]['nome'];
$tel_cliente = $res2[0]['telefone'];
$tel_cliente = '55'.preg_replace('/[ ()-]+/' , '' , $tel_cliente);
$telefone_envio = $tel_cliente;


$data_vencF = implode('/', array_reverse(@explode('-', $data_venc)));
$data_pgtoF = implode('/', array_reverse(@explode('-', $data_pgto)));
$valorF = @number_format($valor, 2, ',', '.');


$valor_final = $valor;
if($data_pgtoF == '00/00/0000' || $data_pgtoF == ''){
	$data_pgtoF = 'Pendente';
	$classe_pago = 'text-danger';
	$ocultar_baixar = '';
	$ocultar_pendentes = 'ocultar';
}else{
	$classe_pago = 'text-success';
	$ocultar_baixar = 'ocultar';
	$ocultar_pendentes = '';
}

$valor_multa = 0;
$valor_juros = 0;
$dias_vencido = 0;
$classe_venc = '';
if(@strtotime($data_venc) < @strtotime($data_atual) and $pago != 'Sim'){
$classe_venc = 'text-danger';
$valor_multa = $multa;

//calcular quanto dias está atrasado

$data_inicio = new DateTime($data_venc);
$data_fim = new DateTime($data_atual);
$dateInterval = $data_inicio->diff($data_fim);
$dias_vencido = $dateInterval->days;

$valor_juros = $dias_vencido * ($juros * $valor / 100);

$valor_final = $valor_juros + $valor_multa + $valor;

}

$valor_finalF = @@number_format($valor_final, 2, ',', '.');

if($parcela == 0){
	$parcela = 'Quitado';
}

if($pago == 'Sim'){
	$classe_pago = 'verde.jpg';
	$pagas += 1;
	$total_pagas += $valor;
}else{
	$classe_pago = 'vermelho.jpg';
	$pendentes += 1;
	$total_pendentes += $valor;
}


$classe_venc = '';
if(strtotime($data_venc) < strtotime($data_atual) and $pago != 'Sim'){
	$classe_venc = 'red';
	$total_vencidas_valor += $valor;
}
$total_vencidas_valorF = @@number_format($total_vencidas_valor, 2, ',', '.');
$total_pagasF = @@number_format($total_pagas, 2, ',', '.');
$total_pendentesF = @@number_format($total_pendentes, 2, ',', '.');

$query2 = $pdo->query("SELECT * from usuarios where id = '$usuario_baixa'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_usuario_baixa = @$res2[0]['nome'];	

  	 ?>

  	 
      <tr>
<td style="width:15%">
<img style="margin-top: 0px" src="<?php echo $url_sistema ?>painel/images/<?php echo $classe_pago ?>" width="8px">
	<?php echo $valorF ?></td>
<td style="width:15%; color:<?php echo $classe_venc ?>"><?php echo $data_vencF ?></td>
<td style="width:15%; "><?php echo $data_pgtoF ?></td>
<td style="width:25%"><?php echo $forma_pgto ?></td>
<td style="width:35%"><?php echo $nome_usuario_baixa ?></td>


    </tr>

<?php } } ?>
				</tbody>
	
			</thead>
		</table>
	


<table>
			<thead>
				<tbody>
					<tr>

						<td style="font-size: 10px; width:280px; text-align: right;"></td>

						

						<td style="font-size: 10px; width:10px; text-align: right;"></td>

							<td style="font-size: 10px; width:120px; text-align: right;"><b>Pagas: <span style="color:green">R$ <?php echo $total_pagasF ?></span></td>


								<td style="font-size: 10px; width:140px; text-align: right;"><b>Pendentes: <span style="color:blue">R$ <?php echo $total_pendentesF ?></span></td>

									<td style="font-size: 10px; width:120px; text-align: right;"><b>Vencidas: <span style="color:red">R$ <?php echo $total_vencidas_valorF ?></span></td>
						
					</tr>
				</tbody>
			</thead>
		</table>			


<hr>
<br><br><br>
<div align="center">
	_____________________________________________________________<br>
	<small><small>(Assinatura)</small></small>
</div>







<div id="footer" class="row">

<hr style="margin-bottom: 0;">

	<table style="width:100%;">

		<tr style="width:100%;">

			<td style="width:50%; font-size: 10px; text-align: left;"><?php echo $nome_sistema ?> Telefone: <?php echo $telefone_sistema ?></td>



			<td style="width:50%; font-size: 10px; text-align: right;"> Endereço: <?php echo $endereco_sistema ?></td>

			

		</tr>

	</table>

</div>






</body>



</html>





