<!DOCTYPE html>
<?php
	// Verifica usuário logado e nível de acesso
	$nivel_necessario = 5;
	require_once "codigo.usuario_sessao.php";

	// Incluir arquivo de conexão ao BD
	require_once "codigo.bd_conexao.php";

	// Flag para sinalização se é para carregar os dados
	$carrega = 0;

	// Função de atualização dos dados de usuário
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		if (isset($_POST["atualiza"])) {
			// Atribui as variáveis ao formulário
			$gepoc_id = $_POST['novo_gepoc_id'];
			$patrimonio = $_POST['novo_patrimonio'];
			$num_serie = $_POST['novo_num_serie'];
			$equipamento = $_POST['novo_equipamento'];
			$fabricante = $_POST['novo_fabricante'];
			$localizacao = $_POST['novo_localizacao'];
			$rfid = $_POST['novo_rfid'];
			$imagem = $_POST['novo_imagem'];
			$categoria_id = $_POST['novo_categoria'];
			$prof_responsavel_id = $_POST['novo_prof_responsavel'];
			$funcionamento_id = $_POST['novo_funcionamento'];
			// Prepare uma instrução de atualização
			$sqlupdate = "UPDATE equipamento
				SET patrimonio='$patrimonio', num_serie='$num_serie', num_serie='$num_serie', equipamento='$equipamento', fabricante='$fabricante', localizacao='$localizacao',
				rfid='$rfid', imagem='$imagem', categoria_id='$categoria_id', prof_responsavel_id='$prof_responsavel_id', funcionamento_id='$funcionamento_id'
				WHERE gepoc_id='$gepoc_id'";
			if (mysqli_query($link, $sqlupdate)){
				echo "<script>alert('Os registros do equipamento ".$equipamento." (ID ".$gepoc_id.") foram atualizados com sucesso!'); window.location = 'equipamentos_detalhe.php?gepocid=$gepoc_id'</script>;";
			} else {
				echo "ERRO: Não foi possível executar $sqlupdate." . mysqli_error($link);
			}

			// Fecha conexão
			mysqli_close($link);

			$carrega = 1;
		}
    }

	// Função para carregar os valores atuais do banco de dados
	if ($carrega == 0) {
		// Carrega GEPOC ID, da página de equipamentos ou do endereço
		if(empty($_POST['gepocid'])) {
			$gepocid = $_GET['gepocid'];
		} else {
			$gepocid = $_POST['gepocid'];
		}

		// Caso não exista um GEPOC ID, redireciona para a página de seleção de equipamento
		if(empty($gepocid)) {
			header("location: equipamentos_atualiza_selecao.php");
		}

		// Validação do GEPOC ID, se ele está cadastrado no banco de dados. Prepara uma declaração SELECT
		if ($stmt = mysqli_prepare($link, "SELECT equipamento FROM equipamento WHERE gepoc_id=?")) {
			// Vincular variáveis à declaração preparada como parâmetros
			mysqli_stmt_bind_param($stmt, "s", $gepocid);

			// Tentativa de execução da declaração preparada
			if(mysqli_stmt_execute($stmt)){
				// Armazena o resultado
				mysqli_stmt_store_result($stmt);
				
				// Verifica se o equipamento está cadastrado, se sim, carrega as informações
				if(mysqli_stmt_num_rows($stmt) == 1){
					// Vincular variáveis de resultado
					mysqli_stmt_bind_result($stmt, $equipamento);
						$result = mysqli_query($link,'SELECT * FROM equipamento
							INNER JOIN equipamento_funcionamento ON equipamento.funcionamento_id = equipamento_funcionamento.id
							WHERE gepoc_id = "'.$gepocid.'"');
						$carrega = mysqli_fetch_assoc($result);
					// Busca valor
					mysqli_stmt_fetch($stmt);
				} else {
					// Mensagem de equipamento não cadastrado e redireciona para página de equipamentos
					echo "<script>alert('Este GEPOC ID não está cadastrado no sistema!'); window.location = 'equipamentos_atualiza_selecao.php'</script>;";
				}
			}
			// Fechar declaração
			mysqli_stmt_close($stmt);
		}
	}
?>

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
						<h1>Configurações do sistema</h1>
					</div>
					<div class="span6">
						<ul class="breadcrumb pull-right">
							<a href="codigo.usuario_logout.php" class="btn btn-small btn-warning"><b><?php echo htmlspecialchars($_SESSION["nome"]); ?></b>, sair</a>
						</ul>
						<ul class="breadcrumb pull-right">
							<li><a href="index.php">Home</a> <span class="divider">/</span></li>
							<li><a href="configuracoes.php">Configurações do sistema</a> <span class="divider">/</span></li>
							<li class="active">Atualização de equipamento</li>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<!-- /Cabeçalho da página -->

		<!-- Informações do Equipamento -->	
		<section id="selecao" class="main">
			<div class="container">
				<div class="row-fluid">
					<div class="span4"></div>
					<div class="span4">
						<form name="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
							<h2>Atualizar informações</h2>
							<div class="form-group">
								<label><b>Data de cadastro</b></label>
								<input type="datetime-local" name="data_cadastro" required="required" class="input-block-level" value="<?php echo date('d/m/Y H:i:s', strtotime($carrega['data_cadastro'])); ?>" disabled>
							</div>
							<div class="form-group">
								<label><b>Data da última modificação</b></label>
								<input type="datetime-local" name="data_modificacao" required="required" class="input-block-level" value="<?php echo date('d/m/Y H:i:s', strtotime($carrega['data_modificacao'])); ?>" disabled>
							</div>
							<div class="form-group">
								<label><b>GEPOC ID</b></label>
								<input type="number" min="1" name="novo_gepoc_id" required="required" class="input-block-level" value="<?php echo $carrega['gepoc_id']; ?>" autofocus>
							</div>    
							<div class="form-group">
								<label><b>Patrimônio</b></label>
								<input type="number" min="0" max="999999" name="novo_patrimonio" class="input-block-level" value="<?php echo $carrega['patrimonio']; ?>">
							</div>
							<div class="form-group">
								<label><b>Número de série</b></label>
								<input type="text" name="novo_num_serie" class="input-block-level" value="<?php echo $carrega['num_serie']; ?>">
							</div>
							<div class="form-group">
								<label><b>Nome do equipamento</b></label>
								<input type="text" name="novo_equipamento" required="required" class="input-block-level" value="<?php echo $carrega['equipamento']; ?>">
							</div>
							<div class="form-group">
								<label><b>Fabricante</b></label>
								<input type="text" name="novo_fabricante" required="required" class="input-block-level" value="<?php echo $carrega['fabricante']; ?>">
							</div>
							<div class="form-group">
								<label><b>Localização</b></label>
								<input type="text" name="novo_localizacao" required="required" class="input-block-level" value="<?php echo $carrega['localizacao']; ?>">
							</div>
							<div class="form-group">
								<label><b>RFID</b></label>
								<input type="text" name="novo_rfid" required="required" minlength="10" maxlength="10" class="input-block-level" value="<?php echo $carrega['rfid']; ?>">
							</div>
							<label><b>Imagem do equipamento</b></label>
								<select class='input-block-level' name='novo_imagem'>
									<option></option>
									<?php
										// Lista as imagens na existentes no servidor
										$files = glob("images/equipamentos/*.*");
										for ($i = 0; $i < count($files); $i++) {
											$image = $files[$i];
											$arquivo = basename($image); // obtém o nome completo do arquivo
											if ($arquivo != "imagem-invalida.jpg" && $arquivo != "sem-imagem.jpg" ){
												echo '<option style="background-image:url(images/equipamentos/'.$arquivo.');" value="'.$arquivo.'"';
												if($carrega['imagem'] == $arquivo){echo("selected");};
												echo '>'.$arquivo.'</option>';
											}
										}
									?>
								</select>
							<div class="form-group">
								<label><b>Categoria</b></label>
								<select class='input-block-level' required='required'  name='novo_categoria'>
									<?php
										// Consulta ao banco de dados
										$result_cat = mysqli_query($link,"SELECT id, categoria FROM equipamento_categoria ORDER BY categoria ASC");

										// Lista as opções existentes na tabela equipamento_categoria
										while ($row = mysqli_fetch_assoc($result_cat)) {
											unset($id, $funcionamento);
											$id = $row['id'];
											$categoria = $row['categoria'];
											echo '<option value="'.$id.'"';
											// Carrega o select box, com a categoria atual pré selecionada
											if($carrega['categoria_id'] == $id){echo("selected");};
											echo '>'.$categoria.'</option>';
										}
									?> 
								</select>
							</div>
							<div class="form-group">
								<label><b>Professor responsável</b></label>
								<select class='input-block-level' name='novo_prof_responsavel'>
									<option></option>
									<?php
										// Consulta ao banco de dados
										$result_prof = mysqli_query($link,"SELECT id, nome FROM equipamento_professor ORDER BY nome ASC");

										// Lista as opções existentes na tabela equipamento_professor
										while ($row = mysqli_fetch_assoc($result_prof)) {
											unset($id, $funcionamento);
											$id = $row['id'];
											$professor = $row['nome'];
											echo '<option value="'.$id.'"';
											// Carrega o select box, com o professor responsável atual pré selecionado
											if($carrega['prof_responsavel_id'] == $id){echo("selected");};
											echo '>'.$professor.'</option>';
										}
									?> 
								</select>
							</div>
							<div class="form-group">
								<label><b>Funcionamento</b></label>
								<select class='input-block-level' required='required'  name='novo_funcionamento'>
									<?php
										// Consulta ao banco de dados
										$result_func = mysqli_query($link,"SELECT id, funcionamento FROM equipamento_funcionamento ORDER BY id ASC");

										// Lista as opções existentes na tabela equipamento_funcionamento
										while ($row = mysqli_fetch_assoc($result_func)) {
											unset($id, $funcionamento);
											$id = $row['id'];
											$funcionamento = $row['funcionamento'];
											echo '<option value="'.$id.'"';
											// Carrega o select box, com o funcionamento atual pré selecionado
											if($carrega['funcionamento_id'] == $id){echo("selected");};
											echo '>'.$funcionamento.'</option>';
										}
										
										// Fechar conexão
										mysqli_close($link);
									?> 
								</select>
							</div>
							<div class="form-group">
								<input type="submit" name="atualiza" class="btn btn-large btn-primary btn-block" value="Atualizar informações">
							</div>
						</form>
					</div>
					<div class="span4"></div>
				</div>
			</div>
		</section>
		<!-- /Informações do Equipamento -->
	</body>
</html>