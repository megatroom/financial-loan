<?php 
$pag = 'pagar';

if(@$pagar == 'ocultar'){
	echo "<script>window.location='../index.php'</script>";
    exit();
}

 ?>

<div class="main-page margin-mobile">

<div class="row">
	<div class="col-md-2">
		<a onclick="inserir()" type="button" class="btn btn-primary"><span class="fa fa-plus"></span> Despesa</a>
	</div>

	<div class="col-md-2">
		<i class="fa fa-calendar-o text-primary"></i>
		<input style="width:130px; height:30px; font-size: 14px" type="date" name="dataInicial" id="dataInicial" value="<?php echo $data_mes ?>" onchange="buscar()">
	</div>

	<div class="col-md-2">
		<i class="fa fa-calendar-o text-primary"></i>
		<input style="width:130px; height:30px; font-size: 14px" type="date" name="dataFinal" id="dataFinal" value="<?php echo $data_final_mes ?>" onchange="buscar()">
	</div>

	<div style="margin-left: 30px" class="col-md-2" align="right">		
		<select class="form-control" id="status" onchange="buscar()">
			<option value="">Todas as contas</option>
			<option value="Não">Pendentes</option>
			<option value="Sim">Pagas</option>
		</select>
	</div>


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
								<input type="text" class="form-control" id="valor" name="valor" placeholder="Valor" required onkeyup="mascara_valor('valor')">							
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
			function carregarImgArquivos() {
				var target = document.getElementById('target-arquivos');
				var file = document.querySelector("#arquivo_conta").files[0];

				var arquivo = file['name'];
				resultado = arquivo.split(".", 2);

				if(resultado[1] === 'pdf'){
					$('#target-arquivos').attr('src', "images/pdf.png");
					return;
				}

				if(resultado[1] === 'rar' || resultado[1] === 'zip'){
					$('#target-arquivos').attr('src', "images/rar.png");
					return;
				}

				if(resultado[1] === 'doc' || resultado[1] === 'docx' || resultado[1] === 'txt'){
					$('#target-arquivos').attr('src', "images/word.png");
					return;
				}


				if(resultado[1] === 'xlsx' || resultado[1] === 'xlsm' || resultado[1] === 'xls'){
					$('#target-arquivos').attr('src', "images/excel.png");
					return;
				}


				if(resultado[1] === 'xml'){
					$('#target-arquivos').attr('src', "images/xml.png");
					return;
				}



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
	function listarArquivos(){
		var id = $('#id_arquivo').val(); 

		 $.ajax({
	        url: 'paginas/' + pag + "/listar_arquivos.php",
	        method: 'POST',
	        data: {id},
	        dataType: "html",

	        success:function(result){
	            $("#listar_arquivos").html(result);
	           
	        }
	    });

	}
</script>



<script type="text/javascript">
	

$("#form-arquivos").submit(function () {

    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'paginas/' + pag + "/arquivos.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem_arquivo').text('');
            $('#mensagem_arquivo').removeClass()
            if (mensagem.trim() == "Inserido com Sucesso") {

            	$('#nome_arq').val('');
				$('#arquivo_conta').val('');
				$('#target-arquivos').attr('src','images/arquivos/sem-foto.png');
                //$('#btn-fechar-arquivos').click();
                listarArquivos();          

            } else {

                $('#mensagem_arquivo').addClass('text-danger')
                $('#mensagem_arquivo').text(mensagem)
            }


        },

        cache: false,
        contentType: false,
        processData: false,

    });

});
</script>





<script type="text/javascript">
	function buscar(){
		var dataInicial = $('#dataInicial').val();
		var dataFinal = $('#dataFinal').val();
		var status = $('#status').val();
		listar(dataInicial, dataFinal, status)
	}
</script>


<script type="text/javascript">
	function baixar(id){	
    $('#mensagem-excluir').text('Baixando...')
    
    $.ajax({
        url: 'paginas/' + pag + "/baixar.php",
        method: 'POST',
        data: {id},
        dataType: "html",

        success:function(mensagem){
            if (mensagem.trim() == "Baixado com Sucesso") {         	
                listar();
            } else {
                $('#mensagem-excluir').addClass('text-danger')
                $('#mensagem-excluir').text(mensagem)
            }
        }
    });
}

</script>