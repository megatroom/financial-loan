<?php 
$tabela = 'emprestimos';
require_once("../../../conexao.php");
@session_start();
$id_usuario = @$_SESSION['id'];

$data_atual = date('Y-m-d');

$valor = $_POST['valor'];
$valor = str_replace('.', '', $valor);
$valor = str_replace(',', '.', $valor);
$parcelas = $_POST['parcelas'];
$juros = $_POST['juros'];
$multa = $_POST['multa'];
$multa = str_replace('.', '', $multa);
$multa = str_replace(',', '.', $multa);
$juros = str_replace('.', '', $juros);
$juros = str_replace(',', '.', $juros);
$juros_emp = $_POST['juros_emp'];
$juros_emp = str_replace(',', '.', $juros_emp);
$obs = $_POST['obs'];
$data_venc = $_POST['data_venc'];
$id = $_POST['id'];
$dias_frequencia = $_POST['frequencia'];
$tipo_juros = $_POST['tipo_juros'];
$data = $_POST['data'];
$enviar_whatsapp = $_POST['enviar_whatsapp'];

$data_emprestimo_post = $_POST['data'];

$frequencia_conta = '0';
$recorrencia_conta = '';

if($tipo_juros == 'Somente Júros'){
$parcelas = 1;
$frequencia_conta = $dias_frequencia;
$recorrencia_conta = 'Sim';
}

$query = $pdo->query("SELECT * from frequencias where dias = '$dias_frequencia'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$frequencia = $res[0]['frequencia'];




$valor_parcela = $valor / $parcelas;


$query = $pdo->prepare("INSERT INTO $tabela SET cliente = '$id', valor = :valor, parcelas = :parcelas, juros = :juros, multa = :multa, data = '$data', usuario = '$id_usuario', obs = :obs, juros_emp = :juros_emp, data_venc = :data_venc, frequencia = '$frequencia', tipo_juros = '$tipo_juros' ");

$query->bindValue(":valor", "$valor");
$query->bindValue(":parcelas", "$parcelas");
$query->bindValue(":juros", "$juros");
$query->bindValue(":multa", "$multa");
$query->bindValue(":obs", "$obs");
$query->bindValue(":juros_emp", "$juros_emp");
$query->bindValue(":data_venc", "$data_venc");
$query->execute();
$ult_id = $pdo->lastInsertId();


//dados do cliente
$query = $pdo->query("SELECT * from clientes where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = $res[0]['nome'];
$tel_cliente = $res[0]['telefone'];
$tel_cliente = '55'.preg_replace('/[ ()-]+/' , '' , $tel_cliente);
$telefone_envio = $tel_cliente;




//lançar a saída
$pdo->query("INSERT INTO pagar SET descricao = '$nome_cliente', valor = '$valor', data = curDate(), data_venc = curDate(), data_pgto = curDate(), usuario_lanc = '$id_usuario', usuario_pgto = '$id_usuario', referencia = 'Empréstimo', id_ref = '$ult_id', pago = 'Sim' ");

$valor_total_juros = 0;
$valor_parcelas_soma = 0;
//lançar as contas a receber (parcelas do pagamento)
for($i=1; $i <= $parcelas; $i++){
$descricao = $nome_cliente.' ('.$i.')';

//calculo dos juros 
$valor_sem_juros = $valor_parcela;

//juros padrão
if($tipo_juros == 'Padrão'){
	$valor_parcela_final = $valor_parcela + ($valor_parcela * $juros_emp / 100);
}


//juros Price JS 
if($tipo_juros == 'Simples'){
$valor_parcela_final = $valor_parcela + ($valor_parcela * $juros_emp / 100) * ($i);
}

//juros Composto (simples)
if($tipo_juros == 'Composto'){
$valor_parcela_final = $valor_parcela * (1 + ($juros_emp / 100))**$parcelas;
}


//juros Composto (Price JS Usado nos empréstimos bancários)
if($tipo_juros == 'Composto_Price'){
$valor_parcela_final = $valor * (($juros_emp / 100)*(1 + ($juros_emp / 100))**$parcelas) / ((1 + ($juros_emp / 100))**$parcelas - 1);
}

//juros Prefixado
if($tipo_juros == 'Prefixado'){
$valor_parcela_final = $valor_parcela + ($valor * $juros_emp / 100);
}


//Sem Júros
if($tipo_juros == 'Sem Júros'){
$valor_parcela_final = $valor_parcela;
}


//Sem Júros
if($tipo_juros == 'Somente Júros'){
	$valor_parcela_final = $valor * $juros_emp / 100;
	$valor_sem_juros = 0;
}



if($tipo_juros == 'Simples'){
	$valor_parcelas_soma += $valor_parcela_final;
	$valor_total_juros = $valor_parcelas_soma - $valor;
}else{
	$valor_total_juros = $valor_parcela_final * $parcelas - $valor;
}

	$dias_parcela = $i - 1;
	$dias_parcela_2 = ($i - 1) * $dias_frequencia;

	if($i == 1){
		$novo_vencimento = $data_venc;
		if($dias_frequencia == 1){
			$novo_vencimento = date('Y-m-d', strtotime("+1 days",strtotime($data_venc)));
		}
	}else{


		if($dias_frequencia == 30 || $dias_frequencia == 31){
			
			$novo_vencimento = date('Y-m-d', strtotime("+$dias_parcela month",strtotime($data_venc)));

		}else if($dias_frequencia == 90){ 
			$dias_parcela = $dias_parcela * 3;
			$novo_vencimento = date('Y-m-d', strtotime("+$dias_parcela month",strtotime($data_venc)));

		}else if($dias_frequencia == 180){ 

			$dias_parcela = $dias_parcela * 6;
			$novo_vencimento = date('Y-m-d', strtotime("+$dias_parcela month",strtotime($data_venc)));

		}else if($dias_frequencia == 360 || $dias_frequencia == 365){ 

			$dias_parcela = $dias_parcela * 12;
			$novo_vencimento = date('Y-m-d', strtotime("+$dias_parcela month",strtotime($data_venc)));

		}else if($dias_frequencia == 15){ 
			
			$novo_vencimento = date('Y-m-d', strtotime("+15 days",strtotime($novo_vencimento)));

		}else if($dias_frequencia == 7){ 
			
			$novo_vencimento = date('Y-m-d', strtotime("+7 days",strtotime($novo_vencimento)));

		}else{
			
			$novo_vencimento = date('Y-m-d', strtotime("+1 days",strtotime($novo_vencimento)));
		}

	}

	//verificação de feriados
	require("../../verificar_feriados.php");
	
		
	


$pdo->query("INSERT INTO receber SET cliente = '$id', referencia = 'Empréstimo', id_ref = '$ult_id', valor = '$valor_parcela_final', parcela = '$i', usuario_lanc = '$id_usuario', data = curDate(), data_venc = '$novo_vencimento', pago = 'Não', descricao = '$descricao', frequencia = '$frequencia_conta', recorrencia = '$recorrencia_conta', parcela_sem_juros = '$valor_sem_juros', hora_alerta = '$hora_random' ");
$ult_id_conta = $pdo->lastInsertId();



}

echo 'Salvo com Sucesso';


if($token != "" and $instancia != "" and $enviar_whatsapp == 'Sim'){
//enviar mensagem para o cliente

	$data_vencF = date('d', strtotime($data_venc));
	$dataF = implode('/', array_reverse(explode('-', $data_emprestimo_post)));
	$valorF = number_format($valor, 2, ',', '.');
	$valor_total_jurosF = number_format($valor_total_juros, 2, ',', '.');

	if($seletor_api == 'menuia'){
		$pcto = '%';
	}else{
		$pcto = 'PCento';
	}

	$mensagem = '💰_Novo Empréstimo '.$nome_sistema.'_ %0A';
	$mensagem .= 'Cliente: *'.$nome_cliente.'* %0A';
	$mensagem .= 'Valor: '.$valorF.' %0A';
	$mensagem .= 'Júros '.$tipo_juros.': '.$juros_emp.''.$pcto.' %0A';
	if($tipo_juros != "Somente Júros"){
		$mensagem .= 'Júros pago no final: *'.$valor_total_jurosF.'* %0A';
	}

	$mensagem .= 'Data Empréstimo: *'.$dataF.'* %0A';
	$mensagem .= 'Dia Pgto Parcelas: *Dia '.$data_vencF.'* %0A';
	$mensagem .= 'Frequência Parcelas: *'.$frequencia.'* %0A%0A';

	$mensagem .= '*Parcelas* %0A';

	$query = $pdo->query("SELECT * FROM receber where referencia = 'Empréstimo' and id_ref = '$ult_id'  order by id asc");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = @count($res);
	if($total_reg > 0){
		for($i=0; $i < $total_reg; $i++){
			$valor = $res[$i]['valor'];
			$parcela = $res[$i]['parcela'];
			$data_venc = $res[$i]['data_venc'];

			$data_vencF = implode('/', array_reverse(explode('-', $data_venc)));
			$valorF = number_format($valor, 2, ',', '.');

			$mensagem .= '💲('.$parcela.') R$: *'.$valorF.'* Venc: '.$data_vencF.'%0A';
		}
	}

	require('../../apis/texto.php');

}

//atualizar valor da parcela
$pdo->query("UPDATE emprestimos SET valor_parcela = '$valor_parcela_final' where id = '$ult_id'");

 ?>