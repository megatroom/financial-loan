<?php 
require_once("../conexao.php");

$query = $pdo->query("SELECT * from receber where data_venc < curDate() and pago != 'Sim' and cliente > 0 and cliente is not null and hora_alerta <= curTime() and (data_alerta != curDate() or data_alerta is null) ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_pagar_vencidas = @count($res);
for ($i = 0; $i < $contas_pagar_vencidas; $i++) {
	$valor = $res[$i]['valor'];
	$descricao = $res[$i]['descricao'];
	$id = $res[$i]['id'];	
	$cliente = $res[$i]['cliente'];
	$vencimento = $res[$i]['data_venc'];
	$referencia = $res[$i]['referencia'];
	$id_ref = $res[$i]['id_ref'];
	$parcela = $res[$i]['parcela'];
	$cobrar_sempre = $res[$i]['cobrar_sempre'];

	if($referencia == 'Cobrança'){		
		$sql_consulta = 'cobrancas';
	}else{		
		$sql_consulta = 'emprestimos';
	}

	$query2 = $pdo->query("SELECT * FROM $sql_consulta where id = '$id_ref'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res2) > 0){
		$multa = @$res2[0]['multa'];
		$juros = @$res2[0]['juros'];	
	}else{
		$multa = $multa_sistema;
		$juros = $juros_sistema;	
	}

	$valor_multa = $multa;

//calcular quanto dias está atrasado
	$data_atual = date('Y-m-d');
	$data_inicio = new DateTime($vencimento);
	$data_fim = new DateTime($data_atual);
	$dateInterval = $data_inicio->diff($data_fim);
	$dias_vencido = $dateInterval->days;

	$valor_juros = $dias_vencido * ($juros * $valor / 100);

	$valor_final = $valor_juros + $valor_multa + $valor;
	$valor_finalF = @number_format($valor_final, 2, ',', '.');
	$valor_jurosF = @number_format($valor_juros, 2, ',', '.');
	$valor_multaF = @number_format($valor_multa, 2, ',', '.');
	

	$vencimentoF = implode('/', array_reverse(@explode('-', $vencimento)));

	$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if (@count($res2) > 0) {
		$nome_cliente = $res2[0]['nome'];
		$telefone_cliente = $res2[0]['telefone'];
	} else {
		$nome_cliente = 'Sem Registro';
		$telefone_cliente = "";
	}


	$link_pgto = $url_sistema.'pagar/'.$id;

	


	$telefone_envio = '55' . preg_replace('/[ ()-]+/', '', $telefone_cliente);
	$mensagem = '💰 *' . $nome_sistema . '*%0A';
	$mensagem .= '_Sua conta Venceu_ %0A';

	$mensagem .= '*Descrição:* '.$descricao.' %0A';
	$mensagem .= '*Cliente:* '.$nome_cliente.' %0A';

	if($parcela > 0){
		$mensagem .= 'Parcela: *'.$parcela.'* %0A';
	}
	
	
	if($valor_multa > 0){
	$mensagem .= 'Multa Atraso: *R$ '.$valor_multaF.'* %0A';
	}

	if($valor_juros > 0){
		$mensagem .= 'Júros Atraso: *R$ '.$valor_jurosF.'* %0A';
		$mensagem .= 'Dias Atraso: *'.$dias_vencido.'* %0A';
	}

	$mensagem .= 'Valor: *R$ '.$valor_finalF.'* %0A';	
	$mensagem .= '*Vencimento:* '.$vencimentoF.' %0A%0A';	

	if($pix_sistema != ""){
	$mensagem .= '*Chave Pix:* %0A';
	$mensagem .= $pix_sistema;	
	}else{
		$mensagem .= '*Link Pagamento:* %0A';
		$mensagem .= $link_pgto;
	}	

	if($cobrar_automatico == 'Sim' or $cobrar_sempre == 'Sim'){

		require('texto.php');

		if(@$status_mensagem == "Mensagem enviada com sucesso." and $seletor_api == 'menuia'){
			$pdo->query("UPDATE receber SET data_alerta = curDate(), cobrar_sempre = 'Não' where id = '$id'");
		}

		if($seletor_api != 'menuia'){
			$pdo->query("UPDATE receber SET data_alerta = curDate(), cobrar_sempre = 'Não' where id = '$id'");
		}

	}

	



}

echo $contas_pagar_vencidas;

?>