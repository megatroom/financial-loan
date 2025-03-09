<?php 
require_once("../../../conexao.php");
$pagina = 'receber';
$id = $_POST['id_emp'];
$mostrar = @$_POST['mostrar'];

if($mostrar == 'cobranca'){
	$sql_mostrar = 'Cobrança';
	$sql_consulta = 'cobrancas';
}else{
	$sql_mostrar = 'Empréstimo';
	$sql_consulta = 'emprestimos';
}

$data_atual = date('Y-m-d');

$query = $pdo->query("SELECT * FROM $sql_consulta where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$multa = $res[0]['multa'];
$juros = $res[0]['juros'];
$cliente = $res[0]['cliente'];

$query = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$telefone = $res[0]['telefone'];

echo <<<HTML
<small>
HTML;
$query = $pdo->query("SELECT * FROM $pagina where referencia = '$sql_mostrar' and id_ref = '$id'  order by id asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){
echo <<<HTML
	<table class="table table-hover" id="">
		<thead> 
			<tr> 				
				<th>Parcela</th>
				<th>Valor</th>				
				<th>Vencimento</th>				
				<th>Data PGTO</th>				
				<th>Recibo / Pagar</th>
			</tr> 
		</thead> 
		<tbody> 
HTML;
for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
$id_par = $res[$i]['id'];
$valor = $res[$i]['valor'];
$parcela = $res[$i]['parcela'];
$data_venc = $res[$i]['data_venc'];
$data_pgto = $res[$i]['data_pgto'];
$pago = $res[$i]['pago'];
$ref_pix = $res[$i]['ref_pix'];


$data_vencF = implode('/', array_reverse(@explode('-', $data_venc)));
$data_pgtoF = implode('/', array_reverse(@explode('-', $data_pgto)));
$valorF = number_format($valor, 2, ',', '.');


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

$valor_finalF = @number_format($valor_final, 2, ',', '.');

if($parcela == 0){
	$parcela = 'Quitado';
}

echo <<<HTML
			<tr>					
				<td class="">{$parcela}</td>
				<td class="{$classe_pago}">R$ {$valorF}</td>				
				<td class="{$classe_venc}">{$data_vencF}</td>
				<td class="">{$data_pgtoF}</td>
				
				<td>					
					

						<form   method="POST" action="../painel/rel/recibo_class.php" target="_blank" style="display:inline-block">
					<input type="hidden" name="id" value="{$id_par}">
					<input type="hidden" name="enviar" value="Não">
					<big><button class="{$ocultar_pendentes}" title="Enviar Recibo" style="background:transparent; border:none; margin:0; padding:0"><i class="fa fa-file-pdf-o " style="color:red"></i></button></big>
					</form>


					<big><a class="{$ocultar_baixar}" href="../pagar/{$id_par}" target="_blank" title="Efetuar Pagamento"><i class="fa fa-usd text-success"></i></a></big>



				</td>  
			</tr> 
HTML;
}
echo <<<HTML
		</tbody> 
	</table>
</small>
HTML;
}else{
	echo 'Nenhum registro cadastrado!';
}

?>


<script type="text/javascript">

	function baixarParcela(id_par, valor, multa, juros){	 
	$('#id_baixar').val(id_par);	
	$('#valor_baixar').val(valor);
	$('#multa_baixar').val(multa);
	$('#juros_baixar').val(juros);

	calcular();
    $('#modalBaixar').modal('show');
}

function cobrar(id_emp, parcela, valor, data, telefone, multa, juros, id_par){

	var instancia = "<?=$instancia?>";
	var token = "<?=$token?>";

	var id_empr = $('#id_emprestimo').val();
	var id_cob = $('#id_cobranca').val();
	
	if(multa == ""){
		multa = 0;
	}

	if(juros == ""){
		juros = 0;
	}


	if(instancia.trim() == "" || token.trim() == ""){
		alert('Insira um Token e Instancia Whatsapp nas configurações');
		return;
	}
	$.ajax({
	        url: 'paginas/clientes/gerar_cobranca.php',
	        method: 'POST',
	        data: {parcela, valor, data, telefone, multa, juros, id_par},
	        dataType: "html",

	        success:function(result){	
	        //alert(result) 
	        	alert('Cobrança Efetuada!');
	           	
	           	 if(id_empr != ""){
                	 mostrarParcelasEmp(id_empr)
                }

                if(id_cob != ""){
                	 mostrarParcelas(id_cob);
                }
	        }
	    });
}


</script>


