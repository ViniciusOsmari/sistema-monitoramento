<?php
	$nivel_necessario = 5;
	// Verifica usuário logado
	require_once "codigo.usuario_sessao.php";
	
	// Incluir arquivo de conexão ao BD
	require_once "codigo.bd_conexao.php";
	
	// Função de atualização dos dados de usuário
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		if (isset($_POST["editar"])) {
			// Atribui as variáveis ao formulário
			$id = $_POST['id'];
			echo "<script>window.location = 'configuracoes_atualiza.php?id=$id'</script>;";
		}
		elseif (isset($_POST["deletar"])) {
			// Atribui as variáveis ao formulário
			$id = $_POST['id'];
			$sala_velha = $_POST['sala_id'];
			mysqli_query($link, "UPDATE sala SET unidade_monitoramento = 0 WHERE sala_id = ".$sala_velha);
			$query = "DELETE FROM configuracao_rpi WHERE id = '".$id."'";
			mysqli_query($link, $query);
			echo "<script>alert('Configuração de ID ".$id." apagada!');window.location = 'configuracoes_lista.php'</script>;";
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
						<h1>Lista de configurações</h1>
					</div>
					<div class="span6">
						<ul class="breadcrumb pull-right">
							<a href="codigo.usuario_logout.php" class="btn btn-small btn-warning"><b><?php echo htmlspecialchars($_SESSION["nome"]); ?></b>, sair</a>
						</ul>
						<ul class="breadcrumb pull-right">
							<li><a href="index.php">Home</a> <span class="divider">/</span></li>
							<li><a href="configuracoes.php">Configurações do sistema</a> <span class="divider">/</span></li>
							<li class="active">Lista de configurações</li>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<!-- /Cabeçalho da página -->  

		<!-- Tabela configurações -->
		<section class="main">
			<div class="container">
				<div class="row-fluid">
					<div class="span2"></div>
					<div class="span8">
						<input class="btn-block" type="text" id="filtro_configuracoes" onkeyup="funcao_filtro('filtro_configuracoes', 'lista_configuracoes', 2)" placeholder="Filtre pelo nome da sala" autofocus>
						<table class="table table-striped" id="lista_configuracoes">
							<thead>
								<tr>
									<th>ID</th>
									<th>Local</th>
									<th>Nome da sala</th>
									<th>IP</th>
									<th>Data de cadastro</th>
									<th>Edição</th>
								</tr>
							</thead>
							<tbody>
								<?php
									// Consulta ao banco de dados
									$result = mysqli_query($link,"SELECT * FROM configuracao_rpi
										INNER JOIN sala ON configuracao_rpi.sala_id = sala.sala_id
										ORDER BY sala_nome ASC");

									// Loop dos registros existentes na tabela configuracao_rpi
									while ($dado = mysqli_fetch_assoc($result)) {
										echo '<tr>
											<td>'.$dado["id"].'</td>
											<td>'.$dado["sala_predio"].'</td>
											<td>'.$dado["sala_nome"].'</td>
											<td>'.$dado["ip"].'</td>
											<td>'.date("d/m/Y", strtotime($dado["data_criacao"])).'</td>
											<td>
											
											<form action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post">
												<input type="hidden" name="id" value="'.$dado["id"].'"/>
												<input type="hidden" name="sala_id" value="'.$dado["sala_id"].'"/>
												<input type="submit" name="editar" class="btn btn-mini btn-primary" value="Editar">';
												// Verifica o nível de acesso do usuário para liberar o botão de deletar
												if($_SESSION['nivel'] >= 10) {
													echo '<input type="button" class="btn btn-mini btn-danger" id="delete_a'.$dado['id'].'" onclick="ConfirmarDelete('.$dado['id'].')" value="Deletar">
													<input type="submit" class="btn btn-mini btn-danger pull-right" id="deletar'.$dado["id"].'" name="deletar" style="display: none;" value="Confirmar">';
												}
											echo '</form>
										</tr>';
									}
								?>
							</tbody>
						</table>
						<a class="btn btn-large btn-primary btn-block" href="configuracoes_cadastro.php">Cadastrar nova configuração</a>
					</div>
					<div class="span2"></div>
				</div>
			</div>
		</section>
		<!-- /Tabela configurações -->
		
		<!-- Função filtro de tabela -->
		<script src="js/filtro_tabela.js"></script>
		<!-- /Função filtro de tabela -->

		<!-- Função para habilitar o botão de confirmação de deletar imagem -->
		<script>
			function ConfirmarDelete(valor) {
				var elemento1 = document.getElementById("deletar".concat(valor));
				var elemento2 = document.getElementById("delete_a".concat(valor));
				if (elemento1.style.display === "none") {
					elemento1.style.display = "block";
					elemento2.value = "Cancelar";
					elemento2.classList.remove("btn-danger");
					elemento2.classList.add("btn-warning");
				} else {
					elemento1.style.display = "none";
					elemento2.value = "Deletar";
					elemento2.classList.remove("btn-warning");
					elemento2.classList.add("btn-danger");
				}
			} 
		</script>
		<!-- /Função para habilitar o botão de confirmação de deletar imagem -->
	</body>
</html>