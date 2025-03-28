<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Produto</title>
    <link rel="stylesheet" href="css/exibir.css">
    <style>
        section {
            display: flex;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            gap: 30px;
        }
        #imagens {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }
        .caixa-img {
            max-width: 400px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .caixa-img img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <?php
    // Configuração de conexão com o banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ModularProduto";

    // Verificar se o ID do produto foi passado
    if(!isset($_GET['id']) || empty($_GET['id'])){
        die("ID do produto não especificado");
    }

    $id = intval($_GET['id']);

    // Criar conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Buscar detalhes do produto
    $sql_produto = "SELECT nome_produto, descricao FROM produtos WHERE id_produto = ?";
    $stmt_produto = $conn->prepare($sql_produto);
    $stmt_produto->bind_param("i", $id);
    $stmt_produto->execute();
    $result_produto = $stmt_produto->get_result();

    // Buscar imagens do produto
    $sql_imagens = "SELECT nome_imagem FROM imagens WHERE fk_id_produto = ?";
    $stmt_imagens = $conn->prepare($sql_imagens);
    $stmt_imagens->bind_param("i", $id);
    $stmt_imagens->execute();
    $result_imagens = $stmt_imagens->get_result();

    // Verificar se o produto existe
    if ($result_produto->num_rows > 0) {
        $produto = $result_produto->fetch_assoc();
    ?>
    <section>
        <div>
            <h1><?php echo htmlspecialchars($produto['nome_produto']); ?></h1>
            <p><span>Descrição: </span><?php echo htmlspecialchars($produto['descricao']); ?></p>
        </div>
        <div id="imagens">
            <?php 
            // Verificar se há imagens
            if ($result_imagens->num_rows > 0) {
                while ($imagem = $result_imagens->fetch_assoc()) {
            ?>
                <div class="caixa-img">
                    <img src="imagens/<?php echo htmlspecialchars($imagem['nome_imagem']); ?>" alt="Imagem do Produto">
                </div>
            <?php 
                }
            } else {
                echo "<p>Nenhuma imagem disponível para este produto.</p>";
            }
            ?>
        </div>             
    </section>
    <?php
    } else {
        echo "<p>Produto não encontrado.</p>";
    }

    // Fechar statements e conexão
    $stmt_produto->close();
    $stmt_imagens->close();
    $conn->close();
    ?>
</body>
</html>