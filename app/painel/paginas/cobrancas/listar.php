<?php 
$tabela = 'cobrancas';
require_once("../../../conexao.php");
$data_atual = date('Y-m-d');

$dataInicial = @$_POST['p1'];
$dataFinal = @$_POST['p2'];
$cliente = @$_POST['p3'];

$data_atual = date('Y-m-d');
$mes_atual = Date('m');
$ano_atual = Date('Y');
$data_mes = $ano_atual."-".$mes_atual."-01";
$data_ano = $ano_atual."-01-01";

if($mes_atual == '04' || $mes_atual == '06' || $mes_atual == '09' || $mes_atual == '11'){
	$data_final_mes = $ano_atual.'-'.$mes_atual.'-30';
}else if($mes_atual == '02'){
	$bissexto = date('L', @mktime(0, 0, 0, 1, 1, $ano_atual));
	if($bissexto == 1){
		$data_final_mes = $ano_atual.'-'.$mes_atual.'-29';
	}else{
		$data_final_mes = $ano_atual.'-'.$mes_atual.'-28';
	}
	
}else{
	$data_final_mes = $ano_atual.'-'.$mes_atual.'-31';
}


if($dataInicial == ""){
	$dataInicial = $data_mes;
}

if($dataFinal == ""){
	$dataFinal = $data_final_mes;
}

if($cliente == ""){
	$query = $pdo->query("SELECT * from $tabela where data >= '$dataInicial' and data <= '$dataFinal' order by id desc");
}else{
	$query = $pdo->query("SELECT * from $tabela where data >= '$dataInicial' and data <= '$dataFinal' and cliente = '$cliente' order by id desc");
}

$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
echo <<<HTML
<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Cliente</th>	
	<th class="esc">Valor</th>	
	<th class="esc">Parcelas</th>
	<th class="esc">Data</th>	
	<th class="esc">Prox Venc</th>		
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;


for($i=0; $i<$linhas; $i++){
	$id = $res[$i]['id'];
$valor = $res[$i]['valor'];
$parcelas = $res[$i]['parcelas'];
$data_venc = $res[$i]['data_venc'];
$data = $res[$i]['data'];
$cliente = $res[$i]['cliente'];
$juros = $res[$i]['juros'];
$multa = $res[$i]['multa'];
$usuario = $res[$i]['usuario'];
$obs = $res[$i]['obs'];
$frequencia = $res[$i]['frequencia'];



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
$query2 = $pdo->query("SELECT * from receber where referencia = 'Cobrança' and id_ref = '$id' and pago = 'Não' and data_venc < curDate()");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_atras = @count($res2);
if(@count($res2) > 0){
	$classe_debito = 'text-danger';
}

$atrasadas = '';
if($total_atras > 0){
	$atrasadas = '('.$total_atras.')';
}

//verificar parcelas pagas
$query2 = $pdo->query("SELECT * from receber where referencia = 'Cobrança' and id_ref = '$id' and pago = 'Sim'");
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
$data_ultimo_vencF = implode('/', array_reverse(@explode('-', $data_ultimo_venc)));


echo <<<HTML
<tr style="">
<td class="{$classe_debito}">
<input type="checkbox" id="seletor-{$id}" class="form-check-input" onchange="selecionar('{$id}')">
{$nome_cliente}
</td>
<td class="esc">R$ {$valorF}</td>
<td class="esc">{$parcelas_nome} <span style="color:red"><small>{$atrasadas}</small></span></td>
<td class="esc">{$dataF}</td>
<td class="esc {$classe_debito}">{$data_ultimo_vencF}</td>
<td>
	

	<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-trash-o text-danger"></i></big></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
		</div>
		</li>										
		</ul>
</li>



<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-info-circle text-primary "></i></big></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>
		<span><b>Multa por Atraso:</b> R$ {$multaF}</span><br>
		<span><b>Júros dia Atraso:</b> {$jurosF}%</span><br>
		<span><b>Efetuado Por:</b> {$nome_usuario}</span><br>
		<span><b>Frequência Pgto:</b>{$frequencia}</span><br>
		<span><b>OBS:</b>{$obs}</span><br>
		</p>
		</div>
		</li>										
		</ul>
</li>




<big><a href="#" onclick="arquivo('{$id}','{$nome_cliente}')" title="Inserir / Ver Arquivos"><i class="fa fa-file-archive-o" style="color:#3d1002"></i></a></big>


<big><a href="#" onclick="mostrarParcelas('{$id}')" title="Mostrar Parcelas"><i class="fa fa-money verde"></i></a></big>


   <form   method="POST" action="rel/detalhamento_cobranca_class.php" target="_blank" style="display:inline-block">
					<input type="hidden" name="id" value="{$id}">
					<big><button style="background:transparent; border:none; margin:0; padding:0" class="" title="Detalhamento Cobrança"><i class="fa fa-file-pdf-o text-danger"></i></button></big>
		</form>	

</td>
</tr>
HTML;

}


echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>
</table>
HTML;

}else{
	echo '<small>Nenhum Registro Encontrado!</small>';
}
?>



<script type="text/javascript">
	$(document).ready( function () {		
    $('#tabela').DataTable({
    	"language" : {
            //"url" : '//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json'
        },
        "ordering": false,
		"stateSave": true
    });
} );
</script>

<script type="text/javascript">
	
	function selecionar(id){

		var ids = $('#ids').val();

		if($('#seletor-'+id).is(":checked") == true){
			var novo_id = ids + id + '-';
			$('#ids').val(novo_id);
		}else{
			var retirar = ids.replace(id + '-', '');
			$('#ids').val(retirar);
		}

		var ids_final = $('#ids').val();
		if(ids_final == ""){
			$('#btn-deletar').hide();
		}else{
			$('#btn-deletar').show();
		}
	}

	function deletarSel(){
		var ids = $('#ids').val();
		var id = ids.split("-");
		
		for(i=0; i<id.length-1; i++){
			excluir(id[i]);			
		}

		limparCampos();
	}



	function arquivo(id, nome){		    	
    	$('#nome_arquivo').text(nome);    	
    	$('#id_arquivo').val(id);    	  	
    	$('#mensagem_arquivo').text(''); 

    	listarArquivos();
    	$('#modalArquivos').modal('show');
	}

	


	
</script>


<script type="text/javascript">

	function mostrarParcelas(id_emp){	

		var mostrar = 'cobranca';
    
    $.ajax({
        url: 'paginas/clientes/mostar_parcelas.php',
        method: 'POST',
        data: {id_emp, mostrar},
        dataType: "text",

        success: function (mensagem) {           
           $("#listar_parcelas").html(mensagem);
        },      

    });

    $('#id_emprestimo').val(id_emp);
    $('#modalParcelas').modal('show');

}


</script>


