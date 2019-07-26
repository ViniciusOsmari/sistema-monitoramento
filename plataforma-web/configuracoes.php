<!DOCTYPE html>
<?php
	// Verifica usuário logado e nível de acesso
	$nivel_necessario = 5;
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
							<li class="active">Configurações do sistema</li>
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
					<div class="span4 center">
						<div class="box">
							<h2>Alarmes</h2>
							<a class="btn btn-large btn-primary btn-block" href="configuracoes_alarmes.php">Abrir</a>
						</div>
						<p>&nbsp;</p>
						<div class="box">
							<h2>Equipamentos</h2>
							<a class="btn btn-large btn-primary btn-block" href="equipamentos_cadastro.php">Cadastrar novo</a>
							<a class="btn btn-large btn-primary btn-block" href="equipamentos_atualiza.php">Atualizar informações</a>
							<a class="btn btn-large btn-primary btn-block" href="equipamentos_imagens.php">Imagens de equipamentos</a>
						</div>
						<p>&nbsp;</p>
					</div>
					<div class="span4 center">
						<?php
							// Bloqueia seção para usuário de menores que 10 (Administrador)
							if($_SESSION['nivel'] >= 10) {
								echo'<div class="box">
									<h2>Tabelas Adicionais</h2>
									<a class="btn btn-large btn-primary btn-block" href="configuracoes_tabela_categoria.php">Categorias de equipamentos</a>
									<a class="btn btn-large btn-primary btn-block" href="configuracoes_tabela_funcionamento.php">Funcionamento de equipamentos</a>
									<a class="btn btn-large btn-primary btn-block" href="configuracoes_tabela_professor.php">Professores</a>
									<a class="btn btn-large btn-primary btn-block" href="configuracoes_tabela_portassistema.php">Portas de expansão</a>
									<a class="btn btn-large btn-primary btn-block" href="configuracoes_tabela_sala.php">Salas</a>
								</div>
								<p>&nbsp;</p>';
							}
						?>
					</div>
					<div class="span4 center">
						<div class="box">
							<h2>Unidade de monitoramento</h2>
							<a class="btn btn-large btn-primary btn-block" href="configuracoes_cadastro.php">Nova unidade</a>
							<a class="btn btn-large btn-primary btn-block" href="configuracoes_lista.php">Lista de unidades cadastradas</a>
						</div>
						<p>&nbsp;</p>
						<div class="box">
							<h2>Usuários</h2>
							<a class="btn btn-large btn-primary btn-block" href="configuracoes_usuario_cadastro.php">Cadastrar novo(a)</a>
							<!-- verifica o nível de usuário, se for igual ou maior que 5 (colaborador ou administrador) libera o atalho -->
							<?php if($_SESSION['nivel'] >= 5) {echo'<a class="btn btn-large btn-primary btn-block" href="configuracoes_usuario_lista.php">Lista de usuários</a>';}?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- /Atalhos -->
	</body>
</html>