<?php 
$tabela = 'receber';
require_once("../../../conexao.php");
$data_atual = date('Y-m-d');

$dataInicial = @$_POST['p1'];
$dataFinal = @$_POST['p2'];
$status = '%'.@$_POST['p3'].'%';
$data_buscar = @$_POST['p4'];

if($data_buscar == ""){
	$data_buscar = 'data_venc';
}

if($dataInicial == ""){
	$dataInicial = $data_atual;
}

if($dataFinal == ""){
	$dataFinal = $data_atual;
}


$valor_pendentes = 0;
$valor_pago = 0;
$valor_pendentesF = 0;
$valor_pagoF = 0;
$query = $pdo->query("SELECT * from $tabela where $data_buscar >= '$dataInicial' and $data_buscar <= '$dataFinal' and pago LIKE '$status' order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
echo <<<HTML
<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Descrição</th>	
	<th>Cliente</th>	
	<th class="esc">Valor</th>	
	<th class="esc">Data</th>	
	<th class="esc">Vencimento</th>	
	<th class="esc">Data Pgto</th>	
	<th>Ações</th>
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
	$cliente = $res[$i]['cliente'];
	$forma_pgto = $res[$i]['forma_pgto'];


$query2 = $pdo->query("SELECT * from usuarios where id = '$usuario_lanc'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_usuario_lanc = @$res2[0]['nome'];

$query2 = $pdo->query("SELECT * from clientes where id = '$cliente'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = @$res2[0]['nome'];

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
		$valor_pago += $valor;
	}else{
		$classe_square = 'text-danger';
		$classe_baixar = '';
		$valor_pendentes += $valor;
	}

	$valor_pagoF = @number_format($valor_pago, 2, ',', '.');
	$valor_pendentesF = @number_format($valor_pendentes, 2, ',', '.');

echo <<<HTML
<tr style="">
<td>
<input type="checkbox" id="seletor-{$id}" class="form-check-input" onchange="selecionar('{$id}')">
<i class="fa fa-square {$classe_square}"></i>
{$descricao}
</td>
<td class="esc">{$nome_cliente}</td>
<td class="esc">R$ {$valorF}</td>
<td class="esc">{$dataF}</td>
<td class="esc">{$data_vencF}</td>
<td class="esc">{$data_pgtoF}</td>
<td>
	<big><a href="#" onclick="editar('{$id}','{$descricao}','{$valor}','{$data_venc}','{$obs}','{$cliente}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>

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
		<span><b>Usuário Lançamento:</b> {$nome_usuario_lanc}</span><br>
		<span><b>Usuário Baixa :</b> {$nome_usuario_baixa}</span><br>		
		<span><b>OBS:</b>{$obs}</span><br>
		</p>
		</div>
		</li>										
		</ul>
</li>




<big><a href="#" onclick="arquivo('{$id}','{$descricao}')" title="Inserir / Ver Arquivos"><i class="fa fa-file-archive-o" style="color:#3d1002"></i></a></big>



<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a href="#" class="dropdown-toggle {$classe_baixar}" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-check-square text-success"></i></big></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>Baixar Conta? <a href="#" onclick="baixar('{$id}')"><span class="text-success">Sim</span></a></p>
		</div>
		</li>										
		</ul>
</li>


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
<span style="margin-right: 20px">Total à Receber <span style="color:red">R$ {$valor_pendentesF}</span></span>
<span style="">Total Recebido <span style="color:green">R$ {$valor_pagoF}</span></span>
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
	function editar(id, descricao, valor, data_venc, obs, cliente){
		$('#mensagem').text('');
    	$('#titulo_inserir').text('Editar Registro');

    	$('#id').val(id);
    	$('#descricao').val(descricao);
    	$('#valor').val(valor);
    	mascara_valor('valor');
    	$('#data_venc').val(data_venc);
    	$('#obs').val(obs);
    	$('#cliente').val(cliente).change();
    	
    	$('#modalForm').modal('show');
	}



	function limparCampos(){
		$('#id').val('');
    	$('#descricao').val('');
    	$('#valor').val('');
    	$('#data_venc').val("<?=$data_atual?>");	
    	$('#obs').val('');
    	$('#cliente').val('').change();

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