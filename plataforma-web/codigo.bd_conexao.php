<?php
	// Credenciais do banco de dados.
	define("BD_SERVER", "NOME_DO_SERVIDOR");
	define("BD_USERNAME", "NOME_DO_USUARIO");
	define("BD_PASSWORD", "SENHA_DO_USUARIO");
	define("BD_NAME", "NOME_DO_BANCO_DE_DADOS");
	 
	// Tentativa de conexão ao banco de dados MySQL
	$link = mysqli_connect(BD_SERVER, BD_USERNAME, BD_PASSWORD, BD_NAME);

	// Verifica a conexão
	if($link === false){
		die("ERRO: não foi possível conectar.".mysqli_connect_error());
	}
	
	if (!mysqli_set_charset($link, "utf8")) {
		printf("Erro ao usar utf8: %s", mysqli_error($link));
		exit;
	}
?>