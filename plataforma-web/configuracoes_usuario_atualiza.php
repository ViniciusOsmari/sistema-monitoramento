<?php
	$nivel_necessario = 5;
	// Verifica usuário logado
	require_once "codigo.usuario_sessao.php";
	
	// Incluir arquivo de conexão ao BD
	require_once "codigo.bd_conexao.php";

	// Flag para sinalização se é para carregar os dados
	$carrega = 0;

	// Carrega o ID do usuario
	if(empty($_POST["id"])) {
		$id = $_GET["id"];
	} else {
		$id = $_POST["id"];
	}

	// Função de atualização dos dados de usuário
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		if (isset($_POST["atualiza"])) {
				// Atribui as variáveis ao formulário
				$email = $_POST['novo_email'];
				$senha = $_POST['novo_senha'];
				$nome = $_POST['novo_nome'];
				$rfid = $_POST['novo_rfid'];
				$nivel_id = $_POST['novo_nivel_id'];

				// Prepare uma instrução de atualização
				$sqlupdate = "UPDATE usuario
					SET email='$email', senha='$senha', nome='$nome', rfid='$rfid', nivel_id='$nivel_id'
					WHERE id='$id'";
				if(mysqli_query($link, $sqlupdate)){
					echo "<script>alert('Os registros do usuario ".$nome." (ID=".$id.") foram atualizados com sucesso!'); window.location = 'configuracoes_usuario_lista.php'</script>;";
				} else {
					echo "ERRO: Não foi possível executar $sqlupdate. " . mysqli_error($link);
				}
			
			// Fecha conexão
			mysqli_close($link);
			$carrega = 1;
		}
    }

	// Função para carregar os valores atuais do banco de dados
	if ($carrega == 0) {
		// Validação do ID, se ele está cadastrado no banco de dados. Prepara uma declaração SELECT
		if ($stmt = mysqli_prepare($link, "SELECT nivel_id FROM usuario WHERE id=?")) {
			// Vincular variáveis à declaração preparada como parâmetros
			mysqli_stmt_bind_param($stmt, "s", $id);

			// Tentativa de execução da declaração preparada
			if(mysqli_stmt_execute($stmt)){
				// Armazena o resultado
				mysqli_stmt_store_result($stmt);
				
				// Verifica se o equipamento está cadastrado, se sim, carrega as informações
				if(mysqli_stmt_num_rows($stmt) == 1){
					// Vincular variáveis de resultado
					mysqli_stmt_bind_result($stmt, $equipamento);
						$result = mysqli_query($link,'SELECT * FROM usuario WHERE id = "'.$id.'"');
						$carrega = mysqli_fetch_assoc($result);
						
						// Verifica se o nivel do usuário logado é maior do que o usuário a ser editado
						if ($carrega['nivel_id'] > $_SESSION['nivel']){
							echo "<script>alert('Este usuário não pode ser editado por um usuário de nível menor!'); window.location = 'configuracoes_usuario_lista.php'</script>;";
						}
						
					// Busca valor
					mysqli_stmt_fetch($stmt);
				} else {
					// Mensagem de usuario não cadastrado e redireciona para a lista de usuarios
					echo "<script>alert('ID de usuário (".$id.") não foi localizado no banco de dados!'); window.location = 'configuracoes_usuario_lista.php'</script>;";
				}
			}
			// Fechar declaração
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
						<h1>Atualização de usuário</h1>
					</div>
					<div class="span6">
						<ul class="breadcrumb pull-right">
							<a href="codigo.usuario_logout.php" class="btn btn-small btn-warning"><b><?php echo htmlspecialchars($_SESSION["nome"]); ?></b>, sair</a>
						</ul>
						<ul class="breadcrumb pull-right">
							<li><a href="index.php">Home</a> <span class="divider">/</span></li>
							<li><a href="configuracoes.php">Configurações do sistema</a> <span class="divider">/</span></li>
							<li class="active">Atualização de usuário</li>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<!-- /Cabeçalho da página -->  

		<!-- Informações do usuário -->
		<section class="main">
			<div class="container">
				<div class="row-fluid">
					<div class="span4"></div>
					<div class="span4">
						<form name="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
							<h2>Atualizar informações</h2>
							<input type="hidden" name="id" value="<?php echo $carrega['id']; ?>"/> 
							<div class="form-group">
								<label><b>Data de cadastro</b></label>
								<input type="datetime-local" name="data_cadastro" required="required" class="input-block-level" value="<?php echo date('d/m/Y H:i:s', strtotime($carrega['data_criacao'])); ?>" disabled>
							</div>
							<div class="form-group">
								<label><b>ID</b></label>
								<input type="number" required="required" class="input-block-level" value="<?php echo $carrega['id']; ?>" disabled>
							</div>
							<div class="form-group">
								<label><b>E-mail</b></label>
								<input type="email" name="novo_email" class="input-block-level" value="<?php echo $carrega['email']; ?>" autofocus>
							</div>
							<div class="form-group">
								<label><b>Senha</b></label>
								<input type="password" name="novo_senha" required="required" class="input-block-level" value="<?php echo $carrega['senha']; ?>">
							</div>
							<div class="form-group">
								<label><b>Nome</b></label>
								<input type="text" name="novo_nome" required="required" class="input-block-level" value="<?php echo $carrega['nome']; ?>">
							</div>
							<div class="form-group">
								<label><b>Crachá RFID</b></label>
								<input type="text" name="novo_rfid" required="required" minlength="10" maxlength="10" class="input-block-level" value="<?php echo $carrega['rfid']; ?>">
							</div>
							<div class="form-group">
								<label><b>Nível do usuario</b></label>
								<select class='input-block-level' required='required'  name='novo_nivel_id'>
									<?php
										// Seleção de dados do banco de dados
										$result_func = mysqli_query($link,"SELECT id, nivel FROM usuario_nivel ORDER BY nivel ASC");

										// Lista os registros existentes na tabela usuario_nivel
										while ($row = mysqli_fetch_assoc($result_func)) {
											unset($id, $nivel);
											$id = $row['id'];
											$nivel = $row['nivel'];
											// verifica
											if($_SESSION['nivel'] >= $row['id']) {
												echo '<option value="'.$id.'"';
												// Carrega o select box, com o nivel atual pré selecionado
												if($carrega['nivel_id'] == $id){echo("selected");};
												echo '>'.$nivel.'</option>';
											}
										}
										mysqli_close($link);
									?>
								</select>
							</div-->
							<div class="form-group">
								<input type="submit" class="btn btn-large btn-primary btn-block" name="atualiza" value="Atualizar informações">
							</div>
						</form>
					</div>
					<div class="span4"></div>
				</div>
			</div>
		</section>
		<!-- /Informações do usuário -->
	</body>
</html>