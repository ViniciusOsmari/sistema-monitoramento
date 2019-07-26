<?php
	// Inicializa a sessão
	session_start();
 
	// Verifica se o usuário está logado, caso não esteja, redireciona para a página de login
 	if(!isset($_SESSION["logado"]) || $_SESSION["logado"] !== true){
 		header("location: index_login.php");
 		exit;
	}
	
	// Verifica o nível de acesso do usuário para liberar botão de configurações
	if($_SESSION['nivel'] >= 5) {
		$menu_config = "<li><a href='configuracoes.php'>Configurações do sistema</a></li>";
    }else{
    echo $menu_config = "";
    }
	
	// Verifica o nível de acesso do usuário
	if ($_SESSION["nivel"] < $nivel_necessario) {
		echo "<script>alert('Página bloqueada para esse nível de usuário!'); window.location = 'index.php'</script>;";
		exit;
	}
?>