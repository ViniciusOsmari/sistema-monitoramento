<!DOCTYPE html>
<?php
	// Verifica usuário logado e nível de acesso
	$nivel_necessario = 1;
	require_once "codigo.usuario_sessao.php";

	// Incluir arquivo de conexão ao BD
	require_once "codigo.bd_conexao.php";

	// Carrega ID da sala, da página de monitoramento ou do endereço
	if(empty($_POST["id"])) {
		$salaid = $_GET["id"];
	} else {
		$salaid = $_POST["sala_id"];
	}

	// Caso não identifique uma ID, redireciona para a página de monitoramento
	if(empty($salaid)) {
		header("location: monitoramento.php");
	}
	
	// Validação de ID da sala. Prepara uma declaração SELECT
	if ($stmt = mysqli_prepare($link, "SELECT id FROM sala_log WHERE sala_id=?")) {
		// Vincular variáveis à declaração preparada como parâmetros
		mysqli_stmt_bind_param($stmt, "s", $salaid);
		// Tentativa de execução da declaração preparada
		if(mysqli_stmt_execute($stmt)){
			// Armazena o resultado
			mysqli_stmt_store_result($stmt);

			// Verifica se existe algum registro para a sala
			if(mysqli_stmt_num_rows($stmt) == 0){
				// Mensagem de registro não encontrado e redireciona para página de monitoramento
				echo '<script>alert("Nenhum registro encontrado para essa sala!"); window.location = "monitoramento.php"</script>;';
			}
		}
		// Fechar declaração
		mysqli_stmt_close($stmt);
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
							<li><a href="monitoramento.php">Monitoramento de salas</a> <span class="divider">/</span></li>
							<li class="active">Detalhe</li>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<!-- /Cabeçalho da página -->   

		<!-- Plugin de tabela (DataTable) -->
		<script src="plugins/dataTables/jquery-3.3.1.js"></script>
		<script src="plugins/dataTables/jquery.dataTables.min.js"></script>
		<script src="plugins/dataTables/dataTables.buttons.min.js"></script>
		<script src="plugins/dataTables/buttons.colVis.min.js"></script>
		<link rel="stylesheet" href="plugins/dataTables/jquery.dataTables.min.css">
		<link rel="stylesheet" href="plugins/dataTables/buttons.dataTables.min.css">
		<link rel="stylesheet" href="plugins/dataTables/dataTables.fontAwesome.css">
		<script src="plugins/dataTables/exportar/jszip.min.js"></script>
		<script src="plugins/dataTables/exportar/buttons.html5.min.js"></script>
		<!-- /Plugin de tabela (DataTable) -->

		<!-- Configurações do plugin da tabela (DataTable) -->
		<script language="javascript" type="text/javascript">
			$(document).ready(function() {
				var table = $('#tab_sala_log').DataTable( {
					responsive: true,
					"order": [[ 1, "desc" ]], // coluna para ordenação inicial da tabela
					dom: 'lfrtip',
					columns: [
						{ name: 'ID', className: 'notToggleVis'},
						{ name: 'Data e Hora' },
						{ name: 'Temperatura (ºC)' },
						{ name: 'Umidade (%)' },
						{ name: 'Luminosidade' , visible: false },
						{ name: 'Porta 1' , visible: false },
						{ name: 'Porta 2' , visible: false },
						{ name: 'Porta 3' , visible: false },
						{ name: 'Porta 4' , visible: false },
						{ name: 'Porta 5' , visible: false },
						{ name: 'Porta 6' , visible: false },
						{ name: 'Porta 7' , visible: false },
						{ name: 'Porta 8' , visible: false },
						{ name: 'Porta 9' , visible: false },
						{ name: 'Porta 10' , visible: false },
						{ name: 'Porta 11' , visible: false },
						{ name: 'Porta 12' , visible: false },
						{ name: 'Porta 13' , visible: false },
						{ name: 'Porta 14' , visible: false },
						{ name: 'Porta 15' , visible: false },
						{ name: 'Porta 16' , visible: false },
						{ name: 'Porta 17' , visible: false },
					],
					buttons: [
						{
							text: 'Escolher colunas',
							extend: 'colvis', 
							columns: ':not(.notToggleVis)'
						}
						<?php
							// Verifica o nível de acesso do usuário para liberar botão exportar
							if($_SESSION['nivel'] >= 5) {
							echo ",{
								text: 'Exportar', extend: 'collection',
								buttons: [
									{text: 'Copiar', autoFilter: true, extend:'copy', title: null},
									{extend: 'excelHtml5', autoFilter: true, sheetName: 'Exported data', title: null, filename: 'Monitoramento'}
								]}";
							}
						?>
					]
				});
				table.buttons().container()
					.appendTo( $('.dataTables_length', table.table().container() ) );
			});
		</script>
		<!-- /Configurações do plugin da tabela (DataTable) -->

		<!-- Tabela-->
		<section class="container">
			<table id="tab_sala_log" class="hover responsive table table-striped display">
				<thead>
					<tr>
						<th>ID</th>
						<th>Data e Hora</th>
						<th>Temperatura (ºC)</th>
						<th>Umidade (%)</th>
						<th>Luminosidade</th>
						<th>Porta 1</th>
						<th>Porta 2</th>
						<th>Porta 3</th>
						<th>Porta 4</th>
						<th>Porta 5</th>
						<th>Porta 6</th>
						<th>Porta 7</th>
						<th>Porta 8</th>
						<th>Porta 9</th>
						<th>Porta 10</th>
						<th>Porta 11</th>
						<th>Porta 12</th>
						<th>Porta 13</th>
						<th>Porta 14</th>
						<th>Porta 15</th>
						<th>Porta 16</th>
						<th>Porta 17</th>
					</tr>
				</thead>
				<tbody>
					<?php
						// Consulta ao banco de dados
						$consulta = "SELECT * FROM sala_log WHERE sala_id =".$salaid;
						$con = mysqli_query($link, $consulta);
						
						// Loop dos registros existentes para a sala selecionada
						if(mysqli_num_rows($con) > 0) {  
							while($dado = mysqli_fetch_array($con)) {  
								echo '<tr>
									<td>'.$dado["id"].'</td>
									<td>'.date("d/m/Y H:i:s", strtotime($dado["data"])).'</td>
									<td>'.$dado["temperatura"].'</td>
									<td>'.$dado["umidade"].'</td>
									<td>'.$dado["luminosidade"].'</td>
									<td>'.$dado["porta1"].'</td>
									<td>'.$dado["porta2"].'</td>
									<td>'.$dado["porta3"].'</td>
									<td>'.$dado["porta4"].'</td>
									<td>'.$dado["porta5"].'</td>
									<td>'.$dado["porta6"].'</td>
									<td>'.$dado["porta7"].'</td>
									<td>'.$dado["porta8"].'</td>
									<td>'.$dado["porta9"].'</td>
									<td>'.$dado["porta10"].'</td>
									<td>'.$dado["porta11"].'</td>
									<td>'.$dado["porta12"].'</td>
									<td>'.$dado["porta13"].'</td>
									<td>'.$dado["porta14"].'</td>
									<td>'.$dado["porta15"].'</td>
									<td>'.$dado["porta16"].'</td>
									<td>'.$dado["porta17"].'</td>
								</tr>';
							}
						}
					?>  
				</tbody>
				<tfoot>
					<tr>
						<th>ID</th>
						<th>Data e Hora</th>
						<th>Temperatura (ºC)</th>
						<th>Umidade (%)</th>
						<th>Luminosidade</th>
						<th>Porta 1</th>
						<th>Porta 2</th>
						<th>Porta 3</th>
						<th>Porta 4</th>
						<th>Porta 5</th>
						<th>Porta 6</th>
						<th>Porta 7</th>
						<th>Porta 8</th>
						<th>Porta 9</th>
						<th>Porta 10</th>
						<th>Porta 11</th>
						<th>Porta 12</th>
						<th>Porta 13</th>
						<th>Porta 14</th>
						<th>Porta 15</th>
						<th>Porta 16</th>
						<th>Porta 17</th>
					</tr>
				</tfoot>
			</table>
		</section>
		<!-- /Tabela -->
	</body>
</html>