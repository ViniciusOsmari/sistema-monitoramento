<!DOCTYPE html>
<?php
	// Verifica usuário logado e nível de acesso
	$nivel_necessario = 5;
	require_once "codigo.usuario_sessao.php";

	// Incluir arquivo de conexão ao BD
	require_once "codigo.bd_conexao.php";

	// Define variáveis e inicializa com valores vazios
	$gepoc_id = $patrimonio = $num_serie = $equipamento =  $categoria_id = $prof_responsavel_id = $localizacao = $funcionamento_id = $fabricante = $rfid = $imagem = "";
	$gepoc_id_err = $equipamento_err = $categoria_id_err = $localizacao_err = $professor_id_err = $funcionamento_id_err = $fabricante_err = $rfid_err = $imagem_err = "";
 
	// Processamento de dados do formulário, quando for é enviado
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		// Validação do ID GEPOC
		if(empty(trim($_POST["gepoc_id"]))){
			$gepoc_id_err = "Por favor insira o GEPOC ID do equipamento.";
		} else{
			$gepoc_id = trim($_POST["gepoc_id"]);
		}

		// Grava o patrimônio, se existir
		$patrimonio = trim($_POST["patrimonio"]);

		// Grava o número de série, se existir
		$num_serie = trim($_POST["num_serie"]);

		// Validação do nome do equipamento
		if(empty(trim($_POST["equipamento"]))){
			$equipamento_err = "Por favor insira o nome do equipamento.";
		} else{
			$equipamento = trim($_POST["equipamento"]);
		}
		
		// Validação do fabricante
		if(empty(trim($_POST["fabricante"]))){
			$fabricante_err = "Por favor insira o fabricante do equipamento.";
		} else{
			$fabricante = trim($_POST["fabricante"]);
		}

		// Validação da categoria
		if(empty(trim($_POST["categoria_id"]))){
			$categoria_id_err = "Por favor selecione a categoria.";
		} else{
			$categoria_id = trim($_POST["categoria_id"]);
		}

		// Validação da localização
		if(empty(trim($_POST["localizacao"]))){
			$localizacao_err = "Por favor insira a localização do equipamento.";
		} else{
			$localizacao = trim($_POST["localizacao"]);
		}

		// Validação do professor responsável
		if(empty(trim($_POST["prof_responsavel_id"]))){
			$prof_responsavel_id_err = "Por favor selecione o professor responsável.";
		} else{
			$prof_responsavel_id = trim($_POST["prof_responsavel_id"]);
		}

		// Validação do funcionamento
		if(empty(trim($_POST["funcionamento_id"]))){
			$funcionamento_id_err = "Por favor selecione o funcionamento do equipamento.";
		} else{
			$funcionamento_id = trim($_POST["funcionamento_id"]);
		}
		
		// Validação do rfid
		if(empty(trim($_POST["rfid"]))){
			$rfid_err = "Por favor insira o código rfid do equipamento.";
		} else{
			$rfid = trim($_POST["rfid"]);
		}

		// Grava o endereço da imagem, se existir
		$imagem = trim($_POST["imagem"]);
		
		// Verifica os erros de entrada antes de inserir no banco de dados
		if(empty($gepoc_id_err) && empty($equipamento_err) && empty($categoria_id_err) && empty($localizacao_err) && empty($professor_id_err) && empty($funcionamento_id_err) && empty($fabricante_err) && empty($rfid_err)){
			// Prepare uma instrução de inserção
			$sql = "INSERT INTO equipamento (gepoc_id, patrimonio, num_serie, categoria_id, equipamento, prof_responsavel_id, localizacao, funcionamento_id, fabricante, rfid, imagem) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			if($stmt = mysqli_prepare($link, $sql)){
				// Vincula variáveis à declaração preparada como parâmetros
				mysqli_stmt_bind_param($stmt, "sssssssssss", $param_gepoc_id, $param_patrimonio, $param_num_serie, $param_categoria_id, $param_equipamento, $param_prof_responsavel_id, $param_localizacao, $param_funcionamento_id, $param_fabricante, $param_rfid, $param_imagem);

				// Define parâmetros
				$param_gepoc_id = $gepoc_id;
				$param_patrimonio = $patrimonio;
				$param_num_serie = $num_serie;
				$param_categoria_id = $categoria_id;
				$param_equipamento = $equipamento;
				$param_prof_responsavel_id = $prof_responsavel_id;
				$param_localizacao = $localizacao;
				$param_funcionamento_id = $funcionamento_id;
				$param_fabricante = $fabricante;
				$param_rfid = $rfid;
				$param_imagem = $imagem;
				
				// Tentativa de execução da declaração preparada
				if(mysqli_stmt_execute($stmt)){
					// Recarrega a página
					echo "<script>alert('Equipamento ".$equipamento." cadastrado com sucesso!'); window.location = 'equipamentos_cadastro.php'</script>;";
				} else{
				echo "Algo deu errado. Por favor, tente novamente mais tarde.";
				}
			}
			// Fecha a declaração
			mysqli_stmt_close($stmt);
		}
		
		// Fecha conexão
		mysqli_close($link);
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
		<link rel="shortcut icon" href="images/favicon.png">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-responsive.min.css">
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<link rel="stylesheet" href="css/main.css">
		<script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
		<script src="js/vendor/jquery-3.4.0.slim.min.js"></script>
		<script src="js/vendor/bootstrap.min.js"></script>
		<script src="js/main.js"></script>
		<script src="js/jquery.ba-cond.min.js"></script>
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
						<h1>Cadastro de equipamentos</h1>
					</div>
					<div class="span6">
						<ul class="breadcrumb pull-right">
							<a href="codigo.usuario_logout.php" class="btn btn-small btn-warning"><b><?php echo htmlspecialchars($_SESSION["nome"]); ?></b>, sair</a>
						</ul>
						<ul class="breadcrumb pull-right">
							<li><a href="index.php">Home</a> <span class="divider">/</span></li>
							<li><a href="configuracoes.php">Configurações do sistema</a> <span class="divider">/</span></li>
							<li class="active">Cadastro de equipamento</li>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<!-- /Cabeçalho da página -->

		<!-- Informações Equipamento -->	
		<section id="cadastro" class="main">
			<div class="container">
				<div class="row-fluid">
					<div class="span4"></div>
					<div class="span4">
						<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
							<div class="form-group <?php echo (!empty($gepoc_id_err)) ? 'has-error' : ''; ?>">
								<label><b>GEPOC ID</b></label>
								<input type="number" min="1" name="gepoc_id" required="required" class="input-block-level" value="<?php echo $gepoc_id; ?>" autofocus>
								<span class="help-block"><?php echo $gepoc_id_err; ?></span>
							</div>    
							<div class="form-group">
								<label><b>Patrimônio</b></label>
								<input type="number" min="1" max="999999" name="patrimonio" class="input-block-level" value="<?php echo $patrimonio; ?>">
							</div>
							<div class="form-group">
								<label><b>Número de série</b></label>
								<input type="text" name="num_serie" required="required" class="input-block-level" value="<?php echo $num_serie; ?>">
							</div>
							<div class="form-group <?php echo (!empty($equipamento_err)) ? 'has-error' : ''; ?>">
								<label><b>Nome do equipamento</b></label>
								<input type="text" name="equipamento" required="required" class="input-block-level" value="<?php echo $equipamento; ?>">
								<span class="help-block"><?php echo $equipamento_err; ?></span>
							</div>
							<div class="form-group <?php echo (!empty($fabricante_err)) ? 'has-error' : ''; ?>">
								<label><b>Fabricante</b></label>
								<input type="text" name="fabricante" required="required" class="input-block-level" value="<?php echo $fabricante; ?>">
								<span class="help-block"><?php echo $fabricante_err; ?></span>
							</div>
							<div class="form-group <?php echo (!empty($localizacao_err)) ? 'has-error' : ''; ?>">
								<label><b>Localização</b></label>
								<input type="text" name="localizacao" required="required" class="input-block-level" value="<?php echo $localizacao; ?>">
								<span class="help-block"><?php echo $localizacao_err; ?></span>
							</div>
							<div class="form-group <?php echo (!empty($rfid_err)) ? 'has-error' : ''; ?>">
								<label><b>RFID</b></label>
								<input type="text" name="rfid" required="required" minlength="10" maxlength="10" class="input-block-level" value="<?php echo $rfid; ?>">
								<span class="help-block"><?php echo $rfid_err; ?></span>
							</div>
							<div class="form-group">
								<label><b>Imagem do equipamento</b></label>
								<select class='input-block-level' required='required' name='imagem'>
									<option> </option>
									<?php
										$files = glob("images/equipamentos/*.*"); // busca todas as imagens na pasta
										for ($i = 0; $i < count($files); $i++) {
											$image = $files[$i];
											$arquivo = basename($image); // obtém o nome completo do arquivo
											if ($arquivo != "imagem-invalida.jpg" && $arquivo != "sem-imagem.jpg" ){
												echo '<option style="background-image:url(images/equipamentos/'.$arquivo.');" value="'.$arquivo.'">'.$arquivo.'</option>';

											}
										}
									?>
								</select>
							</div>
							<div class="form-group <?php echo (!empty($categoria_id_err)) ? 'has-error' : ''; ?>">
								<label><b>Categoria</b></label>
								<select class='input-block-level' required='required' name='categoria_id'>
									<option> </option>
									<?php
										// Consulta ao banco de dados
										$result = mysqli_query($link,"SELECT id, categoria FROM equipamento_categoria ORDER BY categoria ASC");
										
										// Lista as opções existentes na tabela equipamento_categoria
										while ($row = mysqli_fetch_assoc($result)) {
											unset($id, $categoria);
											$id = $row['id'];
											$categoria = $row['categoria']; 
											echo '<option value="'.$id.'">'.$categoria.'</option>';
										}
									?>
								</select>
								<span class="help-block"><?php echo $categoria_id_err; ?></span>
							</div>
							<div class="form-group <?php echo (!empty($professor_id_err)) ? 'has-error' : ''; ?>">
								<label><b>Professor responsável</b></label>
								<select class='input-block-level' required='required' name='prof_responsavel_id'>
									<option> </option>
									<?php
										// Consulta ao banco de dados
										$result = mysqli_query($link,"SELECT id, nome FROM equipamento_professor ORDER BY nome ASC");

										// Lista as opções existentes na tabela equipamento_professor
											while ($row = mysqli_fetch_assoc($result)) {
												unset($id, $prof_responsavel);
												$id = $row['id'];
												$prof_responsavel = $row['nome']; 
												echo '<option value="'.$id.'">'.$prof_responsavel.'</option>';
											}
									?>
								</select>
								<span class="help-block"><?php echo $professor_id_err; ?></span>					
							</div>
							<div class="form-group <?php echo (!empty($funcionamento_id_err)) ? 'has-error' : ''; ?>">
								<label><b>Funcionamento</b></label>
								<select class='input-block-level' required='required' name='funcionamento_id'>
									<option> </option>
									<?php
										// Consulta ao banco de dados
										$result = mysqli_query($link,"SELECT id, funcionamento FROM equipamento_funcionamento ORDER BY id ASC");

										// Lista as opções existentes na tabela equipamento_funcionamento
										while ($row = mysqli_fetch_assoc($result)) {
											unset($id, $funcionamento);
											$id = $row['id'];
											$funcionamento = $row['funcionamento']; 
											echo '<option value="'.$id.'">'.$funcionamento.'</option>';
										}

										// Fechar conexão
										mysqli_close($link);
									?>
								</select>
								<span class="help-block"><?php echo $funcionamento_id_err; ?></span>
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
		<!-- /Informações Equipamento -->
	</body>
</html>