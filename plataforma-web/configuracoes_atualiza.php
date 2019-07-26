<!DOCTYPE html>
<?php
	// Verifica usuário logado e nível de acesso
	$nivel_necessario = 5;
	require_once "codigo.usuario_sessao.php";

	// Incluir arquivo de conexão ao BD
	require_once "codigo.bd_conexao.php";

	// Carrega GEPOC ID, da página de equipamentos ou do endereço
	if(empty($_POST['id'])) {
		$id = $_GET['id'];
	} else {
		$id = $_POST['id'];
	}

	// Caso não exista um GEPOC ID, redireciona para a página de seleção de equipamento
	if(empty($id)) {
		header("location: configuracoes_lista.php");
	}

	// Flag para sinalização se é para carregar os dados
	$carrega = 0;

	// Função de atualização dos dados de usuário
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		if (isset($_POST["atualiza"])) {
			// Atribui as variáveis ao formulário
			$id = $_POST['novo_id'];
			$usuario_id = $_SESSION["id"];
			$ip = $_POST['novo_ip'];
			$sala_antiga = $_POST['sala_antiga'];
			$sala = $_POST['novo_sala'];
			$dht = $_POST['novo_dht'];
			$luminosidade = $_POST['novo_luminosidade'];
			$rfid = $_POST['novo_rfid'];
			$porta1 = $_POST['novo_porta1'];
			$porta2 = $_POST['novo_porta2'];
			$porta3 = $_POST['novo_porta3'];
			$porta4 = $_POST['novo_porta4'];
			$porta5 = $_POST['novo_porta5'];
			$porta6 = $_POST['novo_porta6'];
			$porta7 = $_POST['novo_porta7'];
			$porta8 = $_POST['novo_porta8'];
			$porta9 = $_POST['novo_porta9'];
			$porta10 = $_POST['novo_porta10'];
			$porta11 = $_POST['novo_porta11'];
			$porta12 = $_POST['novo_porta12'];
			$porta13 = $_POST['novo_porta13'];
			$porta14 = $_POST['novo_porta14'];
			$porta15 = $_POST['novo_porta15'];
			$porta16 = $_POST['novo_porta16'];
			$porta17 = $_POST['novo_porta17'];

			// Prepare uma instrução de atualização
			$sqlupdate = "UPDATE configuracao_rpi
				SET usuario_id = '$usuario_id', ip = '$ip', sala_id = '$sala', dht = '$dht', luminosidade = '$luminosidade', rfid = '$rfid', porta1 = '$porta1', porta2 = '$porta2',
					porta3 = '$porta3', porta4 = '$porta4', porta5 = '$porta5', porta6 = '$porta6', porta7 = '$porta7', porta8 = '$porta8', porta9 = '$porta9', porta10 = '$porta10',
					porta11 = '$porta11', porta12 = '$porta12', porta13 = '$porta13', porta14 = '$porta14', porta15 = '$porta15', porta16 = '$porta16', porta17 = '$porta17'
				WHERE configuracao_rpi.id='$id'";
			if(mysqli_query($link, $sqlupdate)){
				if($sala_antiga != $sala){
					// Atualiza unidade de monitoramento instalada (deleta a antiga e adiciona a nova)
					mysqli_query($link, "UPDATE sala SET unidade_monitoramento = 0 WHERE sala_id = ".$sala_antiga);
					mysqli_query($link, "UPDATE sala SET unidade_monitoramento = 1 WHERE sala_id = ".$sala);
				}
				echo "<script>alert('Os registros foram atualizados com sucesso!'); window.location = 'configuracoes_lista.php'</script>;";
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
		if ($stmt = mysqli_prepare($link, "SELECT ip FROM configuracao_rpi WHERE id=?")) {
			// Vincular variáveis à declaração preparada como parâmetros
			mysqli_stmt_bind_param($stmt, "s", $id);

			// Tentativa de execução da declaração preparada
			if(mysqli_stmt_execute($stmt)){
				// Armazena o resultado
				mysqli_stmt_store_result($stmt);
				
				// Verifica se o email está cadastrado, se sim, verifica a senha
				if(mysqli_stmt_num_rows($stmt) == 1){
					// Vincular variáveis de resultado
					mysqli_stmt_bind_result($stmt, $ip);
						$result = mysqli_query($link,'SELECT * FROM configuracao_rpi
						WHERE id = "'.$id.'"');
						$carrega = mysqli_fetch_assoc($result);
					// Busca valor
					mysqli_stmt_fetch($stmt);
				} else {
					// Mensagem de equipamento não cadastrado e redireciona para página de equipamentos
					echo "<script>alert('Nenhuma configuração com este ID foi encontrada no sistema!'); window.location = 'configuracoes_lista.php'</script>;";
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
						<h1>Atualizar configuração</h1>
					</div>
					<div class="span6">
						<ul class="breadcrumb pull-right">
							<a href="codigo.usuario_logout.php" class="btn btn-small btn-warning"><b><?php echo htmlspecialchars($_SESSION["nome"]); ?></b>, sair</a>
						</ul>
						<ul class="breadcrumb pull-right">
							<li><a href="index.php">Home</a> <span class="divider">/</span></li>
							<li><a href="configuracoes.php">Configurações do sistema</a> <span class="divider">/</span></li>
							<li class="active">Atualizar configuração</li>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<!-- /Cabeçalho da página -->

		<!-- Informações da configuração -->
		<section id="selecao" class="main">
			<div class="container">
				<div class="row-fluid">
					<div class="span4"></div>
					<div class="span4">
						<form name="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
							<h2>Atualizar informações</h2>
							<input type="hidden" name="sala_antiga" value="<?php echo $carrega['sala_id']; ?>"/> 
							<div class="form-group">
								<label><b>ID da configuração</b></label>
								<input type="text" name="novo_id" required="required" class="input-block-level" value="<?php echo $carrega['id']; ?>" readonly="readonly">
							</div>
							<div class="form-group">
								<label><b>Data de criação</b></label>
								<input type="datetime-local" name="novo_data_criacao" required="required" class="input-block-level" value="<?php echo date('d/m/Y H:i:s', strtotime($carrega['data_criacao'])); ?>" disabled>
							</div>
							<div class="form-group">
								<label><b>Data da última modificação</b></label>
								<input type="datetime-local" name="novo_data_criacao" required="required" class="input-block-level" value="<?php echo date('d/m/Y H:i:s', strtotime($carrega['data_modificacao'])); ?>" disabled>
							</div>
							<div class="form-group">
								<label><b>Endereço IP</b></label>
								<input type="text" name="novo_ip" required="required" class="input-block-level" value="<?php echo $carrega['ip']; ?>" placeholder="XXX.XXX.XXX.XXX" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" autofocus>
							</div>
							<div class="form-group">
								<label><b>Sala</b></label>
								<select class='input-block-level' required='required'  name='novo_sala'>
									<?php
										// Consulta ao banco de dados
										$result_sala = mysqli_query($link,"SELECT * FROM sala ORDER BY sala_predio ASC, sala_nome ASC");

										// Lista as opções existentes na tabela sala
										while ($dado = mysqli_fetch_assoc($result_sala)) {
											unset($id, $sala);
											$id = $dado['sala_id'];
											$sala = $dado['sala_nome']." ".$dado['nome']. " [".$dado['sala_predio']. "]";

											// Verifica se é a sala atual, ou se é uma sala sem unidade de monitoramento instalada
											if($dado['unidade_monitoramento'] == 0 or ($carrega['sala_id'] == $id)) {
												echo '<option value="'.$id.'"';
												// Carrega o select box, com a sala atual selecionada
												if($carrega['sala_id'] == $id) echo("selected");;
												echo '>'.$sala.'</option>';
											}
										}
									?>
								</select>
							</div>    
							<div class="form-group">
								<label><b>Sensor de umidade e temperatura</b></label>
								<select class='input-block-level' required='required'  name='novo_dht'>
									<?php
										// Consulta ao banco de dados
										$result_dht = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=4 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($dado = mysqli_fetch_assoc($result_dht)) {
											unset($id, $exp);
											$id = $dado['id'];
											$exp = $dado['funcao']; 
											echo '<option value="'.$id.'"';
											// Carrega o select box, com a função atual selecionada
											if($carrega['dht'] == $id) echo("selected");;
											echo '>'.$exp.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Sensor de luminosidade</b></label>
								<select class='input-block-level' required='required'  name='novo_luminosidade'>
									<?php
										// Consulta ao banco de dados
										$result_lum = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=2 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($dado = mysqli_fetch_assoc($result_lum)) {
											unset($id, $exp);
											$id = $dado['id'];
											$exp = $dado['funcao']; 
											echo '<option value="'.$id.'"';
											// Carrega o select box, com a função atual selecionada
											if($carrega['luminosidade'] == $id) echo("selected");;
											echo '>'.$exp.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Leitor RFID</b></label>
								<select class='input-block-level' required='required'  name='novo_rfid'>
									<?php
										// Consulta ao banco de dados
										$result_rfid = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=3 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($dado = mysqli_fetch_assoc($result_rfid)) {
											unset($id, $exp);
											$id = $dado['id'];
											$exp = $dado['funcao']; 
											echo '<option value="'.$id.'"';
											// Carrega o select box, com a função atual selecionada
											if($carrega['rfid'] == $id) echo("selected");;
											echo '>'.$exp.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 1</b></label>
								<select class='input-block-level' required='required'  name='novo_porta1'>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($dado = mysqli_fetch_assoc($result_pe)) {
											unset($id, $exp);
											$id = $dado['id'];
											$exp = $dado['funcao']; 
											echo '<option value="'.$id.'"';
											// Carrega o select box, com a função atual selecionada
											if($carrega['porta1'] == $id) echo("selected");;
											echo '>'.$exp.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 2</b></label>
								<select class='input-block-level' required='required'  name='novo_porta2'>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($dado = mysqli_fetch_assoc($result_pe)) {
											unset($id, $exp);
											$id = $dado['id'];
											$exp = $dado['funcao']; 
											echo '<option value="'.$id.'"';
											// Carrega o select box, com a função atual selecionada
											if($carrega['porta2'] == $id) echo("selected");;
											echo '>'.$exp.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 3</b></label>
								<select class='input-block-level' required='required'  name='novo_porta3'>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($dado = mysqli_fetch_assoc($result_pe)) {
											unset($id, $exp);
											$id = $dado['id'];
											$exp = $dado['funcao']; 
											echo '<option value="'.$id.'"';
											// Carrega o select box, com a função atual selecionada
											if($carrega['porta3'] == $id) echo("selected");;
											echo '>'.$exp.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 4</b></label>
								<select class='input-block-level' required='required'  name='novo_porta4'>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($dado = mysqli_fetch_assoc($result_pe)) {
											unset($id, $exp);
											$id = $dado['id'];
											$exp = $dado['funcao']; 
											echo '<option value="'.$id.'"';
											// Carrega o select box, com a função atual selecionada
											if($carrega['porta4'] == $id) echo("selected");;
											echo '>'.$exp.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 5</b></label>
								<select class='input-block-level' required='required'  name='novo_porta5'>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($dado = mysqli_fetch_assoc($result_pe)) {
											unset($id, $exp);
											$id = $dado['id'];
											$exp = $dado['funcao']; 
											echo '<option value="'.$id.'"';
											// Carrega o select box, com a função atual selecionada
											if($carrega['porta5'] == $id) echo("selected");;
											echo '>'.$exp.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 6</b></label>
								<select class='input-block-level' required='required'  name='novo_porta6'>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($dado = mysqli_fetch_assoc($result_pe)) {
											unset($id, $exp);
											$id = $dado['id'];
											$exp = $dado['funcao']; 
											echo '<option value="'.$id.'"';
											// Carrega o select box, com a função atual selecionada
											if($carrega['porta6'] == $id) echo("selected");;
											echo '>'.$exp.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 7</b></label>
								<select class='input-block-level' required='required'  name='novo_porta7'>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($dado = mysqli_fetch_assoc($result_pe)) {
											unset($id, $exp);
											$id = $dado['id'];
											$exp = $dado['funcao']; 
											echo '<option value="'.$id.'"';
											// Carrega o select box, com a função atual selecionada
											if($carrega['porta7'] == $id) echo("selected");;
											echo '>'.$exp.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 8</b></label>
								<select class='input-block-level' required='required'  name='novo_porta8'>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($dado = mysqli_fetch_assoc($result_pe)) {
											unset($id, $exp);
											$id = $dado['id'];
											$exp = $dado['funcao']; 
											echo '<option value="'.$id.'"';
											// Carrega o select box, com a função atual selecionada
											if($carrega['porta8'] == $id) echo("selected");;
											echo '>'.$exp.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 9</b></label>
								<select class='input-block-level' required='required'  name='novo_porta9'>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($dado = mysqli_fetch_assoc($result_pe)) {
											unset($id, $exp);
											$id = $dado['id'];
											$exp = $dado['funcao']; 
											echo '<option value="'.$id.'"';
											// Carrega o select box, com a função atual selecionada
											if($carrega['porta9'] == $id) echo("selected");;
											echo '>'.$exp.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 10</b></label>
								<select class='input-block-level' required='required'  name='novo_porta10'>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($dado = mysqli_fetch_assoc($result_pe)) {
											unset($id, $exp);
											$id = $dado['id'];
											$exp = $dado['funcao']; 
											echo '<option value="'.$id.'"';
											// Carrega o select box, com a função atual selecionada
											if($carrega['porta10'] == $id) echo("selected");;
											echo '>'.$exp.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 11</b></label>
								<select class='input-block-level' required='required'  name='novo_porta11'>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($dado = mysqli_fetch_assoc($result_pe)) {
											unset($id, $exp);
											$id = $dado['id'];
											$exp = $dado['funcao']; 
											echo '<option value="'.$id.'"';
											// Carrega o select box, com a função atual selecionada
											if($carrega['porta11'] == $id) echo("selected");;
											echo '>'.$exp.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 12</b></label>
								<select class='input-block-level' required='required'  name='novo_porta12'>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($dado = mysqli_fetch_assoc($result_pe)) {
											unset($id, $exp);
											$id = $dado['id'];
											$exp = $dado['funcao']; 
											echo '<option value="'.$id.'"';
											// Carrega o select box, com a função atual selecionada
											if($carrega['porta12'] == $id) echo("selected");;
											echo '>'.$exp.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 13</b></label>
								<select class='input-block-level' required='required'  name='novo_porta13'>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($dado = mysqli_fetch_assoc($result_pe)) {
											unset($id, $exp);
											$id = $dado['id'];
											$exp = $dado['funcao']; 
											echo '<option value="'.$id.'"';
											// Carrega o select box, com a função atual selecionada
											if($carrega['porta13'] == $id) echo("selected");;
											echo '>'.$exp.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 14</b></label>
								<select class='input-block-level' required='required'  name='novo_porta14'>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($dado = mysqli_fetch_assoc($result_pe)) {
											unset($id, $exp);
											$id = $dado['id'];
											$exp = $dado['funcao']; 
											echo '<option value="'.$id.'"';
											// Carrega o select box, com a função atual selecionada
											if($carrega['porta14'] == $id) echo("selected");;
											echo '>'.$exp.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 15</b></label>
								<select class='input-block-level' required='required'  name='novo_porta15'>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($dado = mysqli_fetch_assoc($result_pe)) {
											unset($id, $exp);
											$id = $dado['id'];
											$exp = $dado['funcao']; 
											echo '<option value="'.$id.'"';
											// Carrega o select box, com a função atual selecionada
											if($carrega['porta15'] == $id) echo("selected");;
											echo '>'.$exp.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 16</b></label>
								<select class='input-block-level' required='required'  name='novo_porta16'>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($dado = mysqli_fetch_assoc($result_pe)) {
											unset($id, $exp);
											$id = $dado['id'];
											$exp = $dado['funcao']; 
											echo '<option value="'.$id.'"';
											// Carrega o select box, com a função atual selecionada
											if($carrega['porta16'] == $id) echo("selected");;
											echo '>'.$exp.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 17</b></label>
								<select class='input-block-level' required='required'  name='novo_porta17'>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($dado = mysqli_fetch_assoc($result_pe)) {
											unset($id, $exp);
											$id = $dado['id'];
											$exp = $dado['funcao']; 
											echo '<option value="'.$id.'"';
											// Carrega o select box, com a função atual selecionada
											if($carrega['porta17'] == $id) echo("selected");;
											echo '>'.$exp.'</option>';
										}

										// Fechar conexão
										mysqli_close($link);
									?>
								</select>
							</div>
							<div class="form-group">
								<input type="submit" class="btn btn-large btn-primary btn-block" name="atualiza" value="Atualizar informações">
							</div>
						</form>
					</div>
					<div class="span4"></div>
				</div>
			</div>
		</section>
		<!-- /Informações da configuração -->
	</body>
</html>