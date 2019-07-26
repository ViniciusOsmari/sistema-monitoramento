<!DOCTYPE html>
<?php
	// Verifica usuário logado e nível de acesso
	$nivel_necessario = 5;
	require_once "codigo.usuario_sessao.php";

	$arquivo = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// Função de deletar arquivo
		if (isset($_POST["remover"])) {
			$arquivo=$_POST["arquivo"];
			$deletar = "images/equipamentos/".$arquivo;
			unlink($deletar) or die("Erro ao remover arquivo");
			echo '<script>alert("O arquivo ('.$arquivo.') foi removido!")</script>';
		} else {
			// Função para envio de arquivo
			$ds = DIRECTORY_SEPARATOR;
			$destino = "images/equipamentos";
			if (!empty($_FILES)) {
				$arquivotemporario = $_FILES["file"]["tmp_name"];
				$caminhodestino = dirname( __FILE__ ).$ds.$destino.$ds;
				$arquivodestino = $caminhodestino.$_FILES["file"]["name"];
				move_uploaded_file($arquivotemporario,$arquivodestino);
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
						<h1>Imagens de equipamentos</h1>
					</div>
					<div class="span6">
						<ul class="breadcrumb pull-right">
							<a href="codigo.usuario_logout.php" class="btn btn-small btn-warning"><b><?php echo htmlspecialchars($_SESSION["nome"]); ?></b>, sair</a>
						</ul>
						<ul class="breadcrumb pull-right">
							<li><a href="index.php">Home</a> <span class="divider">/</span></li>
							<li><a href="configuracoes.php">Configurações do sistema</a> <span class="divider">/</span></li>
							<li class="active">Imagens de equipamentos</li>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<!-- /Cabeçalho da página -->

		<!-- Plugin de arrastar e soltar arquivo (drag and drop) (dropzone) -->
		<script src="plugins/dropzone/dropzone.min.js"></script>
		<link rel="stylesheet" href="plugins/dropzone/dropzone.min.css">
		<!-- /Plugin de arrastar e soltar arquivo (drag and drop) (dropzone) -->

		<!-- Configurações do plugin de arrastar e soltar arquivo (drag and drop) (dropzone) -->
		<script language="javascript" type="text/javascript">
			Dropzone.options.NovaImagem = {
				maxFilesize: 2, // tamanho máximo da imagem (em MB)
				acceptedFiles: ".jpg", // tipos de arquivos permitidos
				addRemoveLinks: true // botão de remover arquivo da fila
			};
		</script>
		<!-- /Configurações do plugin de arrastar e soltar arquivo (drag and drop) (dropzone) -->

		<!-- Carregar nova imagem -->	
		<section id="cadastro" class="main">
			<div class="container">
				<div class="row-fluid">
					<div class="span12">
						<h2 class="center">Carregar imagem</h2>
						<form name="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="dropzone" id="NovaImagem"></form>
						<p class="center">Envie os arquivos com a seguinte nomenclatura: "tipo-marca-modelo".</p>
						<p class="center">Para substituir uma imagem existente, faça o upload de uma nova com o mesmo nome de arquivo.</p>
					</div>
				</div>
			</div>
		</section>
		<!-- /Carregar nova imagem -->

		<!-- Plugins de organização da galeria (masonry e imagesloaded) -->
		<script src="plugins/masonry-imagesloaded/masonry.pkgd.min.js"></script>
		<script src="plugins/masonry-imagesloaded/imagesloaded.pkgd.min.js"></script>
		<!-- /Plugins de organização da galeria (masonry e imagesloaded) -->

		<!-- Galeria -->
		<section id="galeria" class="container">
			<h2 class="center">Galeria</h2>
				<div class="grid">
					<div class="center grid-sizer"></div>
					<?php
						// Mostra todas as imagens no servidor
						$files = glob("images/equipamentos/*.*");
						for ($i = 0; $i < count($files); $i++) {
							$image = $files[$i];
							$arquivo = basename($image); // obtém o nome completo do arquivo
							if ($arquivo != "imagem-invalida.jpg" && $arquivo != "sem-imagem.jpg" ){
								echo '<div class="box grid-item">
									<p class="center"><b>'.$arquivo.'</b>
									<img src="'.$image.'" alt="'.$arquivo.'"/><br>
									<div>
										<form class="center" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).' " method="post">
											<input type="hidden" name="arquivo" value="'.$arquivo.'"/> 
											<input type="button" class="btn btn-mini btn-danger" id="delete_a'.$i.'" onclick="ConfirmarDelete('.$i.')" value="Deletar">
											<input type="submit" class="btn btn-mini btn-danger pull-right" id="remover'.$i.'" name="remover" style="display: none;" value="Confirmar">
										</form>
									</div>
								</div>';
							}
						}
					?>
				</div>
		</section>
		<!-- /Galeria -->

		<!-- Configurações dos plugins de organização da galeria (masonry e imagesloaded) -->
		<script>
			var $grid = $('.grid').imagesLoaded( function() {
				$grid.masonry({
					itemSelector: '.grid-item',
					percentPosition: true,
					columnWidth: '.grid-sizer'
				}); 
			});
		</script>
		<style>
			* { box-sizing: border-box; }
			html { overflow-y: scroll; }
			body { font-family: sans-serif; }
			.grid:after { content: ''; display: block; clear: both; }
			.grid-sizer,
			.grid-item { border: 1px solid #2DCC70; margin: 1px; width: 19%; } // tamanho da imagem na galeria
			.grid-item { float: left; }
			.grid-item img { display: block; width: 100%;}
		</style>
		<!-- /Configurações dos plugins de organização da galeria (masonry e imagesloaded) -->

		<!-- Função para habilitar o botão de confirmação de deletar imagem -->
		<script>
			function ConfirmarDelete(valor) {
				var elemento1 = document.getElementById("remover".concat(valor));
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