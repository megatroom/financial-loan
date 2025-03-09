<?php 
$pag = 'receber';

	//verificar se tem conta paga
	$query = $pdo->query("SELECT * from receber where pago != 'Sim' and ref_pix != '' and cliente = '$id_usuario'");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$linhas = @count($res);
	if($linhas > 0){
		for($i=0; $i<$linhas; $i++){
		$ref_pix = $res[$i]['ref_pix'];	
	        require('../painel/pagamentos/consultar_pagamento.php');      
		}
	}



 ?>

<div class="main-page margin-mobile">

<div class="row">
	
	<button onclick="buscar('')" style="background:#8b8a8a ; width:110px; color:#FFF" class="btn btn-secondary">Todas</button>
	<button onclick="buscar('Sim')" style="width:110px;" class="btn btn-success">Pagas</button>
	<button onclick="buscar('Não')" style="width:110px;" class="btn btn-danger">Pendentes</button>

</div>




<li class="dropdown head-dpdn2" style="display: inline-block;">		
		<a href="#" data-toggle="dropdown"  class="btn btn-danger dropdown-toggle" id="btn-deletar" style="display:none"><span class="fa fa-trash-o"></span> Deletar</a>

		<ul class="dropdown-menu">
		<li>
		<div class="notification_desc2">
		<p>Excluir Selecionados? <a href="#" onclick="deletarSel()"><span class="text-danger">Sim</span></a></p>
		</div>
		</li>										
		</ul>
</li>

<div class="bs-example widget-shadow" style="padding:15px;margin-top:0px" id="listar">

</div>

</div>

<input type="hidden" id="ids">

<!-- Modal Perfil -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_inserir"></span></h4>
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form">
			<div class="modal-body">
				

					<div class="row">
						<div class="col-md-5">							
								<label>Descrição</label>
								<input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição da Conta" required>							
						</div>

						<div class="col-md-3">							
								<label>Valor</label>
								<input type="text" class="form-control" id="valor" name="valor" placeholder="Valor" required>							
						</div>

						<div class="col-md-4">							
								<label>Vencimento</label>
								<input type="date" class="form-control" id="data_venc" name="data_venc" value="<?php echo $data_atual ?>" required>							
						</div>



						
					</div>


					
					<div class="row">

						<div class="col-md-12">							
								<label>Observações</label>
								<input type="text" class="form-control" id="obs" name="obs" placeholder="Observações" >							
						</div>
					</div>


					


					


					<input type="hidden" class="form-control" id="id" name="id">					

				<br>
				<small><div id="mensagem" align="center"></div></small>
			</div>
			<div class="modal-footer">       
				<button type="submit" class="btn btn-primary">Salvar</button>
			</div>
			</form>
		</div>
	</div>
</div>






	<!-- Modal Arquivos -->
	<div class="modal fade" id="modalArquivos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="tituloModal">Gestão de Arquivos - <span id="nome_arquivo"> </span></h4>
					<button id="btn-fechar-arquivos" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id="form-arquivos" method="post">
					<div class="modal-body">

						<div class="row">
							<div class="col-md-8">						
								<div class="form-group"> 
									<label>Arquivo</label> 
									<input class="form-control" type="file" name="arquivo_conta" onChange="carregarImgArquivos();" id="arquivo_conta">
								</div>	
							</div>
							<div class="col-md-4" style="margin-top:-10px">	
								<div id="divImgArquivos">
									<img src="images/arquivos/sem-foto.png"  width="60px" id="target-arquivos">									
								</div>					
							</div>




						</div>

						<div class="row" style="margin-top:-40px">
							<div class="col-md-8">
								<input type="text" class="form-control" name="nome_arq"  id="nome_arq" placeholder="Nome do Arquivo * " required>
							</div>

							<div class="col-md-4">										 
								<button type="submit" class="btn btn-primary">Inserir</button>
							</div>
						</div>

						<hr>

						<small><div id="listar_arquivos"></div></small>

						<br>
						<small><div align="center" id="mensagem_arquivo"></div></small>

						<input type="hidden" class="form-control" name="id_arquivo"  id="id_arquivo">


					</div>
				</form>
			</div>
		</div>
</div>







<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>


<script type="text/javascript">
	function buscar(p){		
		listar(p)
	}
</script>

