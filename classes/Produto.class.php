<?php 

class Produto{
	private $pdo;

	function __construct(){
		$type   = "mysql:dbname=";
		$dbName = "ModularProduto";
		$host   = "localhost";
		$user   = "root";
		$senha  = "";

		//echo $type.$dbName.';host='.$host.' ,' . $user . ' ,' .$senha	

		try {
			$this->pdo = new PDO($type.$dbName.";
				host=".$host, $user, $senha );							
		} catch (Exception $e) {
			echo "Erro ao tentar abrir o banco de dados!".$e->getMessage();
		}
	}

	public function enviarProduto($nome, $descricao, $fotos = array()){
		//inserir Produto na tabela produtos
		//==================================

		$sql = "INSERT INTO produtos SET descricao = :d, nome_produto = :n";
		$sql = $this->pdo->prepare($sql);
		$sql ->bindValue(":d", $descricao);
		$sql ->bindValue(":n", $nome);

		$isOk = $sql->execute();
	
		if($isOk == true){
			$id_produto = $this->pdo->LastInsertId();
		}		

		//inserir Imagem na tabela imagens	
		//================================

		if(count($fotos) > 0){
			for($i = 0; $i < count($fotos); $i++){ 
				$nome_foto = $fotos[$i];
				echo"<br>";
				
				$sql = "INSERT INTO imagens (nome_imagem, fk_id_produto) values(:n,:fk)";
				$sql = $this->pdo->prepare($sql);
				$sql->bindValue(":n" , $nome_foto);
				$sql->bindValue(":fk", $id_produto);

				$isOk = $sql->execute();

				return $isOk;
					
			}	
		}	
	}

	public function buscarProdutos(){
		//busca todos os produtos

		$cmd = "(SELECT *,(SELECT nome_imagem FROM imagens WHERE fk_id_produto = produtos.
			id_produto LIMIT 1) as foto_capa FROM produtos)";	
					
		$cmd = $this->pdo->prepare($cmd);
		$cmd->execute();

		if($cmd->rowCount() > 0){
			$dados = $cmd->fetchAll();
		}else{
			$dados = array();
		}

		return $dados;

	}

	public function buscarProduto($id){
		//um produto

		$cmd = "SELECT * FROM produtos WHERE id_produto = :i";
		$cmd = $this->pdo->prepare($cmd);
		$cmd->bindValue(":i", $id);
		$cmd->execute();

		if($cmd->rowCount() > 0){
			$dados = $cmd->fetch();
		}else{
			$dados = array();
		}

		return $dados;

	}

	public function buscarImagem($id){
		//busca uma imagem pelo id

		$sql = "SELECT * FROM imagens WHERE fk_id_produto = :i";
		$sql = $this->pdo->prepare($sql);
		$sql->bindValue(":i", $id);
		$sql->execute();

		if($sql->rowCount() > 0){
			$dados = $sql->fetch();
		}else{
			$dados = array();
		}

		return $dados;
	}
}