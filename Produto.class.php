<?php
class Produto {
    private $conexao;

    public function __construct() {
        try {
            $this->conexao = new PDO('mysql:host=localhost;dbname=ModularProduto', 'root', '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            ]);
        } catch(PDOException $e) {
            error_log("Erro de conexão: " . $e->getMessage());
            throw new Exception("Não foi possível conectar ao banco de dados.");
        }
    }

    public function enviarProduto($nome, $descricao, $fotos) {
        try {
            $this->conexao->beginTransaction();

            $sqlProduto = "INSERT INTO produtos (nome_produto, descricao) VALUES (:nome, :descricao)";
            $stmtProduto = $this->conexao->prepare($sqlProduto);
            $stmtProduto->bindParam(':nome', $nome);
            $stmtProduto->bindParam(':descricao', $descricao);
            $stmtProduto->execute();

            $id_produto = $this->conexao->lastInsertId();

            $sqlImagens = "INSERT INTO imagens (nome_imagem, fk_id_produto) VALUES (:nome_imagem, :id_produto)";
            $stmtImagens = $this->conexao->prepare($sqlImagens);

            foreach ($fotos as $foto) {
                $stmtImagens->bindParam(':nome_imagem', $foto);
                $stmtImagens->bindParam(':id_produto', $id_produto);
                $stmtImagens->execute();
            }

            $this->conexao->commit();
            return true;
        } catch(PDOException $e) {
            $this->conexao->rollBack();
            error_log("Erro ao enviar produto: " . $e->getMessage());
            return false;
        }
    }

    public function buscarProdutos() {
        try {
            $sql = "SELECT p.*, 
                    (SELECT nome_imagem FROM imagens WHERE fk_id_produto = p.id_produto LIMIT 1) as foto_capa 
                    FROM produtos p 
                    ORDER BY p.id_produto DESC";
            $stmt = $this->conexao->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erro ao buscar produtos: " . $e->getMessage());
            return [];
        }
    }

    public function buscarProduto($id) {
        try {
            $sql = "SELECT * FROM produtos WHERE id_produto = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erro ao buscar produto: " . $e->getMessage());
            return null;
        }
    }

    public function buscarImagem($id) {
        try {
            $sql = "SELECT * FROM imagens WHERE fk_id_produto = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erro ao buscar imagens: " . $e->getMessage());
            return [];
        }
    }

    public function removerProduto($id) {
        try {
            $sql = "DELETE FROM produtos WHERE id_produto = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Erro ao remover produto: " . $e->getMessage());
            return false;
        }
    }
}
?>