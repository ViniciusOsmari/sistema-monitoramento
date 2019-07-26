<!DOCTYPE html>
<?php
	// Verifica usuário logado e nível de acesso
	$nivel_necessario = 5;
	require_once "codigo.usuario_sessao.php";
	
	// Incluir arquivo de conexão ao BD
	require_once "codigo.bd_conexao.php";
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
						<h1>Alarmes</h1>
					</div>
					<div class="span6">
						<ul class="breadcrumb pull-right">
							<a href="codigo.usuario_logout.php" class="btn btn-small btn-warning"><b><?php echo htmlspecialchars($_SESSION["nome"]); ?></b>, sair</a>
						</ul>
						<ul class="breadcrumb pull-right">
							<li><a href="index.php">Home</a> <span class="divider">/</span></li>
							<li><a href="configuracoes.php">Configurações do sistema</a> <span class="divider">/</span></li>
							<li class="active">Alarmes</li>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<!-- /Cabeçalho da página -->   

		<!-- Tabela alarme -->
		<section class="main">
			<div class="container">
				<div class="row-fluid">
					<div class="span2"></div>
					<div class="span8">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>ID</th>
									<th>Data</th>
									<th>Hora</th>
									<th>Sala</th>
									<th>Motivo</th>
								</tr>
							</thead>
							<?php
								// Consulta ao banco de dados
								$result = mysqli_query($link,"SELECT * FROM alarme INNER JOIN sala ON alarme.sala_id = sala.sala_id ORDER BY data DESC");

								// Lista os registros existentes na tabela alarme
								while ($dado = mysqli_fetch_assoc($result)) {
									echo '<tbody>
										<tr>
											<td>'.$dado["id"].'</td>
											<td>'.date("d/m/Y", strtotime($dado["data"])).'</td>
											<td>'.date("H:i:s", strtotime($dado["data"])).'</td>
											<td>'.$dado["sala_nome"].'</td>
											<td>'.$dado["motivo"].'</td>
										</tr>
									</tbody>';
								}
							?>
						</table>
					</div>
					<div class="span2"></div>
				</div>
			</div>
		</section>
		<!-- /Tabela alarme -->
	</body>
</html>