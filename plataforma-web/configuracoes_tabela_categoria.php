<!DOCTYPE html>
<?php
	// Verifica usuário logado e nível de acesso
	$nivel_necessario = 10;
	require_once "codigo.usuario_sessao.php";
	
	// Incluir arquivo de conexão ao BD
	require_once "codigo.bd_conexao.php";

	// Define variáveis e inicializa com valores vazios
	$categoria = $categoria_err = "";

	// Função para editar ou excluir registro
	if (!isset($_POST["categoria_enviar"])) {
		// Campo a ser modificado
		$input = filter_input_array(INPUT_POST);
		$categoria = mysqli_real_escape_string($link, $input["categoria"]);

		// Função para editar
		if($input["action"] === 'edit')
		{
			$query = "UPDATE equipamento_categoria SET categoria = '".$categoria."' WHERE id = '".$input["id"]."'";
			mysqli_query($link, $query);
		}

		// Função para deletar
		if($input["action"] === 'delete')
		{
			$query = "DELETE FROM equipamento_categoria WHERE id = '".$input["id"]."'";
			mysqli_query($link, $query);
		}
	}
 
	// Processamento de dados do formulário, quando for é enviado
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if (isset($_POST["categoria_enviar"])) {
			// Validação do novo categoria
			if(empty(trim($_POST["nova_categoria"]))){
				$categoria_err = "Por favor insira um categoria.";
			} else{
				// Prepare uma select
				$sql = "SELECT id FROM equipamento_categoria WHERE categoria = ?";
				
				if($stmt = mysqli_prepare($link, $sql)){
					// Vincular variáveis à declaração preparada como parâmetros
					mysqli_stmt_bind_param($stmt, "s", $param_categoria);
					
					// Define parâmetros
					$param_categoria = trim($_POST["nova_categoria"]);
					
					// Tentativa de execução da declaração preparada
					if(mysqli_stmt_execute($stmt)){
						// Armazena resultado
						mysqli_stmt_store_result($stmt);
						
						if(mysqli_stmt_num_rows($stmt) == 1){
							$categoria_err = "Essa categoria já existe.";
						} else{
							$categoria = trim($_POST["nova_categoria"]);
						}
					} else{
						echo "Opa! Algo deu errado. Por favor, tente novamente mais tarde.";
					}
				}
				 
				// fecha
				mysqli_stmt_close($stmt);
			}

			// Verifica os erros de entrada antes de inserir no banco de dados
			if(empty($categoria_err)){
				
				// Prepare uma instrução de inserção
				$sql = "INSERT INTO equipamento_categoria (categoria) VALUES (?)";
				 
				if($stmt = mysqli_prepare($link, $sql)){
					// Vincula variáveis à declaração preparada como parâmetros
					mysqli_stmt_bind_param($stmt, "s", $param_categoria);
					
					// Define parâmetros
					$param_categoria = $categoria;
					
					// Tentativa de execução da declaração preparada
					if(mysqli_stmt_execute($stmt)){
						// Exibe mensagem de conclusão e recarrega a página
						echo "<script>alert('Categoria inserida com sucesso!'); window.location = 'configuracoes_tabela_categoria.php'</script>;";
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
						<h1>Tabela de categoria de equipamentos</h1>
					</div>
					<div class="span6">
						<ul class="breadcrumb pull-right">
							<a href="codigo.usuario_logout.php" class="btn btn-small btn-warning"><b><?php echo htmlspecialchars($_SESSION["nome"]); ?></b>, sair</a>
						</ul>
						<ul class="breadcrumb pull-right">
							<li><a href="index.php">Home</a> <span class="divider">/</span></li>
							<li><a href="configuracoes.php">Configurações do sistema</a> <span class="divider">/</span></li>
							<li class="active">Tabela de categoria de equipamentos</li>
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
				$('#lista_equipamento_categoria').Tabledit({
					url:'',
					toolbarHeaderClass: '',
					toolbarClass: '',
					// deleteButton: false, //comando para remover botão deletar
					columns:{
						identifier:[0, "id"],
						editable:[[1, 'categoria']]
					},
					restoreButton:true,
					onSuccess:function(data, textStatus, jqXHR)
					{
						window.location = 'index.php'
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
						<input class="btn-block" type="text" id="filtro_categoria" onkeyup="funcao_filtro('filtro_categoria', 'lista_equipamento_categoria', 1)" placeholder="Filtre pela categoria">
						<table id="lista_equipamento_categoria" class="table table-striped">
							<thead>
								<tr>
									<th>ID</th>
									<th>Categoria</th>
								</tr>
							</thead>
							<tbody>
								<?php
									// Seleção de dados do banco de dados
									$result = mysqli_query($link, "SELECT * FROM equipamento_categoria ORDER BY categoria ASC");

									// Lista os registros existentes na tabela equipamento_categoria
									while($coluna = mysqli_fetch_array($result))
									{
										echo '
										<tr>
											<td>'.$coluna["id"].'</td>
											<td>'.$coluna["categoria"].'</td>
										</tr>';
									}
								?>
							</tbody>
						</table>
						<form class="box"action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
							<div id="novo" class="form-group <?php echo (!empty($categoria_err)) ? 'has-error' : ''; ?>">
								<label><b>Adionar nova categoria</b></label>
								<input type="text" name="nova_categoria" maxlength="50" class="input-block-level" placeholder="Categoria" value="<?php echo $categoria; ?>">
								<span class="help-block"><?php echo $categoria_err; ?></span>
							</div>
							<div class="form-group">
								<input type="submit" name="categoria_enviar" class="btn btn-primary btn-block" value="Cadastrar nova categoria">
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