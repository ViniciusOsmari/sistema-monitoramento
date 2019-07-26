<?php
	$nivel_necessario = 5;
	// Verifica usuário logado
	require_once "codigo.usuario_sessao.php";
	
	// Incluir arquivo de conexão ao BD
	require_once "codigo.bd_conexao.php";
 
	// Define variáveis e inicializa com valores vazios
	$email = $senha = $confirm_senha = $nome =  $rfid = $nivel_id = "";
	$email_err = $senha_err = $confirm_senha_err = $nome_err = $rfid_err = $nivel_id_err = "";
 
	// Processamento de dados do formulário, quando for é enviado
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		// Validação do email
		if(empty(trim($_POST["email"]))){
			$email_err = "Por favor insira o endereço de e-mail.";
		} else{
			// Prepare uma select
			$sql = "SELECT id FROM usuario WHERE email = ?";
			
			if($stmt = mysqli_prepare($link, $sql)){
				// Vincular variáveis à declaração preparada como parâmetros
				mysqli_stmt_bind_param($stmt, "s", $param_email);
				
				// Define parâmetros
				$param_email = trim($_POST["email"]);
				
				// Tentativa de execução da declaração preparada
				if(mysqli_stmt_execute($stmt)){
					// Armazena resultado
					mysqli_stmt_store_result($stmt);
					
					if(mysqli_stmt_num_rows($stmt) == 1){
						$email_err = "Este e-mail já está sendo usado.";
					} else{
						$email = trim($_POST["email"]);
					}
				} else{
					echo "Opa! Algo deu errado. Por favor, tente novamente mais tarde.";
				}
			}
			 
			// fecha
			mysqli_stmt_close($stmt);
		}

		// Validação da senha
		if(empty(trim($_POST["senha"]))){
			$senha_err = "Por favor insira uma senha.";   
		} elseif(strlen(trim($_POST["senha"])) < 5){
			$senha_err = "A senha deve ter pelo menos 5 caracteres.";
		} else{
			$senha = trim($_POST["senha"]);
		}

		// Validação da confirmação da senha
		if(empty(trim($_POST["confirm_senha"]))){
			$confirm_senha_err = "Por favor, confirme a senha.";    
		} else{
			$confirm_senha = trim($_POST["confirm_senha"]);
			if(empty($senha_err) && ($senha != $confirm_senha)){
				$confirm_senha_err = "A senha não coincide.";
			}
		}

		// Validação do nome
		if(empty(trim($_POST["nome"]))){
			$nome_err = "Por favor insira o nome do usuário.";
		} else{
			$nome = trim($_POST["nome"]);
		}

		// Validação do crachá RFID
		if(empty(trim($_POST["rfid"]))){
			$rfid_err = "Por favor insira o endereço RFID do crachá.";
		} else{
			$rfid = trim($_POST["rfid"]);
		}
		
		// Validação do nível de acesso
		if(empty(trim($_POST["nivel_id"]))){
			$nivel_id_err = "Por favor selecione o nível de acesso do usuário.";
		} else{
			$nivel_id = trim($_POST["nivel_id"]);
		}
		
		// Verifica os erros de entrada antes de inserir no banco de dados
		if(empty($email_err) && empty($senha_err) && empty($confirm_senha_err) && empty($nome_err) && empty($rfid_err) && empty($nivel_err)){
			
			// Prepare uma instrução de inserção
			$sql = "INSERT INTO usuario (email, senha, nome, rfid, nivel_id) VALUES (?, ?, ?, ?, ?)";
			 
			if($stmt = mysqli_prepare($link, $sql)){
				// Vincula variáveis à declaração preparada como parâmetros
				mysqli_stmt_bind_param($stmt, "sssss", $param_email, $param_senha, $param_nome, $param_rfid, $param_nivel_id);
				
				// Define parâmetros
				$param_email = $email;
//				$param_senha = password_hash($senha, PASSWORD_DEFAULT); //senha com HASH
				$param_senha = $senha;
				$param_nome = $nome;
				$param_rfid = $rfid;
				$param_nivel_id = $nivel_id;
				
				// Tentativa de execução da declaração preparada
				if(mysqli_stmt_execute($stmt)){
					// Exibe mensagem de conclusão e recarrega a página
					echo "<script>alert('Usuário ".$nome." registrado com sucesso!'); window.location = 'configuracoes_usuario_cadastro.php'</script>;";
				} else{
				echo "Algo deu errado. Por favor, tente novamente mais tarde.";
				}
			}
			// Fecha a declaração
			mysqli_stmt_close($stmt);
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
						<li><a href="index.php">Home</a></li>
						<li><a href="equipamentos.php">Controle de equipamentos</a></li>
						<li><a href="monitoramento.php">Monitoramento de salas</a></li>
						<li class="active"><a href="configuracoes.php">Configurações do sistema</a></li>
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
						<h1>Cadastro de usuário</h1>
					</div>
					<div class="span6">
						<ul class="breadcrumb pull-right">
							<a href="codigo.usuario_logout.php" class="btn btn-small btn-warning"><b><?php echo htmlspecialchars($_SESSION["nome"]); ?></b>, sair</a>
						</ul>
						<ul class="breadcrumb pull-right">
							<li><a href="index.php">Home</a> <span class="divider">/</span></li>
							<li><a href="configuracoes.php">Configurações do sistema</a> <span class="divider">/</span></li>
							<li class="active">Cadastro de usuário</li>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<!-- /Cabeçalho da página -->  

		<!-- Formulário -->	
		<section id="login" class="main">
			<div class="container">
				<div class="row-fluid">
					<div class="span4"></div>
					<div class="span4">
						<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
							<div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
								<label><b>E-mail</b></label>
								<input type="email" name="email" class="input-block-level" value="<?php echo $email; ?>" autofocus>
								<span class="help-block"><?php echo $email_err; ?></span>
							</div>    
							<div class="form-group <?php echo (!empty($senha_err)) ? 'has-error' : ''; ?>">
								<label><b>Senha</b></label>
								<input type="password" name="senha" class="input-block-level" value="<?php echo $senha; ?>">
								<span class="help-block"><?php echo $senha_err; ?></span>
							</div>
							<div class="form-group <?php echo (!empty($confirm_senha_err)) ? 'has-error' : ''; ?>">
								<label><b>Confirme a senha</b></label>
								<input type="password" name="confirm_senha" class="input-block-level" value="<?php echo $confirm_senha; ?>">
								<span class="help-block"><?php echo $confirm_senha_err; ?></span>
							</div>
							<div class="form-group <?php echo (!empty($nome_err)) ? 'has-error' : ''; ?>">
								<label><b>Nome</b></label>
								<input type="text" name="nome" class="input-block-level" value="<?php echo $nome; ?>">
								<span class="help-block"><?php echo $nome_err; ?></span>
							</div>
							<div class="form-group <?php echo (!empty($rfid_err)) ? 'has-error' : ''; ?>">
								<label><b>Crachá RFID</b></label>
								<input type="text" name="rfid" minlength="10" maxlength="10" class="input-block-level" value="<?php echo $rfid; ?>">
								<span class="help-block"><?php echo $rfid_err; ?></span>
							</div>
							<div class="form-group <?php echo (!empty($nivel_id_err)) ? 'has-error' : ''; ?>">
								<label><b>Nível do usuario</b></label>
								<select class='input-block-level' name='nivel_id'>
									<option> </option>
									<?php
										// Seleção de dados do banco de dados
										$result = mysqli_query($link,"SELECT id, nivel FROM usuario_nivel");

										// Lista os registros existentes na tabela usuario_nivel
										while ($row = mysqli_fetch_assoc($result)) {
											unset($id, $nivel);
											$id = $row['id'];
											$nivel = $row['nivel'];
											if($_SESSION['nivel'] >= $row['id']) {
												echo '<option value="'.$id.'">'.$nivel.'</option>';
											}
										}
										mysqli_close($link);
									?>
								</select>
							</div>
							<div class="form-group">
								<input type="submit" class="btn btn-large btn-primary btn-block" value="Enviar">
							</div>
						</form>
					</div>
					<div class="span4"></div>
				</div>
			</div>
		</section>
		<!-- /Formulário -->	
	</body>
</html>