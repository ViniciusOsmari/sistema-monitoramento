<?php
	// Verifica usuário logado e nível de acesso
	$nivel_necessario = 1;
	require_once "codigo.usuario_sessao.php";
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
						<li class="active"><a href="index.php">Home</a></li>
						<li><a href="equipamentos.php">Controle de equipamentos</a></li>
						<li><a href="monitoramento.php">Monitoramento de salas</a></li>
						<?php if($menu_config){echo $menu_config;}?>
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
						<h1>Sistema de monitoramento</h1>
					</div>
					<div class="span6">
						<ul class="breadcrumb pull-right">
							<a href="codigo.usuario_logout.php" class="btn btn-small btn-warning"><b><?php echo htmlspecialchars($_SESSION["nome"]); ?></b>, sair</a>
						</ul>
						<ul class="breadcrumb pull-right">
							<li class="active">Home</li>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<!-- /Cabeçalho da página -->

		<!-- Atalhos -->
		<section class="main">
			<div class="container">
				<div class="row-fluid">
					<div class="span4"></div>
					<div class="span4">
						<a class="btn btn-large btn-primary btn-block" href="equipamentos.php">Controle de equipamentos</a>
						<a class="btn btn-large btn-primary btn-block" href="monitoramento.php">Monitoramento de salas</a>
						<!-- verifica o nível de usuário, se for igual ou maior que 5 (colaborador ou administrador) libera o atalho das configurações -->
						<?php
							if($_SESSION["nivel"] >= 5) {
								echo '<a class="btn btn-large btn-primary btn-block" href="configuracoes.php">Configurações do sistema</a>';
							}
						?>
						<a class="btn btn-large btn-warning btn-block" href="codigo.usuario_logout.php">Fazer Logout</a>
					</div>
					<div class="span4"></div>
				</div>
			</div>
		</section>
		<!-- /Atalhos -->
	</body>
</html>