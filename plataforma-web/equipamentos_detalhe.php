<!DOCTYPE html>
<?php
	// Verifica usuário logado e nível de acesso
	$nivel_necessario = 1;
	require_once "codigo.usuario_sessao.php";

	// Incluir arquivo de conexão ao BD
	require_once "codigo.bd_conexao.php";

	// Flag para sinalização se é para carregar os dados
	$carrega = 0;

	// Função de atualização dos dados de usuário
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		if (isset($_POST["devolver"])) {
			// Atribui as variáveis
			$id = $_POST["id"];
			$gepocid = $_POST["gepocid"];

			// Prepare uma instrução de atualização
			$sqlupdate = "UPDATE equipamento_log SET data_devolucao = CURRENT_TIMESTAMP WHERE id='$id'";
			if(mysqli_query($link, $sqlupdate)){
				echo "<script>alert('Equipamento devolvido!'); window.location = 'equipamentos_detalhe.php?gepocid=$gepocid'</script>;";
			} else {
				echo "ERRO: Não foi possível executar $sqlupdate." . mysqli_error($link);
			}

			// Fecha conexão
			mysqli_close($link);
		}
	}

	// Função para carregar os valores atuais do banco de dados
	if ($carrega == 0) {
		// Carrega GEPOC ID, da página de equipamentos ou do endereço
		if(empty($_POST["gepocid"])) {
			$gepocid = $_GET["gepocid"];
		} else {
			$gepocid = $_POST["gepocid"];
		}
		
		// Validação de GEPOC ID cadastrado. Prepara uma declaração SELECT
		if ($stmt = mysqli_prepare($link, "SELECT equipamento FROM equipamento WHERE gepoc_id=? LIMIT 1")) {
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
						INNER JOIN equipamento_professor ON equipamento.prof_responsavel_id = equipamento_professor.id
						INNER JOIN equipamento_funcionamento ON equipamento.funcionamento_id = equipamento_funcionamento.id
						INNER JOIN equipamento_categoria ON equipamento.categoria_id = equipamento_categoria.id
						WHERE gepoc_id = "'.$gepocid.'"');
					$carrega = mysqli_fetch_assoc($result);

					// Busca valor
					mysqli_stmt_fetch($stmt);
				} else {
					// Mensagem de equipamento não cadastrado e redireciona para página de equipamentos
					echo '<script>alert("GEPOC ID não cadastrado no sistema!"); window.location = "equipamentos.php"</script>';
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
						<li class="active"><a href="equipamentos.php">Controle de equipamentos</a></li>
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
						<h1>Controle de equipamentos</h1>
					</div>
					<div class="span6">
						<ul class="breadcrumb pull-right">
							<a href="codigo.usuario_logout.php" class="btn btn-small btn-warning"><b><?php echo htmlspecialchars($_SESSION["nome"]); ?></b>, sair</a>
						</ul>
						<ul class="breadcrumb pull-right">
							<li><a href="index.php">Home</a> <span class="divider">/</span></li>
							<li><a href="equipamentos.php">Controle de equipamento</a> <span class="divider">/</span></li>
							<li class="active">Detalhes de equipamento</li>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<!-- /Cabeçalho da página -->

		<!-- Informações do equipamento -->
		<section class="main">
			<!-- Detalhes do equipamento -->
			<div class="container">
				<h2 class="center">Detalhes do equipamento</h2>
				<div class="row-fluid">
					<div class="span5">
						<p>&nbsp;</p>
						<!-- verifica se existe imagem cadastrada -->
						<?php if(empty($carrega['imagem'])) {
							echo  '<img src="images/equipamentos/sem-imagem.jpg" alt="Equipamento sem imagem disponível" class="img">';
						}
						else {
							// verifica se a imagem existe
							if(file_exists("images/equipamentos/".$carrega['imagem'])){
								echo '<img src="images/equipamentos/'.$carrega['imagem'].'" alt=" " class="img">';
							} else {
								echo '<img src="images/equipamentos/imagem-invalida.jpg" alt="Equipamento sem imagem disponível" class="img">';
							}
						}?>
					</div>
					<div class="span7">
						<table class="table table-striped">
							<thead>
								<tr>
									<td><b>GEPOC ID</b></td>
									<td><?php echo $carrega['gepoc_id'];?></td>
								</tr>
								<tr>
									<td><b>Patrimônio</b></td>
									<td><?php if($carrega['patrimonio'] != 0) {echo $carrega['patrimonio'];}?></td>
								</tr>
								<tr>
									<td><b>Número de série</b></td>
									<td><?php echo $carrega['num_serie'];?></td>
								</tr>
								<tr>
									<td><b>Equipamento</b></td>
									<td><?php echo $carrega['equipamento'];?></td>
								</tr>
								<tr>
									<td><b>Fabricante</b></td>
									<td><?php echo $carrega['fabricante'];?></td>
								</tr>
								<tr>
									<td><b>Categoria</b></td>
									<td><?php echo $carrega['categoria'];?></td>
								</tr>
								<tr>
									<td><b>Professor responsável</b></td>
									<td><?php echo $carrega['nome'];?></td>
								</tr>
								<tr>
									<td><b>Funcionamento</b></td>
									<td><?php echo $carrega['funcionamento'];?></td>
								</tr>
								<tr>
									<td><b>Localização</b></td>
									<td><?php echo $carrega['localizacao'];?></td>
								</tr>
								<?php
									// Verifica o nível de acesso do usuário para liberar informações extra
									if($_SESSION['nivel'] >= 5) {
										echo "<tr>
											<td><b>Etiqueta RFID</b></td>
											<td>".$carrega['rfid']."</td>
										</tr>
										<tr>
											<td><b>Data de cadastro</b></td>
											<td>".date('d/m/Y H:i', strtotime($carrega['data_cadastro']))."</td>
										</tr>";
									}
								?>
								<tr>
									<td><b>Situação</b></td>
									<td>
										<?php
											// Consulta ao banco de dados
											$consulta = "SELECT id, data_retirada, data_devolucao FROM equipamento_log
												WHERE equipamento_id=$gepocid ORDER BY data_retirada DESC LIMIT 1";
											$con = mysqli_query($link, $consulta);
											$status = mysqli_fetch_array($con);

											// verifica se existe algum registro, caso exista, verifica se a última retirada possui data de devolução (se possuir marca como disponível, se não marca como emprestado)
											if(mysqli_num_rows($con) != 0) { 
												if($status['data_devolucao'] != 0) {
													echo "Disponível";
												}
												else {
													echo 'Emprestado';
													// Verifica o nível de acesso do usuário para liberar o botão de devolução
													if($_SESSION['nivel'] >= 5) {
														echo '<form class="pull-right" name="form" method="post" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">
															<input type="hidden" name="id" value="'.$status["id"].'"/> 
															<input type="hidden" name="gepocid" value="'.$carrega["gepoc_id"].'"/> 
															<input type="submit" class="btn btn-mini btn-warning" name="devolver" value="Marcar como devolvido">
														</form>';
													}
												}
											}
											else { echo "Disponível";}
										?>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<?php
											// Verifica o nível de acesso do usuário para liberar o botão de edição
											if($_SESSION['nivel'] >= 5) {
												echo '<a class="btn btn-large btn-primary btn-block" href="equipamentos_atualiza.php?gepocid='.$carrega["gepoc_id"].'">Editar informações</a>';
											}
										?>
									</td>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
			<!-- /Detalhes do Equipamento -->
			
			<!-- Histórico do Equipamento -->
			<div class="container">
				<h2 class="center">Histórico de retiradas</h2>
				<table class="table table-striped">
					<?php
						// Consulta ao banco de dados
						$consulta = "SELECT * FROM equipamento_log
							INNER JOIN sala ON equipamento_log.sala_id = sala.sala_id
							INNER JOIN usuario ON equipamento_log.usuario_id = usuario.id
							WHERE equipamento_id=$gepocid ORDER BY data_retirada DESC LIMIT 10";
						$con = mysqli_query($link, $consulta);
						
						// Fechar conexão
						mysqli_close($link);

						// Loop de registros
						if(mysqli_num_rows($con) > 0) {
							echo '
							<thead>
								<tr>
									<th>Usuário</th>
									<th>Data de retirada</th>
									<th>Data de devolução</th>
								</tr>
							</thead>
							<tbody>';
							while($dado = mysqli_fetch_array($con)) {  
								echo '<tr>  
									<td>'.$dado["nome"].'</td>
									<td>'.date('d/m/Y H:i', strtotime($dado['data_retirada'])).'</td>
									<td>';
										if ($dado['data_devolucao'] != 0){
											echo date('d/m/Y H:i', strtotime($dado['data_devolucao']));
										}
									'</td>
								</tr>';
							}
							echo '
							</tbody>';
						}
						else{
							echo "<div class='center'><b>Equipamento sem retiradas<b></div>";
						}
					?>
				</table>
			</div>
			<!-- /Histórico do Equipamento -->
		</section>
		<!-- /Informações do equipamento -->
	</body>
</html>