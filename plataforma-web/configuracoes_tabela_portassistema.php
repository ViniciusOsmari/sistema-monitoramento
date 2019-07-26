<!DOCTYPE html>
<?php
	// Verifica usuário logado e nível de acesso
	$nivel_necessario = 10;
	require_once "codigo.usuario_sessao.php";
	
	// Incluir arquivo de conexão ao BD
	require_once "codigo.bd_conexao.php";
 
	// Define variáveis e inicializa com valores vazios
	$tipo = $funcao = "";

	// Função para editar ou excluir registro
	if (!isset($_POST["funcao_enviar"])) {
		// Campo a ser modificado
		$input = filter_input_array(INPUT_POST);
		$funcao = mysqli_real_escape_string($link, $input["funcao"]);
		$tipo_id = mysqli_real_escape_string($link, $input["tipo_id"]);

		// Função para editar
		if($input["action"] === 'edit')
		{
			$query = "UPDATE configuracao_porta_expansao SET tipo_id = '".$tipo_id."', funcao = '".$funcao."' WHERE id = '".$input["id"]."'";
			mysqli_query($link, $query);
		}

		// Função para deletar
		if($input["action"] === 'delete')
		{
			$query = "DELETE FROM configuracao_porta_expansao WHERE id = '".$input["id"]."'";
			mysqli_query($link, $query);
		}
	}
 
	// Processamento de dados do formulário, quando for é enviado
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if (isset($_POST["funcao_enviar"])) {
			// Vincula variáveis
			$tipo_id = trim($_POST["novo_tipo_id"]);
			$funcao = trim($_POST["novo_funcao"]);

			// Prepare uma instrução de inserção
			$sql = "INSERT INTO configuracao_porta_expansao (tipo_id, funcao) VALUES (?, ?)";
			 
			if($stmt = mysqli_prepare($link, $sql)){
				// Vincula variáveis à declaração preparada como parâmetros
				mysqli_stmt_bind_param($stmt, "ss", $param_tipo_id, $param_funcao);

				// Define parâmetros
				$param_tipo_id = $tipo_id;
				$param_funcao = $funcao;

				// Tentativa de execução da declaração preparada
				if(mysqli_stmt_execute($stmt)){
					// Exibe mensagem de conclusão e recarrega a página
					echo "<script>alert('Nova função inserida com sucesso!'); window.location = 'configuracoes_tabela_portassistema.php'</script>;";
				} else{
					echo "Algo deu errado. Por favor, tente novamente mais tarde.";
				}
			}

			// Fecha smtm
			mysqli_stmt_close($stmt);
			
			// Fecha conexão
			mysqli_close($link);
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
		<link rel="shortcut icon" href="images/favicon.png">
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
						<h1>Portas do sistema</h1>
					</div>
					<div class="span6">
						<ul class="breadcrumb pull-right">
							<a href="codigo.usuario_logout.php" class="btn btn-small btn-warning"><b><?php echo htmlspecialchars($_SESSION["nome"]); ?></b>, sair</a>
						</ul>
						<ul class="breadcrumb pull-right">
							<li><a href="index.php">Home</a> <span class="divider">/</span></li>
							<li><a href="configuracoes.php">Configurações do sistema</a> <span class="divider">/</span></li>
							<li class="active">Portas do sistema</li>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<!-- /Cabeçalho da página -->

		<!-- Plugin de editar tabela (tabledit) -->
		<script src="plugins/tabledit/jquery.min.js"></script>
		<script src="plugins/tabledit/jquery.tabledit.min.js"></script>
		<!-- /Plugin de editar tabela (tabledit) -->

		<!-- Configurações do plugin de editar tabela (tabledit) -->
		<script>  
			$(document).ready(function(){  
				$('#lista_configuracao_porta_expansao').Tabledit({
					url:'',
					toolbarHeaderClass: '',
					toolbarClass: '',
					// deleteButton: false, //comando para remover botão deletar
					columns:{
						identifier:[0, "id"],
						editable:[[1, 'tipo_id', '{"1": "Externa", "2": "Interna - Luminosidade", "3": "Interna - RFID", "4": "Interna - Temperatura e umidade"}'], [2, 'funcao']]
					},
					restoreButton:true,
					onSuccess:function(data, textStatus, jqXHR)
					{
						if(data.action == 'delete')
						{
							$('#'+data.id).remove();
						}
					}
				});
			});  
		</script>
		<!-- /Configurações do plugin de editar tabela (tabledit) -->

		<!-- Tabela -->
		<section class="main">
			<div class="container">
				<div class="row-fluid">
					<div class="span3"></div>
					<div class="row span6">
						<input class="btn-block" type="text" id="filtro_funcao" onkeyup="funcao_filtro('filtro_funcao', 'lista_configuracao_porta_expansao', 2)" placeholder="Filtre pela função">
						<table id="lista_configuracao_porta_expansao" class="table table-striped">
							<thead>
								<tr>
									<th>ID</th>
									<th>Tipo</th>
									<th>Função</th>
								</tr>
							</thead>
							<tbody>
								<?php
									// Seleção de dados do banco de dados
									$result = mysqli_query($link, "SELECT configuracao_porta_expansao.id, tipo, funcao FROM configuracao_porta_expansao
										INNER JOIN configuracao_porta_expansao_tipo ON configuracao_porta_expansao.tipo_id = configuracao_porta_expansao_tipo.id
										ORDER BY tipo ASC, funcao ASC");

									// Lista os registros existentes na tabela configuracao_porta_expansao
									while($coluna = mysqli_fetch_array($result))
									{
										echo '
										<tr>
											<td>'.$coluna["id"].'</td>
											<td>'.$coluna["tipo"].'</td>
											<td>'.$coluna["funcao"].'</td>
										</tr>';
									}
								?>
							</tbody>
						</table>
						<form class="box"action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
							<label><b>Selecione o tipo de função</b></label>
							<div class="form-group">
								<select class="input-block-level form-group" required="required" name="novo_tipo_id">
									<option></option>
									<?php
										// Consulta ao banco de dados
										$result = mysqli_query($link,"SELECT * FROM configuracao_porta_expansao_tipo ORDER BY tipo ASC");
										
										// Lista as opções existentes na tabela equipamento_categoria
										while ($row = mysqli_fetch_assoc($result)) {
											unset($id, $categoria);
											$id = $row['id'];
											$categoria = $row['tipo']; 
											echo '<option value="'.$id.'">'.$categoria.'</option>';
										}
									?>
								</select>
							</div>
							<label><b>Digite a função</b></label>
							<div class="form-group">
								<input type="text" name="novo_funcao" required="required" maxlength="40" class="input-block-level" value="<?php echo $funcao; ?>">
							</div>
							<div class="form-group">
								<input type="submit" name="funcao_enviar" class="btn btn-primary btn-block" value="Cadastrar nova função">
							</div>
						</form>
					</div>
					<div class="span3"></div>
				</div>  
			</div>  
		</section>
		<!-- /Tabela -->

		<!-- Função filtro de tabela -->
		<script src="js/filtro_tabela.js"></script>
		<!-- /Função filtro de tabela -->
	</body>
</html>