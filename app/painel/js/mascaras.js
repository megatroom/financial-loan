$(document).ready(function () {
	$('#telefone').mask('(00) 00000-0000');
	$('#cpf').mask('000.000.000-00');
    $('#cep').mask('00000-000');
    $('#cnpj').mask('00.000.000/0000-00');

    $('#telefone_perfil').mask('(00) 00000-0000');
	$('#cpf_perfil').mask('000.000.000-00');
	$('#telefone_sistema').mask('(00) 00000-0000');
	$('#cnpj_sistema').mask('00.000.000/0000-00');

	$('#telefone_sec').mask('(00) 00000-0000');

	$('#data_nasc').mask('00/00/0000');
});