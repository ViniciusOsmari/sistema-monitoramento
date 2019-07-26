<!DOCTYPE html>
<?php
	// Verifica usuário logado e nível de acesso
	$nivel_necessario = 5;
	require_once "codigo.usuario_sessao.php";

	// Incluir arquivo de conexão ao BD
	require_once "codigo.bd_conexao.php";

	// Define variáveis e inicializa com valores vazios
	$ip = $sala = $dht = $luminosidade = $rfid = $porta1 = $porta2 = $porta3 = $porta4 = $porta5 = $porta6 = $porta7 = $porta8 = $porta9 = $porta10 = $porta11 = $porta12 = $porta13 = $porta14 = $porta15 = $porta16 = $porta17 = "";
	$ip_err = $sala_err = $dht_err = $luminosidade_err = $rfid_err = $porta1_err = $porta2_err = $porta3_err = $porta4_err = $porta5_err = $porta6_err = $porta7_err = $porta8_err = $porta9_err = $porta10_err = $porta11_err = $porta12_err = $porta13_err = $porta14_err = $porta15_err = $porta16_err = $porta17_err = "";
 
	// Processamento de dados do formulário, quando for é enviado
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		// Vefiricação de inserção/seleção das configurações
		if(empty(trim($_POST["c_ip"]))){
			$ip_err = "Por favor insira o endereço de IP.";
		} else{
			$ip = trim($_POST["c_ip"]);
		}
		if(empty(trim($_POST["c_sala"]))){
			$sala_err = "Por favor selecione a sala.";
		} else{
			$sala = trim($_POST["c_sala"]);
		}
		if(empty(trim($_POST["c_dht"]))){
			$dht_err = "Por favor selecione o sensor de temperatura e umidade.";
		} else{
			$dht = trim($_POST["c_dht"]);
		}
		if(empty(trim($_POST["c_luminosidade"]))){
			$luminosidade_err = "Por favor selecione o sensor de luminosidade.";
		} else{
			$luminosidade = trim($_POST["c_luminosidade"]);
		}
		if(empty(trim($_POST["c_rfid"]))){
			$rfid_err = "Por favor selecione o módulo RFID.";
		} else{
			$rfid = trim($_POST["c_rfid"]);
		}
		if(empty(trim($_POST["c_porta1"]))){
			$porta1_err = "Por favor selecione a entrada/saída da porta 1.";
		} else{
			$porta1 = trim($_POST["c_porta1"]);
		}
		if(empty(trim($_POST["c_porta2"]))){
			$porta2_err = "Por favor selecione a entrada/saída da porta 2.";
		} else{
			$porta2 = trim($_POST["c_porta2"]);
		}
		if(empty(trim($_POST["c_porta3"]))){
			$porta3_err = "Por favor selecione a entrada/saída da porta 3.";
		} else{
			$porta3 = trim($_POST["c_porta3"]);
		}
		if(empty(trim($_POST["c_porta4"]))){
			$porta4_err = "Por favor selecione a entrada/saída da porta 4.";
		} else{
			$porta4 = trim($_POST["c_porta4"]);
		}
		if(empty(trim($_POST["c_porta5"]))){
			$porta5_err = "Por favor selecione a entrada/saída da porta 5.";
		} else{
			$porta5 = trim($_POST["c_porta5"]);
		}
		if(empty(trim($_POST["c_porta6"]))){
			$porta6_err = "Por favor selecione a entrada/saída da porta 6.";
		} else{
			$porta6 = trim($_POST["c_porta6"]);
		}
		if(empty(trim($_POST["c_porta7"]))){
			$porta7_err = "Por favor selecione a entrada/saída da porta 7.";
		} else{
			$porta7 = trim($_POST["c_porta7"]);
		}
		if(empty(trim($_POST["c_porta8"]))){
			$porta8_err = "Por favor selecione a entrada/saída da porta 8.";
		} else{
			$porta8 = trim($_POST["c_porta8"]);
		}
		if(empty(trim($_POST["c_porta9"]))){
			$porta9_err = "Por favor selecione a entrada/saída da porta 9.";
		} else{
			$porta9 = trim($_POST["c_porta9"]);
		}
		if(empty(trim($_POST["c_porta10"]))){
			$porta10_err = "Por favor selecione a entrada/saída da porta 10.";
		} else{
			$porta10 = trim($_POST["c_porta10"]);
		}
		if(empty(trim($_POST["c_porta11"]))){
			$porta11_err = "Por favor selecione a entrada/saída da porta 11.";
		} else{
			$porta11 = trim($_POST["c_porta11"]);
		}
		if(empty(trim($_POST["c_porta12"]))){
			$porta12_err = "Por favor selecione a entrada/saída da porta 12.";
		} else{
			$porta12 = trim($_POST["c_porta12"]);
		}
		if(empty(trim($_POST["c_porta13"]))){
			$porta13_err = "Por favor selecione a entrada/saída da porta 13.";
		} else{
			$porta13 = trim($_POST["c_porta13"]);
		}
		if(empty(trim($_POST["c_porta14"]))){
			$porta14_err = "Por favor selecione a entrada/saída da porta 14.";
		} else{
			$porta14 = trim($_POST["c_porta14"]);
		}
		if(empty(trim($_POST["c_porta15"]))){
			$porta15_err = "Por favor selecione a entrada/saída da porta 15.";
		} else{
			$porta15 = trim($_POST["c_porta15"]);
		}
		if(empty(trim($_POST["c_porta16"]))){
			$porta16_err = "Por favor selecione a entrada/saída da porta 16.";
		} else{
			$porta16 = trim($_POST["c_porta16"]);
		}
		if(empty(trim($_POST["c_porta17"]))){
			$porta17_err = "Por favor selecione a entrada/saída da porta 17.";
		} else{
			$porta17 = trim($_POST["c_porta17"]);
		}

		// Verifica os erros de entrada antes de inserir no banco de dados
		if(empty($ip_err) && empty($sala_err) && empty($dht_err) && empty($luminosidade_err) && empty($rfid_err) && empty($porta1_err) && empty($porta2_err) && empty($porta3_err) && empty($porta4_err) && empty($porta5_err) && empty($porta6_err) && empty($porta7_err) && empty($porta8_err) && empty($porta9_err) && empty($porta10_err) && empty($porta11_err) && empty($porta12_err) && empty($porta13_err) && empty($porta14_err) && empty($porta15_err) && empty($porta16_err) && empty($porta17_err)){
			// Prepare uma instrução de inserção
			$sql = "INSERT INTO configuracao_rpi (usuario_id, ip, sala_id, dht, luminosidade, rfid, porta1, porta2, porta3, porta4, porta5, porta6, porta7, porta8, porta9, porta10, porta11, porta12, porta13, porta14, porta15, porta16, porta17)
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			if($stmt = mysqli_prepare($link, $sql)){
				// Vincula variáveis à declaração preparada como parâmetros
				mysqli_stmt_bind_param($stmt, "sssssssssssssssssssssss", $param_usuario_id, $param_ip, $param_sala, $param_dht, $param_luminosidade, $param_rfid, $param_porta1, $param_porta2, $param_porta3, $param_porta4, $param_porta5, $param_porta6, $param_porta7, $param_porta8, $param_porta9, $param_porta10, $param_porta11, $param_porta12, $param_porta13, $param_porta14, $param_porta15, $param_porta16, $param_porta17);
				// Define parâmetros
				$param_usuario_id = $_SESSION["id"];
				$param_ip = $ip ;
				$param_sala= $sala;
				$param_dht= $dht;
				$param_luminosidade = $luminosidade;
				$param_rfid = $rfid;
				$param_porta1 = $porta1;
				$param_porta2 = $porta2;
				$param_porta3 = $porta3;
				$param_porta4 = $porta4;
				$param_porta5 = $porta5;
				$param_porta6 = $porta6;
				$param_porta7 = $porta7;
				$param_porta8 = $porta8;
				$param_porta9 = $porta9;
				$param_porta10 = $porta10;
				$param_porta11 = $porta11;
				$param_porta12 = $porta12;
				$param_porta13 = $porta13;
				$param_porta14 = $porta14;
				$param_porta15 = $porta15;
				$param_porta16 = $porta16;
				$param_porta17 = $porta17;

				// Tentativa de execução da declaração preparada
				if(mysqli_stmt_execute($stmt)){
					// Atualiza unidade de monitoramento instalada
					mysqli_query($link, "UPDATE sala SET unidade_monitoramento = 1 WHERE sala_id = ".$param_sala);
					// Recarrega a página
					echo "<script>alert('Configuração cadastrada com sucesso!'); window.location = 'configuracoes_cadastro.php'</script>;";
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
						<h1>Nova unidade de monitoramento</h1>
					</div>
					<div class="span6">
						<ul class="breadcrumb pull-right">
							<a href="codigo.usuario_logout.php" class="btn btn-small btn-warning"><b><?php echo htmlspecialchars($_SESSION["nome"]); ?></b>, sair</a>
						</ul>
						<ul class="breadcrumb pull-right">
							<li><a href="index.php">Home</a> <span class="divider">/</span></li>
							<li><a href="configuracoes.php">Configurações do sistema</a> <span class="divider">/</span></li>
							<li class="active">Nova unidade de monitoramento</li>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<!-- /Cabeçalho da página -->

		<!-- Formulário -->
		<section id="selecao" class="main">
			<div class="container">
				<div class="row-fluid">
					<div class="span4"></div>
					<div class="span4">
						<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
							<div class="form-group">
								<label><b>IP da Unidade de Monitoramento/Raspberry Pi</b></label>
								<input class="input-block-level" required="required" name="c_ip" type="text" placeholder="XXX.XXX.XXX.XXX" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" autofocus>
							</div>
							<div class="form-group">
								<label><b>Sala</b></label>
								<select class='input-block-level' required='required'  name='c_sala'>
									<option> </option>
									<?php
										// Consulta ao banco de dados
										$result_sala = mysqli_query($link,"SELECT * FROM sala ORDER BY sala_predio ASC, sala_nome ASC");

										// Lista as opções existentes na tabela sala
										while ($row = mysqli_fetch_assoc($result_sala)) {
											unset($id, $sala);
											$id = $row['sala_id'];
											$sala = $row['sala_nome']." ".$row['nome']. " [".$row['sala_predio']. "]"; 
											// Carrega o select box, se a sala não possuir uma configuração salva
											if($row['unidade_monitoramento'] == 0){
												echo '<option value="'.$id.'"';
												echo '>'.$sala.'</option>';
											}
										}
									?> 
								</select>
							</div>
							<div class="form-group">
								<label><b>Sensor de umidade e temperatura</b></label>
								<select class="input-block-level" required="required"  name="c_dht">
									<option> </option>
									<?php
										// Consulta ao banco de dados
										$result_dht = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=4 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($row = mysqli_fetch_assoc($result_dht)) {
											unset($id, $funcao);
											$id = $row['id'];
											$funcao = $row['funcao']; 
											echo '<option value="'.$id.'">'.$funcao.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Sensor de luminosidade</b></label>
								<select class="input-block-level" required="required"  name="c_luminosidade">
									<option> </option>
									<?php
										// Consulta ao banco de dados
										$result_lum = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=2 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($row = mysqli_fetch_assoc($result_lum)) {
											unset($id, $funcao);
											$id = $row['id'];
											$funcao = $row['funcao']; 
											echo '<option value="'.$id.'">'.$funcao.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Leitor RFID</b></label>
								<select class="input-block-level" required="required"  name="c_rfid">
									<option> </option>
									<?php
										// Consulta ao banco de dados
										$result_rfid = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=3 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($row = mysqli_fetch_assoc($result_rfid)) {
											unset($id, $funcao);
											$id = $row['id'];
											$funcao = $row['funcao']; 
											echo '<option value="'.$id.'">'.$funcao.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 1</b></label>
								<select class='input-block-level' required='required'  name='c_porta1'>
									<option> </option>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($row = mysqli_fetch_assoc($result_pe)) {
											unset($id, $funcao);
											$id = $row['id'];
											$funcao = $row['funcao']; 
											echo '<option value="'.$id.'">'.$funcao.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 2</b></label>
								<select class='input-block-level' required='required'  name='c_porta2'>
									<option> </option>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($row = mysqli_fetch_assoc($result_pe)) {
											unset($id, $funcao);
											$id = $row['id'];
											$funcao = $row['funcao']; 
											echo '<option value="'.$id.'">'.$funcao.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 3</b></label>
								<select class='input-block-level' required='required'  name='c_porta3'>
									<option> </option>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($row = mysqli_fetch_assoc($result_pe)) {
											unset($id, $funcao);
											$id = $row['id'];
											$funcao = $row['funcao']; 
											echo '<option value="'.$id.'">'.$funcao.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 4</b></label>
								<select class='input-block-level' required='required'  name='c_porta4'>
									<option> </option>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($row = mysqli_fetch_assoc($result_pe)) {
											unset($id, $funcao);
											$id = $row['id'];
											$funcao = $row['funcao']; 
											echo '<option value="'.$id.'">'.$funcao.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 5</b></label>
								<select class='input-block-level' required='required'  name='c_porta5'>
									<option> </option>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($row = mysqli_fetch_assoc($result_pe)) {
											unset($id, $funcao);
											$id = $row['id'];
											$funcao = $row['funcao']; 
											echo '<option value="'.$id.'">'.$funcao.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 6</b></label>
								<select class='input-block-level' required='required'  name='c_porta6'>
									<option> </option>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($row = mysqli_fetch_assoc($result_pe)) {
											unset($id, $funcao);
											$id = $row['id'];
											$funcao = $row['funcao']; 
											echo '<option value="'.$id.'">'.$funcao.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 7</b></label>
								<select class='input-block-level' required='required'  name='c_porta7'>
									<option> </option>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($row = mysqli_fetch_assoc($result_pe)) {
											unset($id, $funcao);
											$id = $row['id'];
											$funcao = $row['funcao']; 
											echo '<option value="'.$id.'">'.$funcao.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 8</b></label>
								<select class='input-block-level' required='required'  name='c_porta8'>
									<option> </option>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($row = mysqli_fetch_assoc($result_pe)) {
											unset($id, $funcao);
											$id = $row['id'];
											$funcao = $row['funcao']; 
											echo '<option value="'.$id.'">'.$funcao.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 9</b></label>
								<select class='input-block-level' required='required'  name='c_porta9'>
									<option> </option>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($row = mysqli_fetch_assoc($result_pe)) {
											unset($id, $funcao);
											$id = $row['id'];
											$funcao = $row['funcao']; 
											echo '<option value="'.$id.'">'.$funcao.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 10</b></label>
								<select class='input-block-level' required='required'  name='c_porta10'>
									<option> </option>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($row = mysqli_fetch_assoc($result_pe)) {
											unset($id, $funcao);
											$id = $row['id'];
											$funcao = $row['funcao']; 
											echo '<option value="'.$id.'">'.$funcao.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 11</b></label>
								<select class='input-block-level' required='required'  name='c_porta11'>
									<option> </option>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($row = mysqli_fetch_assoc($result_pe)) {
											unset($id, $funcao);
											$id = $row['id'];
											$funcao = $row['funcao']; 
											echo '<option value="'.$id.'">'.$funcao.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 12</b></label>
								<select class='input-block-level' required='required'  name='c_porta12'>
									<option> </option>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($row = mysqli_fetch_assoc($result_pe)) {
											unset($id, $funcao);
											$id = $row['id'];
											$funcao = $row['funcao']; 
											echo '<option value="'.$id.'">'.$funcao.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 13</b></label>
								<select class='input-block-level' required='required'  name='c_porta13'>
									<option> </option>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($row = mysqli_fetch_assoc($result_pe)) {
											unset($id, $funcao);
											$id = $row['id'];
											$funcao = $row['funcao']; 
											echo '<option value="'.$id.'">'.$funcao.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 14</b></label>
								<select class='input-block-level' required='required'  name='c_porta14'>
									<option> </option>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($row = mysqli_fetch_assoc($result_pe)) {
											unset($id, $funcao);
											$id = $row['id'];
											$funcao = $row['funcao']; 
											echo '<option value="'.$id.'">'.$funcao.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 15</b></label>
								<select class='input-block-level' required='required'  name='c_porta15'>
									<option> </option>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($row = mysqli_fetch_assoc($result_pe)) {
											unset($id, $funcao);
											$id = $row['id'];
											$funcao = $row['funcao']; 
											echo '<option value="'.$id.'">'.$funcao.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 16</b></label>
								<select class='input-block-level' required='required'  name='c_porta16'>
									<option> </option>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($row = mysqli_fetch_assoc($result_pe)) {
											unset($id, $funcao);
											$id = $row['id'];
											$funcao = $row['funcao']; 
											echo '<option value="'.$id.'">'.$funcao.'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label><b>Porta de expansão 17</b></label>
								<select class='input-block-level' required='required'  name='c_porta17'>
									<option> </option>
									<?php
										// Consulta ao banco de dados
										$result_pe = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao WHERE tipo_id=1 ORDER BY funcao");

										// Lista as opções existentes na tabela configuracao_porta_expansao
										while ($row = mysqli_fetch_assoc($result_pe)) {
											unset($id, $funcao);
											$id = $row['id'];
											$funcao = $row['funcao']; 
											echo '<option value="'.$id.'">'.$funcao.'</option>';
										}
									?>
								</select>
							</div>
							<button type="submit" class="btn btn-large btn-primary btn-block">Salvar configuração</button>
						</form>
					</div>
					<div class="span4"></div>
				</div>
			</div>
		</section>
		<!-- /Formulário -->
	</body>
</html>