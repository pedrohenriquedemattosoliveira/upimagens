<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/produto.css">
    <title>Produtos</title>
</head>
<body>
    <section>
        <?php 
        require 'classes/produto.class.php'; 
        $p = new Produto(); 
        $dadosProduto = $p->buscarProdutos(); 
        
        if(empty($dadosProduto)){ 
            echo "<p>Ainda não há produtos cadastrados aqui!</p>";  
        } else { 
            foreach($dadosProduto as $value){ 
        ?>  
            <div class="produto-card">
                <a href="exibir_produto.php?id=<?php echo $value['id_produto'];?>" class="link-produto">
                    <img src="imagens/<?php echo $value['foto_capa'];?>" alt="<?php echo $value['nome_produto']; ?>" class="produto-imagem">
                    <div class="produto-info">
                        <h2><?php echo $value['nome_produto']; ?></h2>
                    </div>
                </a>
                
                <button class="btn-remover" onclick="removerProduto(<?php echo $value['id_produto']; ?>)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        <line x1="10" y1="11" x2="10" y2="17"></line>
                        <line x1="14" y1="11" x2="14" y2="17"></line>
                    </svg>
                    Remover
                </button>
            </div>
        <?php 
            }
        }  
        ?>  
    </section>

    <script>
    function removerProduto(id) {
        if (confirm(`Tem certeza que deseja remover este produto?`)) {
            fetch(`remover_produto.php?id=${id}`, {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                if (data.sucesso) {
                    alert(data.mensagem);
                    // Remove o elemento da página
                    document.querySelector(`.produto-card:has(button[onclick="removerProduto(${id})"])`).remove();
                } else {
                    alert('Erro: ' + data.mensagem);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao tentar remover o produto');
            });
        }
    }
    </script>
</body>
</html>