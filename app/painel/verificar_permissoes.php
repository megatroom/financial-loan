<?php 
//require_once("../conexao.php");
@session_start();
$id_usuario = $_SESSION['id'];

$home = 'ocultar';
$configuracoes = 'ocultar';
$emprestimos = 'ocultar';
$cobrancas = 'ocultar';
$verificar_pgtos = 'ocultar';
$gestao_mensagens = 'ocultar';
$dispositivos = 'ocultar';

//grupo pessoas
$usuarios = 'ocultar';
$clientes = 'ocultar';

//grupo cadastros
$grupo_acessos = 'ocultar';
$acessos = 'ocultar';
$formas_pgto = 'ocultar';
$frequencias = 'ocultar';
$feriados = 'ocultar';

//grupo financeiro
$pagar = 'ocultar';
$receber = 'ocultar';
$receber_vencidas = 'ocultar';
$relatorios_financeiro = 'ocultar';
$relatorios_debitos = 'ocultar';
$relatorios_lucros = 'ocultar';
$relatorios_caixa = 'ocultar';
$editar_contas = 'ocultar';
$relatorios_ina = 'ocultar';


$query = $pdo->query("SELECT * FROM usuarios_permissoes where usuario = '$id_usuario'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){
	for($i=0; $i < $total_reg; $i++){
		foreach ($res[$i] as $key => $value){}
		$permissao = $res[$i]['permissao'];

		$query2 = $pdo->query("SELECT * FROM acessos where id = '$permissao'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$nome = $res2[0]['nome'];
		$chave = $res2[0]['chave'];
		$id = $res2[0]['id'];

		if($chave == 'home'){
			$home = '';
		}

		if($chave == 'configuracoes'){
			$configuracoes = '';
		}

		if($chave == 'emprestimos'){
			$emprestimos = '';
		}

		if($chave == 'cobrancas'){
			$cobrancas = '';
		}

		if($chave == 'verificar_pgtos'){
			$verificar_pgtos = '';
		}

		if($chave == 'gestao_mensagens'){
			$gestao_mensagens = '';
		}

		if($chave == 'dispositivos'){
			$dispositivos = '';
		}




		if($chave == 'usuarios'){
			$usuarios = '';
		}


		if($chave == 'clientes'){
			$clientes = '';
		}


		if($chave == 'grupo_acessos'){
			$grupo_acessos = '';
		}

		if($chave == 'acessos'){
			$acessos = '';
		}

		if($chave == 'formas_pgto'){
			$formas_pgto = '';
		}

		if($chave == 'frequencias'){
			$frequencias = '';
		}

		if($chave == 'feriados'){
			$feriados = '';
		}



		if($chave == 'pagar'){
			$pagar = '';
		}

		if($chave == 'receber'){
			$receber = '';
		}

		if($chave == 'receber_vencidas'){
			$receber_vencidas = '';
		}

		if($chave == 'relatorios_financeiro'){
			$relatorios_financeiro = '';
		}

		if($chave == 'relatorios_lucros'){
			$relatorios_lucros = '';
		}

		if($chave == 'relatorios_debitos'){
			$relatorios_debitos = '';
		}

		if($chave == 'relatorios_caixa'){
			$relatorios_caixa = '';
		}

		if($chave == 'editar_contas'){
			$editar_contas = '';
		}

		if($chave == 'relatorios_ina'){
			$relatorios_ina = '';
		}





	}

}



$pag_inicial = '';
if($home != 'ocultar'){
	$pag_inicial = 'home';
}else{
	$query = $pdo->query("SELECT * FROM usuarios_permissoes where usuario = '$id_usuario'");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = @count($res);	
	if($total_reg > 0){
		for($i=0; $i<$total_reg; $i++){
			$permissao = $res[$i]['permissao'];		
			$query2 = $pdo->query("SELECT * FROM acessos where id = '$permissao'");
			$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
			if($res2[0]['pagina'] == 'Não'){
				continue;
			}else{
				$pag_inicial = $res2[0]['chave'];
				break;
			}	
				
		}
				

	}else{
		echo 'Você não tem permissão para acessar nenhuma página, acione o administrador!';
		exit();
	}
}



if($usuarios == 'ocultar' and $clientes == 'ocultar'){
	$menu_pessoas = 'ocultar';
}else{
	$menu_pessoas = '';
}


if($grupo_acessos == 'ocultar' and $acessos == 'ocultar' and $formas_pgto == 'ocultar' and $frequencias == 'ocultar' and $feriados == 'ocultar'){
	$menu_cadastros = 'ocultar';
}else{
	$menu_cadastros = '';
}


if($pagar == 'ocultar' and $receber == 'ocultar' and $receber_vencidas == 'ocultar' and $relatorios_financeiro == 'ocultar' and $relatorios_lucros == 'ocultar' and $relatorios_debitos == 'ocultar' and $relatorios_caixa == 'ocultar' and $relatorios_ina == 'ocultar'){
	$menu_financeiro = 'ocultar';
}else{
	$menu_financeiro = '';
}



?>