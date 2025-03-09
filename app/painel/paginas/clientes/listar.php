<?php 
$tabela = 'clientes';
require_once("../../../conexao.php");

$data_atual = date('Y-m-d');

$query = $pdo->query("SELECT * from $tabela order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
echo <<<HTML
<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Nome</th>	
	<th class="esc">Telefone</th>	
	<th class="esc">Status</th>	
	<th class="esc">Email</th>	
	<th class="esc">Data Cadastro</th>
	<th class="esc">Grupo / Local</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;


for($i=0; $i<$linhas; $i++){
	$id = $res[$i]['id'];
	$nome = $res[$i]['nome'];
	$telefone = $res[$i]['telefone'];
	$email = $res[$i]['email'];
	$cpf = $res[$i]['cpf'];	
	$endereco = $res[$i]['endereco'];
	$data_nasc = $res[$i]['data_nasc'];
	$data_cad = $res[$i]['data_cad'];
	$obs = $res[$i]['obs'];
	$pix = $res[$i]['pix'];
	$indicacao = $res[$i]['indicacao'];
	$bairro = $res[$i]['bairro'];
	$cidade = $res[$i]['cidade'];
	$estado = $res[$i]['estado'];
	$cep = $res[$i]['cep'];
	$pessoa = $res[$i]['pessoa'];

	$nome_sec = @$res[$i]['nome_sec'];
	$telefone_sec = @$res[$i]['telefone_sec'];
	$endereco_sec = @$res[$i]['endereco_sec'];
	$grupo = @$res[$i]['grupo'];
	$status = @$res[$i]['status'];

	$data_nascF = implode('/', array_reverse(explode('-', $data_nasc)));
	$data_cadF = implode('/', array_reverse(explode('-', $data_cad)));

	$tel_whatsF = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);

	//verificar total de emprestimos do cliente
$query2 = $pdo->query("SELECT * from emprestimos where cliente = '$id'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_emprestimos = @count($res2);

$query2 = $pdo->query("SELECT * from cobrancas where cliente = '$id'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_cobrancas = @count($res2);

$query2 = $pdo->query("SELECT * from receber where referencia = 'Conta' and cliente = '$id'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_contas = @count($res2);


$classe_status = '';	
$badge_status = '';

if($status == "Ativo"){
	$classe_status = 'green';	
	$badge_status = 'bg-success';
}

if($status == "Inativo"){
	$classe_status = 'gray';
	$badge_status = 'bg-secondary';	
}

if($status == "Alerta"){
	$classe_status = 'orange';
	$badge_status = 'bg-alert';	
}
	
if($status == "Atenção"){
	$classe_status = 'red';	
	$badge_status = 'bg-danger';
}

$ocultar_empre = '';
if($recursos == 'Cobranças'){
	$ocultar_empre = 'ocultar';
}

$ocultar_cobr = '';
if($recursos == 'Empréstimos'){
	$ocultar_cobr = 'ocultar';
}

echo <<<HTML
<tr style="">
<td>
<input type="checkbox" id="seletor-{$id}" class="form-check-input" onchange="selecionar('{$id}')">
{$nome}
</td>
<td class="esc">{$telefone}</td>
<td class="esc"><span class="badge {$badge_status} me-1 my-2 p-1">{$status}</span></td>
<td class="esc">{$email}</td>
<td class="esc">{$data_cadF}</td>
<td class="esc">{$grupo}</td>
<td>
	<big><a href="#" onclick="editar('{$id}','{$nome}','{$telefone}','{$cpf}','{$email}','{$endereco}','{$data_nascF}', '{$obs}', '{$pix}', '{$indicacao}', '{$bairro}', '{$cidade}', '{$estado}', '{$cep}', '{$pessoa}', '{$nome_sec}', '{$telefone_sec}', '{$endereco_sec}', '{$grupo}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>

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

<big><a href="#" onclick="mostrar('{$id}', '{$nome}','{$telefone}','{$cpf}','{$email}','{$endereco}','{$data_nascF}', '{$data_cadF}', '{$obs}', '{$pix}', '{$indicacao}', '{$bairro}', '{$cidade}', '{$estado}', '{$cep}', '{$total_emprestimos}', '{$total_cobrancas}', '{$pessoa}', '{$total_contas}', '{$nome_sec}', '{$telefone_sec}', '{$endereco_sec}', '{$grupo}')" title="Mostrar Dados"><i class="fa fa-info-circle text-primary"></i></a></big>

<big><a class="" href="http://api.whatsapp.com/send?1=pt_BR&phone={$tel_whatsF}" title="Whatsapp" target="_blank"><i class="fa fa-whatsapp " style="color:green"></i></a></big>


<big><a href="#" onclick="arquivo('{$id}','{$nome}')" title="Inserir / Ver Arquivos"><i class="fa fa-file-archive-o" style="color:#3d1002"></i></a></big>

<big><a class="{$ocultar_empre}" href="#" onclick="emprestimo('{$id}','{$nome}')" title="Novo Empréstimo"><i class="fa fa-usd" style="color:green"></i></a></big>

<big><a class="{$ocultar_cobr}" href="#" onclick="cobranca('{$id}','{$nome}')" title="Cobrança Recorrente"><i class="fa fa-money" style="color:green"></i></a></big>

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
	function editar(id, nome, telefone, cpf, email, endereco, data_nasc, obs, pix, indicacao, bairro, cidade, estado, cep, pessoa, nome_sec, telefone_sec, endereco_sec, grupo){
		$('#mensagem').text('');
    	$('#titulo_inserir').text('Editar Registro');

    	$('#id').val(id);
    	$('#nome').val(nome);
    	$('#email').val(email);
    	$('#telefone').val(telefone);
    	$('#endereco').val(endereco);
    	$('#cpf').val(cpf);
    	$('#data_nasc').val(data_nasc);
    	$('#obs').val(obs);
    	$('#pix').val(pix);
    	$('#indicacao').val(indicacao);
    	$('#bairro').val(bairro);
    	$('#cidade').val(cidade);
    	$('#estado').val(estado).change();
    	$('#pessoa').val(pessoa).change();
    	$('#cep').val(cep);

    	$('#nome_sec').val(nome_sec);
    	$('#telefone_sec').val(telefone_sec);
    	$('#endereco_sec').val(endereco_sec);
    	$('#grupo').val(grupo);

    	$('#modalForm').modal('show');
	}


	function mostrar(id, nome, telefone, cpf, email, endereco, data_nasc, data_cad, obs, pix, indicacao, bairro, cidade, estado, cep, total_emprestimos, total_cobrancas, pessoa, total_contas, nome_sec, telefone_sec, endereco_sec, grupo){


		if(obs.trim() == ""){			
		$('#div_obs_dados').hide();
		}

		if(total_emprestimos > 0){
			$('#dados_emp').show();
		}else{
			$('#dados_emp').hide();
		}

		if(total_cobrancas > 0){
			$('#dados_cob').show();
		}else{
			$('#dados_cob').hide();
		}

		if(total_contas > 0){
			$('#dados_deb').show();
		}else{
			$('#dados_deb').hide();
		}
		    	
    	$('#titulo_dados').text(nome);
    	$('#email_dados').text(email);
    	$('#telefone_dados').text(telefone);
    	$('#endereco_dados').text(endereco);
    	$('#cpf_dados').text(cpf);
    	$('#obs_dados').text(obs);
    	$('#data_nasc_dados').text(data_nasc);
    	$('#data_cad_dados').text(data_cad);
    	$('#pix_dados').text(pix);
    	$('#indicacao_dados').text(indicacao);
    	$('#bairro_dados').text(bairro);
    	$('#cidade_dados').text(cidade);
    	$('#estado_dados').text(estado);
    	$('#cep_dados').text(cep);
    	$('#pessoa_dados').text(pessoa);

    	$('#nome_sec_dados').text(nome_sec);
    	$('#telefone_sec_dados').text(telefone_sec);
    	$('#endereco_sec_dados').text(endereco_sec);
    	$('#grupo_dados').text(grupo);

    	$('#cliente_baixar').val('');
    	  	
    	listarEmprestimos(id);
    	listarCobrancas(id);
    	listarDebitos(id);
    	$('#modalDados').modal('show');
	}

	function limparCampos(){
		$('#id').val('');
    	$('#nome').val('');
    	$('#email').val('');
    	$('#telefone').val('');
    	$('#endereco').val('');
    	$('#cpf').val('');
    	$('#obs').val('');
    	$('#data_nasc').val('');
    	$('#pix').val('');
    	$('#indicacao').val('');
    	$('#mensagem_whats').val('');

    	$('#bairro').val('');
    	$('#cidade').val('');
    	$('#estado').val('').change();
    	$('#cep').val('');
    	$('#data_emp').val('<?=$data_atual?>');
    	$('#data_cob').val('<?=$data_atual?>');

    	$('#valor').val('');
    	$('#parcelas').val('1');
    	$('#juros').val('<?=$juros_sistema?>');
    	$('#multa').val('<?=$multa_sistema?>');
    	$('#juros_emp').val('<?=$juros_emprestimo?>');
    	$('#id_emp').val('');
    	$('#frequencia').val('30').change();  

    	mascara_valor('juros')
    	mascara_valor('multa')


    	$('#data_venc_nova').val('<?=$data_atual?>');
    	$('#descricao_nova').val('');
    	$('#obs_nova').val('');
    	$('#valor_nova').val('');

    	$('#valor_cob').val('');
    	$('#parcelas_cob').val('');    	
    	$('#id_cob').val('');
    	$('#frequencia_cob').val('30').change();
    	$('#pessoa').val('Física').change();
    	$('#obs_cob').val('');    	  	

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
			$('#btn-cobrar').hide();
			$('#mensagem_whats').hide();
		}else{
			$('#btn-deletar').show();
			$('#btn-cobrar').show();
			$('#mensagem_whats').show();
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


	function cobrarSel(){
		var ids = $('#ids').val();
		var id = ids.split("-");
		var mensagem = $('#mensagem_whats').val();

		if(mensagem == ""){
			alert('Digite a mensagem na caixa ao lado');
			return;
		}

		for(i=0; i<id.length-1; i++){
			mensagem_w(id[i], mensagem);			
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

	function emprestimo(id, nome){		    	
    	$('#titulo_emp').text('Empréstimo: '+nome);    	
    	$('#id_emp').val(id);    	  	
    	$('#mensagem_emp').text(''); 
    	$('#frequencia').val('30').change();

    	mascara_valor('juros')
    	mascara_valor('multa')
    	
    	$('#modalEmprestimo').modal('show');
	}

	function cobranca(id, nome){		    	
    	$('#titulo_cob').text('Cobrança: '+nome);    	
    	$('#id_cob').val(id);    	  	
    	$('#mensagem_cob').text(''); 
    	$('#frequencia_cob').val('30').change();
    	
    	$('#modalCobranca').modal('show');
	}


	
</script>