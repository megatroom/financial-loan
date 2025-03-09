<?php 

@session_start();

$id_usu = @$_SESSION['id'];  
$nivel_usu = @$_SESSION['nivel']; 


$tabela = 'emprestimos';
require_once("../../../conexao.php");

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

$dataInicial = @$_POST['p1'];
$dataFinal = @$_POST['p2'];
$cliente1 = @$_POST['p3'];
$corretor = @$_POST['p4'];


if($dataInicial == ""){
	$dataInicial = $data_mes;
}

if($dataFinal == ""){
	$dataFinal = $data_final_mes;
}



@session_start();
$id_usu = @$_SESSION['id']; 

if($corretor == ""){
	$sql_corretor = '';
}else{
	$sql_corretor = " and usuario = '$corretor' ";
}

if($cliente1 == ""){
	$sql_cliente = '';
}else{
	$sql_cliente = " and cliente = '$cliente1' ";
}



$valor_emprestado = 0;
@$valor_do_lucro_total = 0;  

$query = $pdo->query("SELECT * from emprestimos where data >= '$dataInicial' and data <= '$dataFinal' $sql_corretor $sql_cliente  order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);	

if($linhas > 0){
echo <<<HTML
<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Cliente</th>	
	<th class="esc">R$ Emprestado</th>	
	<th class="esc">Parcela</th>	
	<th class="esc">Data</th>	
	<th class="esc">Juros %</th>	
	<th class="esc">frequencia pgto</th>
	<th class="esc">Total Pago</th>
	<th class="esc">Lucro</th>
	
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
	$classe_lucro = 'verde';
}else{
	$classe_lucro = 'text-danger';
}


echo <<<HTML
<tr style="">
<td>
{$nome_cliente}
</td>
<td class="esc text-danger">R$ {$valorF}</td>
<td class="esc">{$parcelas_pagas} / {$parcelas}</td>
<td class="esc">{$dataF}</td>
<td class="esc">{$juros_emp}%</td>
<td class="esc">{$frequencia}</td>
<td class="esc verde">R$ {$valor_total_pagoF}</td>
<td class="esc {$classe_lucro}">R$ {$lucro_emprestimoF}</td>

</tr>
HTML;

$valor_do_lucro_total += $lucro_emprestimo;
if($valor_do_lucro_total > 0){
	$classe_lucro_final = 'verde';
}else{
	$classe_lucro_final = 'text-danger';
}

}


$valor_do_lucro_totalF = @number_format($valor_do_lucro_total, 2, ',', '.');



echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>
</table>
<br>
<div align="right">

<span style="font-size: 15px">Total Lucro <span class="{$classe_lucro_final}">R$ {$valor_do_lucro_totalF}</span></span>
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
    	$('#data_venc').val('');    	
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