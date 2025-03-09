<?php 
@session_start();
$id_usuario = @$_SESSION['id'];
$tabela = 'receber';
require_once("../../../conexao.php");
$data_atual = date('Y-m-d');

$status = @$_POST['p1'];

$valor_pendentes = 0;
$valor_pago = 0;
$valor_pendentesF = 0;
$valor_pagoF = 0;
$query = $pdo->query("SELECT * from $tabela where cliente = '$id_usuario' and pago LIKE '%$status%' order by pago asc, id asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
echo <<<HTML
<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Descrição</th>	
	<th class="esc">Valor</th>	
	<th class="esc">Lançada Em</th>	
	<th class="esc">Vencimento</th>	
	<th class="esc">Data Pgto</th>	
	<th class="esc">Referência</th>	
	<th class="esc">Pagar</th>	
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i<$linhas; $i++){
	$id = $res[$i]['id'];	
	$descricao = $res[$i]['descricao'];
	$valor = $res[$i]['valor'];
	$data = $res[$i]['data'];
	$data_venc = $res[$i]['data_venc'];	
	$data_pgto = $res[$i]['data_pgto'];
	$usuario_lanc = $res[$i]['usuario_lanc'];
	$usuario_baixa = $res[$i]['usuario_pgto'];
	$referencia = $res[$i]['referencia'];
	$id_ref = $res[$i]['id_ref'];
	$pago = $res[$i]['pago'];
	$obs = $res[$i]['obs'];



$query2 = $pdo->query("SELECT * from usuarios where id = '$usuario_lanc'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_usuario_lanc = @$res2[0]['nome'];

$query2 = $pdo->query("SELECT * from usuarios where id = '$usuario_baixa'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_usuario_baixa = @$res2[0]['nome'];

	$data_vencF = implode('/', array_reverse(@explode('-', $data_venc)));
	$data_pgtoF = implode('/', array_reverse(@explode('-', $data_pgto)));
	$dataF = implode('/', array_reverse(@explode('-', $data)));
	
	$valorF = number_format($valor, 2, ',', '.');

	if($pago == 'Sim'){
		$classe_square = 'verde';
		$classe_baixar = 'ocultar';
		$classe_pago = '';
		$valor_pago += $valor;
	}else{
		$classe_square = 'text-danger';
		$classe_baixar = '';
		$valor_pendentes += $valor;
		$classe_pago = 'ocultar';
	}

	$valor_pagoF = @number_format($valor_pago, 2, ',', '.');
	$valor_pendentesF = @number_format($valor_pendentes, 2, ',', '.');

echo <<<HTML
<tr style="">
<td>

<i class="fa fa-square {$classe_square}"></i>
{$descricao}
</td>
<td class="esc">R$ {$valorF}</td>
<td class="esc">{$dataF}</td>
<td class="esc">{$data_vencF}</td>
<td class="esc">{$data_pgtoF}</td>
<td class="esc">{$referencia}</td>
<td>
	<big><a class="{$classe_baixar}" href="../pagar/{$id}" target="_blank" title="Efetuar Pagamento"><i class="fa fa-usd text-success"></i></a></big>

	<form  method="POST" action="../painel/rel/recibo_class.php" target="_blank" style="display:inline-block">
					<input type="hidden" name="id" value="{$id}">
					<input type="hidden" name="enviar" value="Não">
					<big><button class="{$classe_pago}" title="Abrir Recibo" style="background:transparent; border:none; margin:0; padding:0"><i class="fa fa-file-pdf-o " style="color:green"></i></button></big>
					</form>
</td>
</tr>
HTML;

}


echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>
</table>
<br>
<div align="right">
<span style="margin-right: 20px">Total Pendentes <span style="color:red">R$ {$valor_pendentesF}</span></span>
<span style="">Total Pagas <span style="color:green">R$ {$valor_pagoF}</span></span>
</div>
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
	function editar(id, descricao, valor, data_venc, obs){
		$('#mensagem').text('');
    	$('#titulo_inserir').text('Editar Registro');

    	$('#id').val(id);
    	$('#descricao').val(descricao);
    	$('#valor').val(valor);
    	$('#data_venc').val(data_venc);
    	$('#obs').val(obs);
    	
    	$('#modalForm').modal('show');
	}



	function limparCampos(){
		$('#id').val('');
    	$('#descricao').val('');
    	$('#valor').val('');
    	$('#data_venc').val("<?=$data_atual?>");	
    	$('#obs').val('');
    	

    	$('#ids').val('');
    	$('#btn-deletar').hide();	
	}

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