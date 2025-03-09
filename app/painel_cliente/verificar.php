<?php 
@session_start();
if (@$_SESSION['id'] == ""){
	@session_destroy();
	echo '<script>window.location="../acesso"</script>';
	exit();
}

if (@$_SESSION['token_IFDSFDSFFSAS'] != "IODIIIJFDFDSS"){
	@session_destroy();
	echo '<script>window.location="../acesso"</script>';
	exit();
}

 ?>
