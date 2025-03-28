<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalhes do Produto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .produto-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 20px;
            display: flex;
            gap: 20px;
        }
        .produto-imagens {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 50%;
        }
        .produto-imagens img {
            width: 100%;
            border-radius: 10px;
            object-fit: cover;
        }
        .produto-info {
            width: 50%;
        }
        .voltar {
            display: block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #333;
        }
    </style>
</head>
<body>
    <a href="produtos.php" class="voltar">← Voltar para Produtos</a>
    <?php
    // Verificar se o ID do produto foi passado
    if (!isset($_GET['id'])) {
        die("ID do produto não especificado");
    }

    $id_produto = intval($_GET['id']);

    // Configuração de conexão com o banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ModularProduto";

    // Criar conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Buscar detalhes do produto e suas imagens
    $sql = "SELECT p.id_produto, p.nome_produto, p.descricao, i.nome_imagem 
            FROM produtos p
            LEFT JOIN imagens i ON p.id_produto = i.fk_id_produto
            WHERE p.id_produto = ?";
    
    // Prepared statement para prevenir injeção de SQL
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_produto);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $imagens = [];
        $produto = null;

        // Coletar todas as imagens do produto
        while($row = $result->fetch_assoc()) {
            if ($produto === null) {
                $produto = [
                    'id' => $row['id_produto'],
                    'nome' => $row['nome_produto'],
                    'descricao' => $row['descricao']
                ];
            }
            
            if ($row['nome_imagem']) {
                $imagens[] = $row['nome_imagem'];
            }
        }

        // Exibir detalhes do produto
        ?>
        <div class="produto-container">
            <div class="produto-imagens">
                <?php foreach ($imagens as $imagem): ?>
                    <img src="imagens/<?php echo $imagem; ?>" alt="<?php echo $produto['nome']; ?>">
                <?php endforeach; ?>
            </div>
            <div class="produto-info">
                <h1><?php echo $produto['nome']; ?></h1>
                <p><?php echo $produto['descricao']; ?></p>
            </div>
        </div>
        <?php
    } else {
        echo "<p>Produto não encontrado</p>";
    }

    $stmt->close();
    $conn->close();
    ?>
</body>
</html>