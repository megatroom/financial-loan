<?php 
@session_start();
$id_usuario = @$_SESSION['id'];
$tabela = 'emprestimos';
require_once("../../../conexao.php");
$data_atual = date('Y-m-d');


$query = $pdo->query("SELECT * from $tabela where cliente = '$id_usuario' order by id desc");
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
	<th class="esc">Júros</th>	
	<th class="esc">Dia Venc</th>		
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;


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
$cliente = $res[$i]['cliente'];

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
$valorF = number_format($valor, 2, ',', '.');
$jurosF = number_format($juros, 2, ',', '.');
$multaF = number_format($multa, 2, ',', '.');

$query2 = $pdo->query("SELECT * from clientes where id = '$cliente'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = @$res2[0]['nome'];

$query2 = $pdo->query("SELECT * from usuarios where id = '$usuario'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_usuario = @$res2[0]['nome'];


$classe_debito = '';
$texto_debito = '';
//verificar débito
$query2 = $pdo->query("SELECT * from receber where referencia = 'Empréstimo' and id_ref = '$id' and pago = 'Não' and data_venc < curDate()");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if(@count($res2) > 0){
	$classe_debito = 'text-danger';
	$texto_debito = '(Débito)';
}


//verificar parcelas pagas
$query2 = $pdo->query("SELECT * from receber where referencia = 'Empréstimo' and id_ref = '$id' and pago = 'Sim'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$parcelas_pagas = @count($res2);



$query2 = $pdo->query("SELECT * FROM receber where referencia = 'Empréstimo' and id_ref = '$id' and pago != 'Sim'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$valor_parc = @$res2[0]['valor'];

$total_a_pagar = $valor + $valor_parc;


echo <<<HTML
<tr style="">
<td class="{$classe_debito}">
{$nome_cliente} {$classe_finalizado} {$texto_debito}
</td>
<td class="esc">R$ {$valorF}</td>
<td class="esc">{$parcelas_pagas} / {$parcelas}</td>
<td class="esc">{$dataF}</td>
<td class="esc">{$juros_emp}% <small><span class="text-primary">({$tipo_juros})</span></small></td>
<td class="esc">{$data_vencF}</td>
<td>
	


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



<big><a href="../painel/rel/contrato_class.php?id={$id}" target="_blank" title="Gerar Contrato"><i class="fa fa-file-pdf-o text-primary"></i></a></big>

<big><a href="#" onclick="mostrarParcelas('{$id}')" title="Mostrar Parcelas"><i class="fa fa-money verde"></i></a></big>

	

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

		var mostrar = 'emprestimo';
    
    $.ajax({
        url: 'paginas/emprestimos/mostar_parcelas.php',
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


	function baixarEmprestimo(id_emp, valor, cliente){	 
	$('#id_do_emp').val(id_emp);	
	$('#id_do_cliente').val(cliente);	
	$('#valor_final_emprestimo').val(valor);	
	
    $('#modalBaixarEmprestimo').modal('show');
}


</script>


