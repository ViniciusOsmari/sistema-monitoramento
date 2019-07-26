<?php
	// Inicializa a sessão
	session_start();
 
	// Desativar todas as variáveis da sessão
	$_SESSION = array();
 
	// Destrói a sessão
	session_destroy();

	// Redireciona para a home
	header("location: index.php");
	exit;
?>