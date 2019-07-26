<!DOCTYPE html>
<?php
	// Verifica usuário logado e nível de acesso
	$nivel_necessario = 1;
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
						<li class="active"><a href="monitoramento.php">Monitoramento de salas</a></li>
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
						<h1>Monitoramento de salas</h1>
					</div>
					<div class="span6">
						<ul class="breadcrumb pull-right">
							<a href="codigo.usuario_logout.php" class="btn btn-small btn-warning"><b><?php echo htmlspecialchars($_SESSION["nome"]); ?></b>, sair</a>
						</ul>
						<ul class="breadcrumb pull-right">
							<li><a href="index.php">Home</a> <span class="divider">/</span></li>
							<li class="active">Monitoramento de salas</li>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<!-- /Cabeçalho da página -->   

		<!-- Tabela Salas -->
		<section class="main">
			<div class="container">
				<div class="row-fluid">
					<div class="span3"></div>
					<div class="span6">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Local</th>
									<th>Sala</th>
									<th>Monitoramento</th>
								</tr>
							</thead>
							<?php
								// Consulta ao banco de dados
								$result = mysqli_query($link,"SELECT sala_id, sala_nome, sala_predio, unidade_monitoramento FROM sala ORDER BY sala_predio ASC, sala_nome ASC");

								// Loop que lista as salas cadastradas no sistema
								while ($dado = mysqli_fetch_assoc($result)) {
									echo '<tbody>
										<tr>
											<td>'.$dado["sala_predio"].'</td>
											<td>'.$dado["sala_nome"].'</td>
											<td>';
												// Verifica se a sala possui registros de sensores na tabela sala_log
												$verifica = mysqli_query($link,"SELECT sala_id FROM sala_log WHERE sala_id=".$dado["sala_id"]." LIMIT 1");	
												$central = mysqli_fetch_assoc($verifica);
												if(isset ($central["sala_id"])){
													echo '<a href="monitoramento_detalhe.php?id='.$dado["sala_id"].'">Detalhes</a>';
												}
												else {
													// Verifica se a sala possui centra de monitoramento cadastrada na tabela sala
													if($dado["unidade_monitoramento"] == 1){
														echo '<b>Instalado, mas sem registros</b>';
													}
													else{
														echo "Não instalado";
													};
												};
											echo '</td>
										</tr>
									</tbody>';
								}
							?>
						</table>
					</div>
					<div class="span3"></div>
				</div>
			</div>
		</section>
		<!-- /Tabela Salas -->
	</body>
</html>