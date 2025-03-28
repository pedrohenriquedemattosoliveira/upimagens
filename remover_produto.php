<?php
require_once 'Produto.class.php'; 

try {
    // Instanciar a classe Produto
    $produtoController = new Produto();

    // Receber o ID do produto via GET ou POST
    $idProduto = $_GET['id'] ?? $_POST['id'] ?? null;

    if ($idProduto === null) {
        throw new Exception("ID do produto não fornecido");
    }

   
    $imagens = $produtoController->buscarImagem($idProduto);

    // Remover arquivos de imagem do servidor (opcional)
    if (!empty($imagens)) {
        foreach ($imagens as $imagem) {
            $caminhoImagem = 'uploads/' . $imagem['nome_imagem']; 
            if (file_exists($caminhoImagem)) {
                unlink($caminhoImagem);
            }
        }
    }

    $resultado = $produtoController->removerProduto($idProduto);

    if ($resultado) {
        $resposta = [
            'sucesso' => true, 
            'mensagem' => 'Produto removido com sucesso',
            'imagens_removidas' => count($imagens)
        ];
    } else {
        $resposta = [
            'sucesso' => false, 
            'mensagem' => 'Erro ao remover produto'
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($resposta);

} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'sucesso' => false, 
        'mensagem' => 'Erro: ' . $e->getMessage()
    ]);
    
    error_log("Erro na remoção do produto: " . $e->getMessage());
}
?>