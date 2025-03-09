<?php 
@session_start();
require_once("../conexao.php");
require_once("verificar.php");

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

$pag_inicial = 'home';
if(@$_SESSION['nivel'] != 'Administrador'){
	require_once("verificar_permissoes.php");
}

if(@$_GET['pagina'] != ""){
	$pagina = @$_GET['pagina'];
}else{
	$pagina = $pag_inicial;
}

$id_usuario = @$_SESSION['id'];
$query = $pdo->query("SELECT * from usuarios where id = '$id_usuario'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
	$nome_usuario = $res[0]['nome'];
	$email_usuario = $res[0]['email'];
	$telefone_usuario = $res[0]['telefone'];
	$senha_usuario = $res[0]['senha'];
	$nivel_usuario = $res[0]['nivel'];
	$foto_usuario = $res[0]['foto'];
	$endereco_usuario = $res[0]['endereco'];
}

?>
<!DOCTYPE HTML>
<html>
<head>
	<title><?php echo $nome_sistema ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="shortcut icon" href="../img/icone.png" type="image/x-icon">

	<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>

	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />

	<!-- Custom CSS -->
	<link href="css/style.css" rel='stylesheet' type='text/css' />

	<!-- font-awesome icons CSS -->
	<link href="css/font-awesome.css" rel="stylesheet"> 
	<!-- //font-awesome icons CSS-->

	<!-- side nav css file -->
	<link href='css/SidebarNav.min.css' media='all' rel='stylesheet' type='text/css'/>
	<!-- //side nav css file -->

	<!-- js-->
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/modernizr.custom.js"></script>

	<!--webfonts-->
	<link href="//fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext,latin-ext" rel="stylesheet">
	<!--//webfonts--> 

	<!-- chart -->
	<script src="js/Chart.js"></script>
	<!-- //chart -->

	<!-- Metis Menu -->
	<script src="js/metisMenu.min.js"></script>
	<script src="js/custom.js"></script>
	<link href="css/custom.css" rel="stylesheet">
	<!--//Metis Menu -->
	<style>
		#chartdiv {
			width: 100%;
			height: 295px;
		}
	</style>
	<!--pie-chart --><!-- index page sales reviews visitors pie chart -->
	<script src="js/pie-chart.js" type="text/javascript"></script>
	<script type="text/javascript">

		$(document).ready(function () {
			$('#demo-pie-1').pieChart({
				barColor: '#2dde98',
				trackColor: '#eee',
				lineCap: 'butt',
				lineWidth: 8,
				onStep: function (from, to, percent) {
					$(this.element).find('.pie-value').text(Math.round(percent) + '%');
				}
			});

			$('#demo-pie-2').pieChart({
				barColor: '#a6210f',
				trackColor: '#eee',
				lineCap: 'butt',
				lineWidth: 8,
				onStep: function (from, to, percent) {
					$(this.element).find('.pie-value').text(Math.round(percent) + '%');
				}
			});

			$('#demo-pie-3').pieChart({
				barColor: '#ffc168',
				trackColor: '#eee',
				lineCap: 'butt',
				lineWidth: 8,
				onStep: function (from, to, percent) {
					$(this.element).find('.pie-value').text(Math.round(percent) + '%');
				}
			});


		});

	</script>
	<!-- //pie-chart --><!-- index page sales reviews visitors pie chart -->


<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/> <script src="DataTables/datatables.min.js"></script>





<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style type="text/css">
		.select2-selection__rendered {
			line-height: 36px !important;
			font-size:16px !important;
			color:#666666 !important;

		}

		.select2-selection {
			height: 36px !important;
			font-size:16px !important;
			color:#666666 !important;

		}
	</style>  

	
</head> 
<body class="cbp-spmenu-push">
	<div class="main-content" >
		<div class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
			<!--left-fixed -navigation-->
			<aside class="sidebar-left" style="overflow: scroll; height:100%; scrollbar-width: thin;">
				<nav class="navbar navbar-inverse">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".collapse" aria-expanded="false">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<h1><a class="navbar-brand" href="index.php"><span class="fa fa-usd"></span> Sistema<span class="dashboard_text"><?php echo $nome_sistema ?></span></a></h1>
					</div>
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="sidebar-menu">
							<li class="header">MENU NAVEGAÇÃO</li>
							<li class="treeview <?php echo @$home ?>">
								<a href="index.php">
									<i class="fa fa-dashboard"></i> <span>Home</span>
								</a>
							</li>

							<?php if($recursos != "Cobranças"){ ?>
							<li class="treeview <?php echo @$emprestimos ?>">
								<a href="emprestimos">
									<i class="fa fa-usd"></i> <span>Empréstimos</span>
								</a>
							</li>
						<?php } ?>

							<?php if($recursos != "Empréstimos"){ ?>
							<li class="treeview <?php echo @$cobrancas ?>">
								<a href="cobrancas">
									<i class="fa fa-usd"></i> <span>Cobranças Recorrentes</span>
								</a>
							</li>
						<?php } ?>

							<li class="treeview <?php echo @$clientes ?>">					
								<a href="clientes"><i class="fa fa-user"></i> Clientes</a>	
							</li>

							<li class="treeview <?php echo @$usuarios ?>">					<a href="usuarios"><i class="fa fa-users"></i> Usuários</a>	
							</li>

							

							<li class="treeview <?php echo @$menu_cadastros ?>">
								<a href="#">
									<i class="fa fa-plus"></i>
									<span>Cadastros</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li class="<?php echo @$grupo_acessos ?>"><a href="grupo_acessos"><i class="fa fa-angle-right"></i> Grupos</a></li>

									<li class="<?php echo @$acessos ?>"><a href="acessos"><i class="fa fa-angle-right"></i> Acessos</a></li>

									<li class="<?php echo @$formas_pgto ?>"><a href="formas_pgto"><i class="fa fa-angle-right"></i> Formas de Pagamento</a></li>

									<li class="<?php echo @$frequencias ?>"><a href="frequencias"><i class="fa fa-angle-right"></i> Frequências</a></li>

									<li class="<?php echo @$feriados ?>"><a href="feriados"><i class="fa fa-angle-right"></i> Feriados</a></li>
									
								</ul>
							</li>


							<li class="treeview <?php echo @$menu_financeiro ?>">
								<a href="#">
									<i class="fa fa-money"></i>
									<span>Financeiro</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li class="<?php echo @$pagar ?>"><a href="pagar"><i class="fa fa-angle-right"></i> Despesas / Saídas</a></li>

									<li class="<?php echo @$receber ?>"><a href="receber"><i class="fa fa-angle-right"></i> Entradas / Recebimentos</a></li>

									<li class="<?php echo @$receber_vencidas ?>"><a href="receber_vencidas"><i class="fa fa-angle-right"></i> Receber Vencidas</a></li>


									<li class="<?php echo @$relatorios_financeiro ?>"><a href="" data-toggle="modal" data-target="#modalRelFin"><i class="fa fa-angle-right"></i> Relatórios Financeiro</a></li>


										<li class="<?php echo @$relatorios_debitos ?>"><a href="rel/debitos_class.php" target="_blank"><i class="fa fa-angle-right"></i> Relatórios Débitos</a></li>

										<?php if($recursos != "Cobranças"){ ?>
										<li class="<?php echo @$relatorios_lucros ?>"><a href="lucro" ><i class="fa fa-angle-right"></i>Lucro Empréstimos</a></li>
									<?php } ?>


										<li class="<?php echo @$relatorios_caixa ?>"><a href="rel/caixa_class.php" target="_blank"><i class="fa fa-angle-right"></i>Relatório Diário Caixa</a></li>


										<li class="<?php echo @$relatorios_ina ?>"><a href="rel/sintetico_inadimplentes_class.php" target="_blank"><i class="fa fa-angle-right"></i>Relatório Inadimplêntes</a></li>
									
								</ul>
							</li>


							<li class="treeview <?php echo @$verificar_pgtos ?>">
								<a href="#" onclick="verificarPg()">
									<i class="fa fa-spinner"></i> <span>Verificar Pagamentos</span>
								</a>
							</li>


							<li class="treeview <?php echo @$gestao_mensagens ?>">
								<a href="#" onclick="verificarMensagens()">
									<i class="fa fa-whatsapp"></i> <span>Gestão Mensagens</span>
								</a>
							</li>


								<li class="treeview <?php echo @$dispositivos ?>">				
									<a href="dispositivos"><i class="fa fa-user"></i> Dispositivos</a>	
							</li>


						</ul>
					</div>
					<!-- /.navbar-collapse -->
				</nav>
			</aside>
		</div>
		<!--left-fixed -navigation-->
		
		<!-- header-starts -->
		<div class="sticky-header header-section " >
			<div class="header-left">
				<!--toggle button start-->
				<button id="showLeftPush" data-toggle="collapse" data-target=".collapse"><i class="fa fa-bars"></i></button>
				<!--toggle button end-->
				<div class="profile_details_left"><!--notifications of menu start -->

					<?php 
						//totalizar parcelas em atraso
						$query = $pdo->query("SELECT * from receber where data_venc < curDate() and referencia = 'Empréstimo' and pago != 'Sim' ");
						$res = $query->fetchAll(PDO::FETCH_ASSOC);
						$total_parcelas_vencidas = @count($res);
					 ?>
					<ul class="nofitications-dropdown">
						<li class="dropdown head-dpdn">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-envelope"></i><span class="badge"><?php echo $total_parcelas_vencidas ?></span></a>

							<a href="#" onclick="mostrar_valores()" class="dropdown-toggle" ><i class="fa fa-eye" style="color:#FFF"></i></a>

							<ul class="dropdown-menu">
								<li>
									<div class="notification_header">
										<h3>Existem <?php echo $total_parcelas_vencidas ?> parcelas vencidas.</h3>
									</div>
								</li>
								<li><a href="#">
									<div class="user_img"><img src="images/1.jpg" alt=""></div>

									<?php 
										$query = $pdo->query("SELECT * from receber where data_venc < curDate() and referencia = 'Empréstimo' and pago != 'Sim' order by id asc limit 10 ");
						$res = $query->fetchAll(PDO::FETCH_ASSOC);
						$linhas = @count($res);
						for($i=0; $i<$linhas; $i++){
	$valor = $res[$i]['valor'];
	$data = $res[$i]['data_venc'];
	$cliente = $res[$i]['cliente'];

	$query2 = $pdo->query("SELECT * from clientes where id = '$cliente'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = @$res2[0]['nome'];

	$dataF = implode('/', array_reverse(explode('-', $data)));
	
	$valorF = number_format($valor, 2, ',', '.');

									 ?>
									<div class="notification_desc">
										<small><span class="text-danger">R$ <?php echo $valorF ?></span> - <?php echo $dataF ?> (<span style="color:blue"><?php echo $nome_cliente ?></span>)</small>
										
									</div>

								<?php } ?>
									<div class="clearfix"></div>	
								</a></li>
								
								
								
								<li>
									<div class="notification_bottom">
										<a href="index.php?pagina=receber_vencidas">Ver todas as Parcelas</a>
									</div> 
								</li>
							</ul>
						</li>
						


					</ul>
					<div class="clearfix"> </div>
				</div>
				
			</div>
			<div class="header-right">

				<div class="profile_details">		
					<ul>
						<li class="dropdown profile_details_drop">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								<div class="profile_img">	
									<span class="prfil-img"><img src="images/perfil/<?php echo $foto_usuario ?>" alt="" width="50px" height="50px"> </span> 
									<div class="user-name esc">
										<p><?php echo $nome_usuario ?></p>
										<span><?php echo $nivel_usuario ?></span>
									</div>
									<i class="fa fa-angle-down lnr"></i>
									<i class="fa fa-angle-up lnr"></i>
									<div class="clearfix"></div>	
								</div>	
							</a>
							<ul class="dropdown-menu drp-mnu">
								<li class="<?php echo $configuracoes ?>"> <a href="" data-toggle="modal" data-target="#modalConfig"><i class="fa fa-cog"></i> Configurações</a> </li> 
								<li> <a href="" data-toggle="modal" data-target="#modalPerfil"><i class="fa fa-user"></i> Perfil</a> </li> 								
								<li> <a href="logout.php"><i class="fa fa-sign-out"></i> Sair</a> </li>
							</ul>
						</li>
					</ul>
				</div>
				<div class="clearfix"> </div>				
			</div>
			<div class="clearfix"> </div>	
		</div>
		<!-- //header-ends -->




		<!-- main content start-->
		<div id="page-wrapper" >
			<?php 
			require_once('paginas/'.$pagina.'.php');
			?>
		</div>

	
		<div style="height:100px; background: #f4f4f4; ">a</div>

	</div>

	<!-- new added graphs chart js-->
	
	<script src="js/Chart.bundle.js"></script>
	<script src="js/utils.js"></script>
	
	
	
	<!-- Classie --><!-- for toggle left push menu script -->
	<script src="js/classie.js"></script>
	<script>
		var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
		showLeftPush = document.getElementById( 'showLeftPush' ),
		body = document.body;

		showLeftPush.onclick = function() {
			classie.toggle( this, 'active' );
			classie.toggle( body, 'cbp-spmenu-push-toright' );
			classie.toggle( menuLeft, 'cbp-spmenu-open' );
			disableOther( 'showLeftPush' );
		};


		function disableOther( button ) {
			if( button !== 'showLeftPush' ) {
				classie.toggle( showLeftPush, 'disabled' );
			}
		}
	</script>
	<!-- //Classie --><!-- //for toggle left push menu script -->

	<!--scrolling js-->
	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>
	<!--//scrolling js-->
	
	<!-- side nav js -->
	<script src='js/SidebarNav.min.js' type='text/javascript'></script>
	<script>
		$('.sidebar-menu').SidebarNav()
	</script>
	<!-- //side nav js -->
	
	
	
	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.js"> </script>
	<!-- //Bootstrap Core JavaScript -->



	<!-- Mascaras JS -->
<script type="text/javascript" src="js/mascaras.js"></script>

<!-- Ajax para funcionar Mascaras JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script> 

	
</body>
</html>






<!-- Modal Perfil -->
<div class="modal fade" id="modalPerfil" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Alterar Dados</h4>
				<button id="btn-fechar-perfil" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form-perfil">
			<div class="modal-body">
				

					<div class="row">
						<div class="col-md-6">							
								<label>Nome</label>
								<input type="text" class="form-control" id="nome_perfil" name="nome" placeholder="Seu Nome" value="<?php echo $nome_usuario ?>" required>							
						</div>

						<div class="col-md-6">							
								<label>Email</label>
								<input type="email" class="form-control" id="email_perfil" name="email" placeholder="Seu Nome" value="<?php echo $email_usuario ?>" required>							
						</div>
					</div>


					<div class="row">
						<div class="col-md-4">							
								<label>Telefone</label>
								<input type="text" class="form-control" id="telefone_perfil" name="telefone" placeholder="Seu Telefone" value="<?php echo $telefone_usuario ?>" required>							
						</div>

						<div class="col-md-4">							
								<label>Senha</label>
								<input type="password" class="form-control" id="senha_perfil" name="senha" placeholder="Senha" value="<?php echo $senha_usuario ?>" required>							
						</div>

						<div class="col-md-4">							
								<label>Confirmar Senha</label>
								<input type="password" class="form-control" id="conf_senha_perfil" name="conf_senha" placeholder="Confirmar Senha" value="" required>							
						</div>

						
					</div>


					<div class="row">
						<div class="col-md-12">	
							<label>Endereço</label>
							<input type="text" class="form-control" id="endereco_perfil" name="endereco" placeholder="Seu Endereço" value="<?php echo $endereco_usuario ?>" >	
						</div>
					</div>
					


					<div class="row">
						<div class="col-md-8">							
								<label>Foto</label>
								<input type="file" class="form-control" id="foto_perfil" name="foto" value="<?php echo $foto_usuario ?>" onchange="carregarImgPerfil()">							
						</div>

						<div class="col-md-4">								
							<img src="images/perfil/<?php echo $foto_usuario ?>"  width="80px" id="target-usu">								
							
						</div>

						
					</div>


					<input type="hidden" name="id_usuario" value="<?php echo $id_usuario ?>">
				

				<br>
				<small><div id="msg-perfil" align="center"></div></small>
			</div>
			<div class="modal-footer">       
				<button type="submit" class="btn btn-primary">Salvar</button>
			</div>
			</form>
		</div>
	</div>
</div>








<!-- Modal Config -->
<div class="modal fade" id="modalConfig" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Editar Configurações</h4>
				<button id="btn-fechar-config" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form-config">
			<div class="modal-body">
				

					<div class="row">
						<div class="col-md-3">							
								<label>Nome do Projeto</label>
								<input type="text" class="form-control" id="nome_sistema" name="nome_sistema" placeholder="Delivery Interativo" value="<?php echo @$nome_sistema ?>" required>							
						</div>

						<div class="col-md-3">							
								<label>Email Sistema</label>
								<input type="email" class="form-control" id="email_sistema" name="email_sistema" placeholder="Email do Sistema" value="<?php echo @$email_sistema ?>" >							
						</div>


						<div class="col-md-3">							
								<label>Telefone Sistema</label>
								<input type="text" class="form-control" id="telefone_sistema" name="telefone_sistema" placeholder="Telefone do Sistema" value="<?php echo @$telefone_sistema ?>" required>							
						</div>

						<div class="col-md-3">					
								<label>CNPJ</label>
								<input type="text" class="form-control" id="cnpj_sistema" name="cnpj_sistema" placeholder="CNPJ da Empressa" value="<?php echo @$cnpj_sistema ?>">							
						</div>

					</div>


					<div class="row">
						<div class="col-md-4">							
								<label>Endereço <small>(Rua Número Bairro e Cidade)</small></label>
								<input type="text" class="form-control" id="endereco_sistema" name="endereco_sistema" placeholder="Rua X..." value="<?php echo @$endereco_sistema ?>" >							
						</div>

						<div class="col-md-2">							
								<label>Júros %</label>
								<input type="number" class="form-control" id="juros_emprestimo" name="juros_emprestimo" placeholder="Júros Final" value="<?php echo @$juros_emprestimo ?>">							
						</div>

						<div class="col-md-2">							
								<label>Múlta R$</label>
								<input type="text" class="form-control" id="multa_sistema" name="multa_sistema" placeholder="Valor Único Atraso R$" value="<?php echo @$multa_sistema ?>">							
						</div>

						<div class="col-md-2">							
								<label>Júros Dia %</label>
								<input type="text" class="form-control" id="juros_sistema" name="juros_sistema" placeholder="Júros Atraso por Dia %" value="<?php echo @$juros_sistema ?>">							
						</div>

						<div class="col-md-2">	
								<label>Marca D'agua</label>
								<select class="form-control" name="marca_dagua">
									<option value="Sim" <?php if(@$marca_dagua == 'Sim'){?> selected <?php } ?> >Sim</option>
									<option value="Não" <?php if(@$marca_dagua == 'Não'){?> selected <?php } ?> >Não</option>
								</select>							
						</div>
					</div>





					<div class="row">
						<div class="col-md-2">	
								<label>Taxa Sistema</label>
								<select class="form-control" name="taxa_sistema">
									<option value="Cliente" <?php if(@$taxa_sistema == 'Cliente'){?> selected <?php } ?> >Cliente</option>
									<option value="Empresa" <?php if(@$taxa_sistema == 'Empresa'){?> selected <?php } ?> >Empresa</option>
								</select>							
						</div>

						<div class="col-md-2">	
								<label>Selecionar Api</label>
								<select class="form-control" name="seletor_api">
									<option value="menuia" <?php if(@$seletor_api == 'menuia'){?> selected <?php } ?> >Menuia</option>
									<option value="wm" <?php if(@$seletor_api == 'wm'){?> selected <?php } ?> >WordMensagens</option>
								</select>							
						</div>


						<div class="col-md-4">					
								<label>Token (appkey)</label>
								<input type="text" class="form-control" id="token" name="token" placeholder="Api whatsapp" value="<?php echo @$token ?>">						
						</div>


						<div class="col-md-4">						
								<label>Instância (authkey)</label>
								<input type="text" class="form-control" id="instancia" name="instancia" placeholder="Api whatsapp" value="<?php echo @$instancia ?>">							
						</div>


					

						
						

						
					</div>


					<div class="row">
						<div class="col-md-3">	
								<label>Não Criar Parcelas</label>
								<select class="form-control" name="dias_criar_parcelas">
									<option value="Final de Semana" <?php if(@$dias_criar_parcelas == 'Final de Semana'){?> selected <?php } ?> >Sábados e Domingos</option>
									<option value="DomingoSegunda" <?php if(@$dias_criar_parcelas == 'DomingoSegunda'){?> selected <?php } ?> >Domingos e Segundas</option>
									<option value="Domingos" <?php if(@$dias_criar_parcelas == 'Domingos'){?> selected <?php } ?> >Somente Domingos</option>
									<option value="" <?php if(@$dias_criar_parcelas == ''){?> selected <?php } ?> >Criar Todos os Dias</option>
								</select>							
						</div>



					<div class="col-md-7">						
								<label>Chave Pix Sistema</label>
								<input type="text" class="form-control" id="pix_sistema" name="pix_sistema" placeholder="Deixar vazio se for usar Mercado Pago" value="<?php echo @$pix_sistema ?>">							
						</div>


						<div class="col-md-2">						
								<label>Saldo Inicial</label>
								<input type="text" class="form-control" id="saldo_inicial" name="saldo_inicial" placeholder="Saldo inicial sistema" value="<?php echo @$saldo_inicial ?>">							
						</div>						


					</div>


					<div class="row">
							<div class="col-md-3">					
								<label>Dias Alerta Lembrete</label>
								<input type="number" class="form-control" id="dias_aviso" name="dias_aviso" placeholder="Lembrar o Vencimento dias antes" value="<?php echo @$dias_aviso ?>">							
						</div>


						<div class="col-md-3">					
								<label>Recursos Sistema</label>
								<select class="form-control" name="recursos">
									<option value="Empréstimos e Cobranças" <?php if(@$recursos == 'Empréstimos e Cobranças'){?> selected <?php } ?> >Empréstimos e Cobranças</option>
									<option value="Empréstimos" <?php if(@$recursos == 'Empréstimos'){?> selected <?php } ?> >Somente Empréstimos</option>
									<option value="Cobranças" <?php if(@$recursos == 'Cobranças'){?> selected <?php } ?> >Somente Cobranças</option>
									
								</select>							
						</div>

						<div class="col-md-3">					
								<label>Cobrar Automáticamente</label>
								<select class="form-control" name="cobrar_automatico">
									<option value="Sim" <?php if(@$cobrar_automatico == 'Sim'){?> selected <?php } ?> >Sim</option>
									<option value="Não" <?php if(@$cobrar_automatico == 'Não'){?> selected <?php } ?> >Não</option>
								</select>							
						</div>

					</div>


					<div class="row">
						<div class="col-md-6">					
								<label>Public Key (Mercado Pago)</label>
								<input type="text" class="form-control" id="token" name="public_key" placeholder="Public Key Mercado Pago" value="<?php echo @$public_key ?>">						
						</div>


						<div class="col-md-6">						
								<label>Access Token (Mercado Pago)</label>
								<input type="text" class="form-control" id="instancia" name="access_token" placeholder="Access Token Mercado Pago" value="<?php echo @$access_token ?>">							
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">						
								<div class="form-group"> 
									<label>Logo (*PNG)</label> 
									<input class="form-control" type="file" name="foto-logo" onChange="carregarImgLogo();" id="foto-logo">
								</div>						
							</div>
							<div class="col-md-2">
								<div id="divImg">
									<img src="../img/<?php echo $logo_sistema ?>"  width="80px" id="target-logo">									
								</div>
							</div>


							<div class="col-md-4">						
								<div class="form-group"> 
									<label>Ícone (*Png)</label> 
									<input class="form-control" type="file" name="foto-icone" onChange="carregarImgIcone();" id="foto-icone">
								</div>						
							</div>
							<div class="col-md-2">
								<div id="divImg">
									<img src="../img/<?php echo $icone_sistema ?>"  width="50px" id="target-icone">									
								</div>
							</div>

						
					</div>




					<div class="row">
							<div class="col-md-4">						
								<div class="form-group"> 
									<label>Logo Relatório (*Jpg)</label> 
									<input class="form-control" type="file" name="foto-logo-rel" onChange="carregarImgLogoRel();" id="foto-logo-rel">
								</div>						
							</div>
							<div class="col-md-2">
								<div id="divImg">
									<img src="../img/<?php echo @$logo_rel ?>"  width="80px" id="target-logo-rel">									
								</div>
							</div>



								<div class="col-md-4">						
								<div class="form-group"> 
									<label>Assinatura Recibo (*Jpg)</label> 
									<input class="form-control" type="file" name="foto-assinatura" onChange="carregarImgAssinatura();" id="foto-assinatura">
								</div>						
							</div>
							<div class="col-md-2">
								<div id="divImg">
									<img src="../img/<?php echo @$assinatura ?>"  width="80px" id="target-assinatura">									
								</div>
							</div>

						
					</div>					
				

				<br>
				<small><div id="msg-config" align="center"></div></small>
			</div>
			<div class="modal-footer">       
				<button type="submit" class="btn btn-primary">Salvar</button>
			</div>
			</form>
		</div>
	</div>
</div>





<!-- Modal Rel Financeiro -->
<div class="modal fade" id="modalRelFin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Relatório Financeiro</h4>
				<button id="btn-fechar-rel" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="POST" action="rel/financeiro_class.php" target="_blank">
			<div class="modal-body">	
			<div class="row">
				<div class="col-md-4">
					<label>Data Inicial</label>
					<input type="date" name="dataInicial" class="form-control" value="<?php echo $data_atual ?>">
				</div>

				<div class="col-md-4">
					<label>Data Final</label>
					<input type="date" name="dataFinal" class="form-control" value="<?php echo $data_atual ?>">
				</div>

				<div class="col-md-4">
					<label>Filtro Data</label>
					<select name="filtro_data" class="form-control">
						<option value="data">Data de Lançamento</option>
						<option value="data_venc">Data de Vencimento</option>
						<option value="data_pgto">Data de Pagamento</option>
					</select>
				</div>
			</div>		


			<div class="row">				
				<div class="col-md-4">
					<label>Entradas / Saídas</label>
					<select name="filtro_tipo" class="form-control">
						<option value="receber">Entradas / Ganhos</option>
						<option value="pagar">Saídas / Despesas</option>
					</select>
				</div>

				<div class="col-md-4">
					<label>Tipo Lançamento</label>
					<select name="filtro_lancamento" class="form-control">
						<option value="">Tudo</option>
						<option value="Conta">Despesas</option>
						<option value="Empréstimo">Empréstimos</option>
						<option value="Cobrança">Cobranças Recorrentes</option>
						
					</select>
				</div>
				<div class="col-md-4">
					<label>Pendentes / Pago</label>
					<select name="filtro_pendentes" class="form-control">
						<option value="">Tudo</option>
						<option value="Não">Pendentes</option>
						<option value="Sim">Pago</option>
					</select>
				</div>			
			</div>		
				
						

			</div>
			<div class="modal-footer">       
				<button type="submit" class="btn btn-primary">Gerar</button>
			</div>
			</form>
		</div>
	</div>
</div>









<!-- Modal Verificar pgtos pendentes -->
<div class="modal fade" id="modalVerificar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Verificando Pagamentos</h4>
				<button id="btn-fechar-pgtos" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">	
				<div id="verificar_pagamentos">
					<div align="center" id="loading_img"><img src="images/loading.gif"></div>
					<div align="center" id="textos_verificar" style="display:none"></div>
				</div>

				
			</div>						
	
			
		</div>
	</div>
</div>






<!-- Modal Mensagens -->
<div class="modal fade" id="modalMensagens" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Gerenciar Mensagens</h4>
				<button id="btn-fechar-mensagens" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">	
				<div id="">					
					<div align="center" id="textos_verificar_mensagens"></div>
				</div>

				<div style="margin-top: 20px" class="row">
					<div class="col-md-6" align="center" >
							
							<li class="dropdown head-dpdn2" style="display: inline-block;">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><button class="btn btn-danger">Cobrar Vencidas</button></a>

							<ul class="dropdown-menu" style="margin-left:-50px;">
							<li>
							<div class="notification_desc2">
							<p>Gerar Cobranças? <a href="#" onclick="cobrarTodos()"><span class="text-danger">Sim</span></a></p>
							</div>
							</li>										
							</ul>
							</li>
						
					</div>

					<div class="col-md-6" align="center" style="padding:0">
						<li class="dropdown head-dpdn2" style="display: inline-block;">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><button class="btn btn-primary">Lembrete Hoje</button></a>

							<ul class="dropdown-menu" style="margin-left:-50px;">
							<li>
							<div class="notification_desc2">
							<p>Mandar Lembretes dos vencimentos de Hoje? <a href="#" onclick="lembretesTodos()"><span class="text-primary">Sim</span></a></p>
							</div>
							</li>										
							</ul>
							</li>
					</div>

					
				</div>

				<div style="margin-top: 20px" class="row" align="center" id="mensagem_retornos">

				</div>
			</div>						
	
			
		</div>
	</div>
</div>






<script type="text/javascript">
	function carregarImgPerfil() {
    var target = document.getElementById('target-usu');
    var file = document.querySelector("#foto_perfil").files[0];
    
        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }
</script>






 <script type="text/javascript">
	$("#form-perfil").submit(function () {

		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: "editar-perfil.php",
			type: 'POST',
			data: formData,

			success: function (mensagem) {
				$('#msg-perfil').text('');
				$('#msg-perfil').removeClass()
				if (mensagem.trim() == "Editado com Sucesso") {

					$('#btn-fechar-perfil').click();
					location.reload();				
						

				} else {

					$('#msg-perfil').addClass('text-danger')
					$('#msg-perfil').text(mensagem)
				}


			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>






 <script type="text/javascript">
	$("#form-config").submit(function () {

		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: "editar-config.php",
			type: 'POST',
			data: formData,

			success: function (mensagem) {
				$('#msg-config').text('');
				$('#msg-config').removeClass()
				if (mensagem.trim() == "Editado com Sucesso") {

					$('#btn-fechar-config').click();
					location.reload();				
						

				} else {

					$('#msg-config').addClass('text-danger')
					$('#msg-config').text(mensagem)
				}


			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>




<script type="text/javascript">
	function carregarImgLogo() {
    var target = document.getElementById('target-logo');
    var file = document.querySelector("#foto-logo").files[0];
    
        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }
</script>





<script type="text/javascript">
	function carregarImgLogoRel() {
    var target = document.getElementById('target-logo-rel');
    var file = document.querySelector("#foto-logo-rel").files[0];
    
        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }
</script>





<script type="text/javascript">
	function carregarImgIcone() {
    var target = document.getElementById('target-icone');
    var file = document.querySelector("#foto-icone").files[0];
    
        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }
</script>


<script type="text/javascript">
	function verificarPg(){
		$('#modalVerificar').modal('show');
		$('#loading_img').show();
		$('#textos_verificar').hide();

		$.ajax({
        url: 'verificar_pgtos.php',
        method: 'POST',
        data: {},
        dataType: "html",

        success:function(mensagem){
            $('#loading_img').hide();
            $('#textos_verificar').html(mensagem);
            $('#textos_verificar').show();
        }

	});

	}
</script>



<script type="text/javascript">
	function verificarMensagens(){
		$('#modalMensagens').modal('show');
		$('#mensagem_retornos').html('');
		
		$.ajax({
        url: 'verificar_mensagens.php',
        method: 'POST',
        data: {},
        dataType: "html",

        success:function(mensagem){           
            $('#textos_verificar_mensagens').html(mensagem);
           
        }

	});

	}
</script>




<script type="text/javascript">
	function cobrarTodos(){
		
		$('#mensagem_retornos').html('Enviando as Cobranças, pode demorar um pouco, aguarde!!');
		
		$.ajax({
        url: 'cobrar_todos.php',
        method: 'POST',
        data: {},
        dataType: "html",

        success:function(mensagem){           
            $('#mensagem_retornos').html(mensagem);
           
        }

	});

	}
</script>




<script type="text/javascript">
	function lembretesTodos(){
		
		$('#mensagem_retornos').html('Enviando os Lembretes de Vencimentos de Hoje, pode demorar um pouco, aguarde!!');
		
		$.ajax({
        url: 'lembretes_todos.php',
        method: 'POST',
        data: {},
        dataType: "html",

        success:function(mensagem){           
            $('#mensagem_retornos').html(mensagem);
           
        }

	});

	}
</script>



<script type="text/javascript">
	function cancelarMensagens(){
		
		$('#mensagem_retornos').html('Cancelando as mensagens que estão na api do whatsapp aguardando envio!!');
		
		$.ajax({
        url: 'cancelar_mensagens.php',
        method: 'POST',
        data: {},
        dataType: "html",

        success:function(mensagem){           
            $('#mensagem_retornos').html(mensagem);
           
        }

	});

	}


	
</script>




<script type="text/javascript">
	function carregarImgAssinatura() {
    var target = document.getElementById('target-assinatura');
    var file = document.querySelector("#foto-assinatura").files[0];
    
        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }
</script>