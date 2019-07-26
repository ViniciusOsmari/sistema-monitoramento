<?php
	// Inicializa a sessão
	session_start();

	// Incluir arquivo de conexão ao BD
	require_once "codigo.bd_conexao.php"; 

	// Define variáveis e inicializa com valores vazios
	$email = $senha = $nome = $nivel = "";
	$email_err = $senha_err = "";

	// Processa os dados quando o formulário de login é enviado
	if($_SERVER["REQUEST_METHOD"] == "POST"){
 		// Verifica se o nome de usuário está vazio
		if(empty(trim($_POST["email"]))){
			$email_err = "Por favor insira o endereço de e-mail.";
		} else{
			$email = trim($_POST["email"]);
		}

		// Verifica se a senha está vazia
		if(empty(trim($_POST["senha"]))){
			$senha_err = "Por favor, insira a senha.";
		} else{
			$senha = trim($_POST["senha"]);
		}

		// Valida as credenciais de email e senha
		if(empty($email_err) && empty($senha_err)){
			//Busca na tabela 'usuario' o usuário que corresponde com os dados digitado no formulário
			$result_usuario = "SELECT * FROM usuario WHERE email = '$email' AND BINARY senha = '$senha' LIMIT 1";
			$resultado_usuario = mysqli_query($link, $result_usuario);
			$resultado = mysqli_fetch_assoc($resultado_usuario);

			//Encontrado um usuario na tabela usuário com os mesmos dados digitado no formulário
			if(isset($resultado)){
				if (empty($resultado['desativado'])){
					// Armazena dados nas variáveis de sessão
					session_start();
					$_SESSION["logado"] = true;
					$_SESSION["id"] = $resultado['id'];
					$_SESSION["email"] = $resultado['email'];
					$nomeinicial = explode(" ", $resultado['nome']);
					$_SESSION["nome"] = $nomeinicial[0];
					$_SESSION['nivel'] = $resultado['nivel_id'];
					// Redireciona para a página inicial
					header("location: equipamentos.php");
				}
				else {
					$email_err = "Este usuário foi desativado!";
				}
			// Mensagem de erro para email e/ou senha incorretos
			}else{    
				$senha_err = "O endereço de email ou a senha que você inseriu não são válidos.";
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="pt-br" class="no-js">
	<!-- Configurações da página -->
	<head>
		<title>Sistema de monitoramento</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="viewport" content="width=device-width">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-responsive.min.css">
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/sl-slide.css">
		<link rel="shortcut icon" href="images/favicon.png">
		<script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
		<script src="js/vendor/jquery-3.4.0.slim.min.js"></script>
		<script src="js/vendor/bootstrap.min.js"></script>
		<script src="js/main.js"></script>
		<script src="js/jquery.ba-cond.min.js"></script>
		<script src="js/jquery.slitslider.js"></script>
	</head>
	<!-- /Configurações da página -->
	
	<body>
		<!-- Barra superior -->
		<header class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a id="logo" class="pull-left" href="//gepoc.ct.ufsm.br/intranet/"></a>
					<div class="nav-collapse collapse pull-right">
					<ul class="nav">
						<li class="active"><a href="index.php">Home</a></li>
						<li><a href="equipamentos.php">Controle de equipamentos</a></li>
						<li><a href="monitoramento.php">Monitoramento de salas</a></li>
					</ul>    
					</div>
				</div>
			</div>
		</header>
		<!-- /Barra superior -->

		<!-- Cabeçalho da página -->
		<section class="title">
			<div class="container">
				<div class="row-fluid">
					<div class="span6">
						<h1>Login de usuário</h1>
					</div>
					<div class="span6">
						<ul class="breadcrumb pull-right">
							<li class="active">Login</li>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<!-- /Cabeçalho da página -->  

		<!-- Informações de login -->
		<section id="login" class="main">
			<div class="container">
				<div class="row-fluid">
					<div class="span4"></div>
					<div class="span4">
						<form class="box" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
							<div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
								<input type="email" name="email" placeholder="Usuário" class="input-block-level" value="<?php echo $email; ?>" autofocus>
								<span class="help-block"><?php echo $email_err; ?></span>
							</div>
							<div class="form-group <?php echo (!empty($senha_err)) ? 'has-error' : ''; ?>">
								<input type="password" name="senha" placeholder="Senha" class="input-block-level">
								<span class="help-block"><?php echo $senha_err; ?></span>
							</div>
							<div class="form-group">
								<input type="submit" class="btn btn-large btn-primary btn-block" value="Entrar">
							</div>
						</form>
					</div>
					<div class="span4"></div>
				</div>
			</div>
		</section>
		<!-- /Informações de login -->
	</body>
</html>