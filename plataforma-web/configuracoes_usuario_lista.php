<?php
	$nivel_necessario = 5;
	// Verifica usuário logado
	require_once "codigo.usuario_sessao.php";
	
	// Incluir arquivo de conexão ao BD
	require_once "codigo.bd_conexao.php";
	
	// Função de atualização dos dados de usuário
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$id = $_POST["id"];
		$nome = $_POST["nome"];
		if (isset($_POST["desativar"])) {
			// Função para desativar usuário
			mysqli_query($link, "UPDATE usuario SET desativado = 1 WHERE id = ".$id);
			echo "<script>alert('Usuário ".$nome." (ID=".$id.") desativado!'); window.location = 'configuracoes_usuario_lista.php'</script>;";
		}
		elseif (isset($_POST["ativar"])) {
			// Função para reativar usuário
			mysqli_query($link, "UPDATE usuario SET desativado = NULL WHERE id = ".$id);
			echo "<script>alert('Usuário ".$nome." (ID=".$id.") ativado!'); window.location = 'configuracoes_usuario_lista.php'</script>;";
		}
		
		// Fecha conexão
		mysqli_close($link);
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
						<h1>Lista de usuário</h1>
					</div>
					<div class="span6">
						<ul class="breadcrumb pull-right">
							<a href="codigo.usuario_logout.php" class="btn btn-small btn-warning"><b><?php echo htmlspecialchars($_SESSION["nome"]); ?></b>, sair</a>
						</ul>
						<ul class="breadcrumb pull-right">
							<li><a href="index.php">Home</a> <span class="divider">/</span></li>
							<li><a href="configuracoes.php">Configurações do sistema</a> <span class="divider">/</span></li>
							<li class="active">Lista de usuário</li>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<!-- /Cabeçalho da página -->  

		<!-- Tabela usuário -->
		<section class="main">
			<div class="container">
				<div class="row-fluid">
					<div class="span2"></div>
					<div class="span8">
						<input class="btn-block" type="text" id="filtro_usuario" onkeyup="funcao_filtro('filtro_usuario', 'lista_usuarios', 1)" placeholder="Filtre pelo nome de usuário" autofocus>
						<table class="table table-striped" id="lista_usuarios">
							<thead>
								<tr>
									<th>ID</th>
									<th>Nome</th>
									<th>Nível</th>
									<th>Data de cadastro</th>
									<th>Situação</th>
									<th>Editar</th>
								</tr>
							</thead>
							<tbody>
								<?php
									// Consulta ao banco de dados
									$result = mysqli_query($link,"SELECT usuario.id, nome, nivel_id, nivel, data_criacao, desativado FROM usuario
										INNER JOIN usuario_nivel ON usuario.nivel_id = usuario_nivel.id
										ORDER BY nome ASC");

									// Loop dos usuários
									while ($dado = mysqli_fetch_assoc($result)){
										echo '<tr>
											<td>'.$dado["id"].'</td>
											<td>'.$dado["nome"].'</td>
											<td>'.$dado["nivel"].'</td>
											<td>'.date("d/m/Y", strtotime($dado["data_criacao"])).'</td>
											<td>
												<form name="form" method="post" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">';
													// Verifica nível do usuário cadastrado e do usuário logado
													if($dado["nivel_id"] <= $_SESSION["nivel"]) {
														echo '<input type="hidden" name="id" value="'.$dado['id'].'"/>
														<input type="hidden" name="nome" value="'.$dado['nome'].'"/>';
														if(empty($dado["desativado"])) {
															echo 'Ativado <input type="submit" class="btn btn-mini btn-danger" name="desativar" value="Desativar">';
														} else {
															echo 'Desativado <input type="submit" class="btn btn-mini btn-primary" name="ativar" value="Ativar">';
														}
													}
													else {
														echo "Bloqueado";
													}
												echo '</form>
												</td>
											<td>';
											// Verifica o nível de usuário, se for igual ou maior que 5 (colaborador ou administrador) libera o atalho das configurações -->
											if($dado["nivel_id"] <= $_SESSION["nivel"]) {
												echo '<a class="btn btn-mini btn-warning" href="configuracoes_usuario_atualiza.php?id='.$dado['id'].'">Editar</a>';
											}
											else {
												echo "Bloqueado";
											} echo "</td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
						<a class="btn btn-large btn-primary btn-block" href="configuracoes_usuario_cadastro.php">Cadastrar novo usuário</a>
					</div>
					<div class="span2"></div>
				</div>
			</div>
		</section>
		<!-- /Tabela usuário -->

		<!-- Função filtro de tabela -->
		<script src="js/filtro_tabela.js"></script>
		<!-- /Função filtro de tabela -->
	</body>
</html>