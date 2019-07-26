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
							<li class="active">Controle de equipamento</li>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<!-- /Cabeçalho da página -->

		<!-- Plugin de tabela (DataTable) -->
		<link rel="stylesheet" href="plugins/dataTables/jquery.dataTables.min.css">
		<link rel="stylesheet" href="plugins/dataTables/buttons.dataTables.min.css">
		<link rel="stylesheet" href="plugins/dataTables/dataTables.fontAwesome.css">
		<link rel="stylesheet" href="plugins/dataTables/responsive.dataTables.min.css">
		<script src="plugins/dataTables/jquery-3.3.1.js"></script>
		<script src="plugins/dataTables/jquery.dataTables.min.js"></script>
		<script src="plugins/dataTables/dataTables.buttons.min.js"></script>
		<script src="plugins/dataTables/buttons.colVis.min.js"></script>
		<script src="plugins/dataTables/exportar/jszip.min.js"></script>
		<script src="plugins/dataTables/exportar/buttons.html5.min.js"></script>
		<script src="plugins/dataTables/dataTables.responsive.min.js"></script>
		<!-- /Plugin de tabela (DataTable) -->

		<!-- Configurações filtro da tabela (DataTable) -->
		<script language="javascript" type="text/javascript">
			$(document).ready(function() {
				var table = $('#tab_equip').DataTable( {
					responsive: true,
					"order": [[ 0, "asc" ]], // coluna para ordenação inicial da tabela
					dom: 'lfrtip',
					columns: [
						{ name: 'Equipamento', visible: true, className: 'notToggleVis'},
						{ name: 'Fabricante', visible: true },
						{ name: 'GEPOC ID', visible: false  },
						{ name: 'Patrimônio', visible: false },
						{ name: 'Número de série', visible: false },
						{ name: 'Categoria', visible: true },
						{ name: 'Funcionamento', visible: true },
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
		<!-- /Configurações filtro da tabela (DataTable) -->

		<!-- Tabela de equipamentos -->
		<section class="main container">
			<table id="tab_equip" class="hover responsive table table-striped display">
				<thead>
					<tr>
						<th>Equipamento</th>
						<th>Categoria</th>
						<th>Fabricante</th>
						<th>GEPOC ID</th>
						<th>Patrimônio</th>
						<th>Número de série</th>
						<th>Funcionamento</th>
					</tr>
				</thead>
				<tbody>
					<?php
						// Consulta ao banco de dados
						$consulta = "SELECT * FROM equipamento
							INNER JOIN equipamento_funcionamento ON equipamento.funcionamento_id = equipamento_funcionamento.id
							INNER JOIN equipamento_categoria ON equipamento.categoria_id = equipamento_categoria.id";
						$con = mysqli_query($link, $consulta);

						// Loop de equipamentos existente na tabela equipamento
						if(mysqli_num_rows($con) > 0) {  
							while($dado = mysqli_fetch_array($con)) {  
								echo '<tr>  
									<td><b><a href="equipamentos_detalhe.php?gepocid='.$dado["gepoc_id"].'">'.$dado["equipamento"].'</a></b></td>
									<td>'.$dado["categoria"].'</td>
									<td>'.$dado["fabricante"].'</td>
									<td>'.$dado["gepoc_id"].'</td>
									<td>';
										if($dado["patrimonio"] != 0) {
											echo $dado["patrimonio"];
										}
									echo '</td>
									<td>'.$dado["num_serie"].'</td>
									<td>'.$dado["funcionamento"].'</td>
								</tr>';
							}  
						}  
					?>  
				</tbody>
				<tfoot>
					<tr>
						<th>Equipamento</th>
						<th>Categoria</th>
						<th>Fabricante</th>
						<th>GEPOC ID</th>
						<th>Patrimônio</th>
						<th>Número de série</th>
						<th>Funcionamento</th>
					</tr>
				</tfoot>
			</table>
		</section>
		<!-- /Tabela de equipamentos -->
	</body>
</html>