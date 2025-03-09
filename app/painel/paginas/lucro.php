<?php 
$pag = 'lucro';


if(@$lucro == 'ocultar'){
	echo "<script>window.location='../index.php'</script>";
	exit();
}


?>

<div class="main-page margin-mobile">

	<div class="row">

		<form method="POST" action="rel/lucro_class.php" target="_blank">
			<div class="col-md-2">
				<i class="fa fa-calendar-o text-primary"></i>
				<input style="width:130px; height:30px; font-size: 14px" type="date" name="dataInicial" id="dataInicial" value="<?php echo $data_mes ?>" onchange="buscar()">
			</div>

			<div class="col-md-2">
				<i class="fa fa-calendar-o text-primary"></i>
				<input style="width:130px; height:30px; font-size: 14px" type="date" name="dataFinal" id="dataFinal" value="<?php echo $data_final_mes ?>" onchange="buscar()">
			</div>

			<div style="margin-left: 30px" class="col-md-2 " align="right">		
				<select class="sel2" name="cliente" id="cliente" style="width:100%;" onchange="buscar()"> 






					<option value="">Todos os Clientes</option>
					<?php 

				
						$query = $pdo->query("SELECT * FROM clientes order by nome asc");

						$res = $query->fetchAll(PDO::FETCH_ASSOC);


					for($i=0; $i < @count($res); $i++){

						foreach ($res[$i] as $key => $value){}



							?>	

						<option value="<?php echo $res[$i]['id'] ?>"><?php echo $res[$i]['nome'] ?> </option>



					<?php } ?>



				</select>
			</div>

			<?php if(@$_SESSION['nivel'] == 'Administrador'){ ?>
				<div style="margin-left: 30px" class="col-md-2 " align="right">

					<select class=" form-control sel8" name="corretor" id="corretor" style="width:100%;" onchange="buscar()"> 






						<option value="">Todos os corretores</option>
						<?php 

						if(@$_SESSION['nivel'] == 'Administrador'){

							$query = $pdo->query("SELECT * FROM usuarios order by nome asc");

							$res = $query->fetchAll(PDO::FETCH_ASSOC);
						}

						for($i=0; $i < @count($res); $i++){

							foreach ($res[$i] as $key => $value){}



								?>	

							<option value="<?php echo $res[$i]['id'] ?>"><?php echo $res[$i]['nome'] ?> </option>



						<?php } ?>



					</select>
				</div>
			<?php } ?>


			<div class="col-md-2" align="right">
				<button  type="submit" class="btn btn-success"><span class="fa fa-plus"></span> Relatório</button>
			</div>
		</form>


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














<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>



<script type="text/javascript">
	$(document).ready(function() {
		$('.sel2').select2({
			//dropdownParent: $('#modalForm')
		});
	});
</script>



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
		var cliente = $('#cliente').val();
		var corretor = $('#corretor').val();
		
		listar(dataInicial, dataFinal, cliente, corretor)
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
					buscar();
				} else {
					$('#mensagem-excluir').addClass('text-danger')
					$('#mensagem-excluir').text(mensagem)
				}
			}
		});
	}

</script>

<script type="text/javascript">
	
	$("#form").submit(function () {

		$('#mensagem').text('Salvando!!!');

		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: 'paginas/' + pag + "/salvar.php",
			type: 'POST',
			data: formData,

			success: function (mensagem) {
				$('#mensagem').text('');
				$('#mensagem').removeClass()
				if (mensagem.trim() == "Salvo com Sucesso") {

					$('#btn-fechar').click();
					buscar();          

				} else {

					$('#mensagem').addClass('text-danger')
					$('#mensagem').text(mensagem)
				}


			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});




	function excluir(id){	
		$('#mensagem-excluir').text('Excluindo...')

		$.ajax({
			url: 'paginas/' + pag + "/excluir.php",
			method: 'POST',
			data: {id},
			dataType: "html",

			success:function(mensagem){
				if (mensagem.trim() == "Excluído com Sucesso") {            	
					buscar();
				} else {
					$('#mensagem-excluir').addClass('text-danger')
					$('#mensagem-excluir').text(mensagem)
				}
			}
		});
	}


</script>