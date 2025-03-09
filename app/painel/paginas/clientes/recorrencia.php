<?php 
$tabela = 'cobrancas';
require_once("../../../conexao.php");
@session_start();
$id_usuario = @$_SESSION['id'];

$data_atual = date('Y-m-d');

$valor = $_POST['valor'];
$valor = str_replace('.', '', $valor);
$valor = str_replace(',', '.', $valor);
$parcelas = $_POST['parcelas'];
$obs = $_POST['obs'];
$data_venc = $_POST['data_venc'];
$id = $_POST['id'];
$dias_frequencia = $_POST['frequencia'];
$data = $_POST['data'];
$enviar_whatsapp = $_POST['enviar_whatsapp'];

$juros = $_POST['juros'];
$multa = $_POST['multa'];
$multa = str_replace('.', '', $multa);
$multa = str_replace(',', '.', $multa);
$juros = str_replace('.', '', $juros);
$juros = str_replace(',', '.', $juros);

$query = $pdo->query("SELECT * from frequencias where dias = '$dias_frequencia'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$frequencia = $res[0]['frequencia'];

if($parcelas == "" || $parcelas == "0"){
	$parcelas = 1;
}

$valor_parcela = $valor / $parcelas;


$query = $pdo->prepare("INSERT INTO $tabela SET cliente = '$id', valor = :valor, parcelas = :parcelas, data = curDate(), usuario = '$id_usuario', obs = :obs, data_venc = :data_venc, frequencia = '$frequencia', valor_parcela = '$valor', juros = :juros, multa = :multa ");

$query->bindValue(":valor", "$valor");
$query->bindValue(":parcelas", "$parcelas");
$query->bindValue(":obs", "$obs");
$query->bindValue(":data_venc", "$data_venc");
$query->bindValue(":juros", "$juros");
$query->bindValue(":multa", "$multa");
$query->execute();
$ult_id = $pdo->lastInsertId();


//dados do cliente
$query = $pdo->query("SELECT * from clientes where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = $res[0]['nome'];
$tel_cliente = $res[0]['telefone'];
$tel_cliente = '55'.preg_replace('/[ ()-]+/' , '' , $tel_cliente);
$telefone_envio = $tel_cliente;


$valor_total_juros = 0;
$valor_parcelas_soma = 0;
//lançar as contas a receber (parcelas do pagamento)
for($i=1; $i <= $parcelas; $i++){
$descricao = $nome_cliente.' ('.$i.')';


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


if($parcelas > 1){
	$recorrencia = 'Não';
	$texto_cobranca = 'Frequência Parcelas ';
}else{
	$recorrencia = 'Sim';
	$texto_cobranca = 'Recorrência ';
}

$pdo->query("INSERT INTO receber SET cliente = '$id', referencia = 'Cobrança', id_ref = '$ult_id', valor = '$valor', parcela = '$i', usuario_lanc = '$id_usuario', data = '$data', data_venc = '$novo_vencimento', pago = 'Não', descricao = '$descricao', frequencia = '$dias_frequencia', recorrencia = '$recorrencia', hora_alerta = '$hora_random' ");
$ult_id_conta = $pdo->lastInsertId();




}

echo 'Salvo com Sucesso';


if($token != "" and $instancia != "" and $enviar_whatsapp == 'Sim'){
//enviar mensagem para o cliente

$data_vencF = date('d', strtotime($data_venc));
$dataF = implode('/', array_reverse(explode('-', $data_atual)));
$valorF = number_format($valor, 2, ',', '.');
$valor_total_jurosF = number_format($valor_total_juros, 2, ',', '.');

$mensagem =  '💰_'.$nome_sistema.'_ %0A';
$mensagem .= '😀Cliente: *'.$nome_cliente.'* %0A';
$mensagem .= '💲Valor: *'.$valorF.'* %0A';
$mensagem .= 'Data Cobrança: *'.$dataF.'* %0A';
$mensagem .= 'Dia Pgto Cobranças: *Dia '.$data_vencF.'* %0A';
$mensagem .= $texto_cobranca.': *'.$frequencia.'* %0A%0A';

if($parcelas > 1){
$mensagem .= '*Parcelas* %0A';

$query = $pdo->query("SELECT * FROM receber where referencia = 'Cobrança' and id_ref = '$ult_id'  order by id asc");
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

}


require('../../apis/texto.php');

}

 ?>