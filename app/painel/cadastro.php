<?php require_once("../conexao.php"); ?>
<!DOCTYPE HTML>
<html>
<head>
	<title><?php echo $nome_sistema ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="shortcut icon" href="img/icone.png" type="image/x-icon">

	
	<!-- Bootstrap Core CSS -->
	<link href="painel/css/bootstrap.css" rel='stylesheet' type='text/css' />

	

	<!-- font-awesome icons CSS -->
	<link href="painel/css/font-awesome.css" rel="stylesheet"> 
	<!-- //font-awesome icons CSS-->

	<!-- side nav css file -->
	<link href='painel/css/SidebarNav.min.css' media='all' rel='stylesheet' type='text/css'/>
	<!-- //side nav css file -->

	<!-- js-->
	<script src="painel/js/jquery-1.11.1.min.js"></script>
	<script src="painel/js/modernizr.custom.js"></script>

	<!--webfonts-->
	<link href="//fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext,latin-ext" rel="stylesheet">
	<!--//webfonts--> 

	<!-- chart -->
	<script src="painel/js/Chart.js"></script>
	<!-- //chart -->

	

	</head> 
<body class="cbp-spmenu-push">
	<div class="container" style="margin-top:20px">
		<form id="form">
			<div class="modal-body">
				

					<div class="row">
						<div class="col-md-6" style="margin-bottom:10px">							
								<label>Nome</label>
								<input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" required>							
						</div>

						<div class="col-md-6" style="margin-bottom:10px">							
								<label>Email</label>
								<input type="email" class="form-control" id="email" name="email" placeholder="Email" >							
						</div>

						
					</div>


					<div class="row">

						<div class="col-md-4" style="margin-bottom:10px">							
								<label>Telefone</label>
								<input type="text" class="form-control" id="telefone" name="telefone" placeholder="Telefone" required>							
						</div>


						<div class="col-md-4 " style="margin-bottom:10px">							
								<label>CPF</label>
								<input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF" required>							
						</div>
						

						<div class="col-md-4 " style="margin-bottom:10px">							
								<label>Data Nascimento</label>
								<input type="text" class="form-control" id="data_nasc" name="data_nasc" placeholder="dd/mm/aaaa">							
						</div>


						
				

						<div class="col-md-3 " style="margin-bottom:10px">							
								<label>CEP</label>
								<input type="text" class="form-control" id="cep" name="cep" placeholder="CEP" onblur="pesquisacep(this.value);">							
						</div>

						<div class="col-md-9" style="margin-bottom:10px">							
								<label>Endereço</label>
								<input type="text" class="form-control" id="endereco" name="endereco" placeholder="Rua e Número" >							
						</div>
					</div>


					<div class="row">
						<div class="col-md-5" style="margin-bottom:10px">							
								<label>Bairro</label>
								<input type="text" class="form-control" id="bairro" name="bairro" placeholder="Bairro" >							
						</div>

						<div class="col-md-4 col-xs-7" style="margin-bottom:10px">							
								<label>Cidade</label>
								<input type="text" class="form-control" id="cidade" name="cidade" placeholder="Cidade" >							
						</div>

						<div class="col-md-3 col-xs-5" style="margin-bottom:10px">							
								<label>Estado</label>
								<select class="form-control" id="estado" name="estado">
									<option value="">Selecionar</option>
    <option value="AC">Acre</option>
    <option value="AL">Alagoas</option>
    <option value="AP">Amapá</option>
    <option value="AM">Amazonas</option>
    <option value="BA">Bahia</option>
    <option value="CE">Ceará</option>
    <option value="DF">Distrito Federal</option>
    <option value="ES">Espírito Santo</option>
    <option value="GO">Goiás</option>
    <option value="MA">Maranhão</option>
    <option value="MT">Mato Grosso</option>
    <option value="MS">Mato Grosso do Sul</option>
    <option value="MG">Minas Gerais</option>
    <option value="PA">Pará</option>
    <option value="PB">Paraíba</option>
    <option value="PR">Paraná</option>
    <option value="PE">Pernambuco</option>
    <option value="PI">Piauí</option>
    <option value="RJ">Rio de Janeiro</option>
    <option value="RN">Rio Grande do Norte</option>
    <option value="RS">Rio Grande do Sul</option>
    <option value="RO">Rondônia</option>
    <option value="RR">Roraima</option>
    <option value="SC">Santa Catarina</option>
    <option value="SP">São Paulo</option>
    <option value="SE">Sergipe</option>
    <option value="TO">Tocantins</option>
    <option value="EX">Estrangeiro</option>
</select>						
						</div>

						
					</div>


					<div class="row">

						<div class="col-md-6" style="margin-bottom:10px">							
								<label>Chave Pix</label>
								<input type="text" class="form-control" id="pix" name="pix" placeholder="Chave Pix" >							
						</div>

						<div class="col-md-6" style="margin-bottom:10px">							
								<label>Indicação</label>
								<input type="text" class="form-control" id="indicacao" name="indicacao" placeholder="Indicado Por" >							
						</div>
					</div>




					<div class="row">

						<div class="col-md-12" style="margin-bottom:10px">							
								<label>Observações</label>
								<input type="text" class="form-control" id="obs" name="obs" placeholder="Observações" >							
						</div>
					</div>


					


					


					<input type="hidden" class="form-control" id="id" name="id">					

				<br>
				<small><div id="mensagem" align="center"></div></small>

				<button type="submit" class="btn btn-primary" style="width:150px">Salvar</button>
			</div>
			   
				
			
			</form>
	</div>

</body>
</html>





<!-- new added graphs chart js-->
	
	<script src="painel/js/Chart.bundle.js"></script>
	<script src="painel/js/utils.js"></script>
	
	
	
	<!-- Classie --><!-- for toggle left push menu script -->
	<script src="painel/js/classie.js"></script>
	
	<!--scrolling js-->
	<script src="painel/js/jquery.nicescroll.js"></script>
	<script src="painel/js/scripts.js"></script>
	<!--//scrolling js-->
	
	
	
	<!-- Bootstrap Core JavaScript -->
	<script src="painel/js/bootstrap.js"> </script>
	<!-- //Bootstrap Core JavaScript -->



	<!-- Mascaras JS -->
<script type="text/javascript" src="painel/js/mascaras.js"></script>

<!-- Ajax para funcionar Mascaras JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script> 





<script>
    
    function limpa_formulário_cep() {
            //Limpa valores do formulário de cep.
            document.getElementById('endereco').value=("");
            document.getElementById('bairro').value=("");
            document.getElementById('cidade').value=("");
            document.getElementById('estado').value=("");
            //document.getElementById('ibge').value=("");
    }

    function meu_callback(conteudo) {
        if (!("erro" in conteudo)) {
            //Atualiza os campos com os valores.
            document.getElementById('endereco').value=(conteudo.logradouro);
            document.getElementById('bairro').value=(conteudo.bairro);
            document.getElementById('cidade').value=(conteudo.localidade);
            document.getElementById('estado').value=(conteudo.uf);
            //document.getElementById('ibge').value=(conteudo.ibge);
        } //end if.
        else {
            //CEP não Encontrado.
            limpa_formulário_cep();
            alert("CEP não encontrado.");
        }
    }
        
    function pesquisacep(valor) {

        //Nova variável "cep" somente com dígitos.
        var cep = valor.replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if(validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                document.getElementById('endereco').value="...";
                document.getElementById('bairro').value="...";
                document.getElementById('cidade').value="...";
                document.getElementById('estado').value="...";
                //document.getElementById('ibge').value="...";

                //Cria um elemento javascript.
                var script = document.createElement('script');

                //Sincroniza com o callback.
                script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';

                //Insere script no documento e carrega o conteúdo.
                document.body.appendChild(script);

            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
        }
    };








$("#form").submit(function () {

    $('#mensagem').text('Salvando!!!');
    
    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'painel/paginas/clientes/salvar.php',
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem').text('');
            $('#mensagem').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {

            	alert("Cadastrado com Sucesso!");                

            } else {
            	alert(mensagem); 
                $('#mensagem').addClass('text-danger')
                //$('#mensagem').text(mensagem)
            }


        },

        cache: false,
        contentType: false,
        processData: false,

    });

});

    </script>