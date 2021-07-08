<?php

class bd {

    public function criaBD() {
        $conexao = new PDO("mysql:host=127.0.0.1", "root", "root");
        $stmt = "CREATE DATABASE IF NOT EXISTS turim_db";
        $conexao->exec($stmt);

        $conexao = new PDO("mysql:host=127.0.0.1;dbname=turim_db", "root", "root");
        $stmt = "CREATE TABLE IF NOT EXISTS pessoas (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
        nome_pessoa VARCHAR(30) NOT NULL
        )";
        $conexao->exec($stmt);

        $conexao = new PDO("mysql:host=127.0.0.1;dbname=turim_db", "root", "root");
        $stmt = "CREATE TABLE IF NOT EXISTS filhos (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        id_pessoa INT NOT NULL, 
        nome VARCHAR(30) NOT NULL
        )";
        $conexao->exec($stmt);
    }

    public function conectar() {
		  try {
			  $conexao = new PDO('mysql:host=127.0.0.1;dbname=turim_db','root','root');
			  return $conexao;
      } catch (PDOException $e) {
          $msg = 'Erro:' . $e->getcode();
			return $msg;
		  }
	  }

    public function gravar($conexao, $pessoa) {

      $query = 'insert into pessoas (nome_pessoa) values (?)';
      $stmt = $conexao->prepare($query);
      $stmt->bindValue(1, $pessoa->__get('nome'));
      $stmt->execute();

      $id_pessoa = $conexao->lastInsertId();

      $filhos = $pessoa->__get('filhos');
      if ($filhos) {
        foreach ($filhos as $filho) {
          $query = 'insert into filhos (id_pessoa, nome) values (?, ?)';
          $stmt = $conexao->prepare($query);
          $stmt->bindValue(1, $id_pessoa);
          $stmt->bindValue(2, $filho);
          $stmt->execute();
        }
      }
    }

    public function ler($conexao, $pessoa){
      $query = 'select p.nome_pessoa as nome, f.nome as filhos from pessoas as p left join filhos as f on p.id = f.id_pessoa';
      $stmt = $conexao->prepare($query);
      $stmt->execute();
      $pessoas = $stmt->fetchAll(PDO::FETCH_ASSOC);


      $array_pessoas = array();
      
      for ($i=0; $i < count($pessoas); $i++) {
        if ($i == 0) {
          $array_pessoas[$i]['nome'] = $pessoas[$i]['nome'];
          $array_pessoas[$i]['filhos'] = array($pessoas[$i]['filhos']);
        }
        if ($i > 0) {
          if ($pessoas[$i]['nome'] == $pessoas[$i-1]['nome']) {
            array_push($array_pessoas[$i-1]['filhos'], $pessoas[$i]['filhos']); 
          } else {
            $array_pessoas[$i]['nome'] = $pessoas[$i]['nome'];
            $array_pessoas[$i]['filhos'] = array($pessoas[$i]['filhos']);
          }
        } 
      }

      return $array_pessoas;
    }


      
}

?>