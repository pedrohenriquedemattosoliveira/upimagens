<!DOCTYPE html>
<html>
<head>
	<title>Formulario de Cadastro</title>
	<link rel="stylesheet" type="text/css" href="css/estilo.css">
</head>
<body>
	<section>
		<a href="produtos.php" class="sombra">Ver todos os produtos</a>
		<form method="post" enctype="multipart/form-data">
			<h1>ENVIO DE IMAGENS</h1>
			<label for="nome">Nome do Produto</label>
			<input type="text" name="nome" id="nome" class="sombra">

			<label for="des">Descrição</label>
			<textarea name="desc" id="desc" class="sombra"></textarea>
			
			<input type="file" name="foto[]" multiple id="foto" class="sombra meuInput">
			<input type="submit" id="botao">

		</form>
	</section>

</body>
</html>

<?php

if(isset ($_POST['nome']) && !empty($_POST['nome'])){				
	$nome      = addslashes($_POST['nome']);						
	$descricao = addslashes($_POST['desc']);

	$fotos     = array();						
	
	if(isset($_FILES['foto'])){	
		$tipo = '';	
		for($i = 0; $i < count($_FILES['foto']['name']); $i++){ 
			if($_FILES['foto']['type'][$i] == "image/png"){
				$tipo = ".png";
			}elseif ($_FILES['foto']['type'][$i] == 'image/jpeg'){
				$tipo = ".jpg";
			}else{
				$tipo = "outro";
			}					

			if($tipo == 'outro'){
				?>
				<script>
					alert("Só é possível enviar arquivos JPG e PNG");
				</script>
				<?php
			}else{
				$nome_arquivo = md5($_FILES['foto']['name'][$i]).rand(1,999).$tipo;	//encripta
				
				move_uploaded_file($_FILES['foto']['tmp_name'][$i], 'imagens/'.$nome_arquivo);

				array_push($fotos, $nome_arquivo);
			}	
		}

		if(!empty($nome) && !empty($descricao) ){
			require 'classes/Produto.class.php';
			$p = new Produto();
			$p->enviarProduto($nome, $descricao, $fotos);	

		}else{
			?>
			<script>
				alert("Preencha os campos obrigatorios!")
			</script>	
			<?php
		}	
	}
}

?>