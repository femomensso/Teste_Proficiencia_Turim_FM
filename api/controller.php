<?php

class Controller {

    private $pessoa;
    private $bd;
    private $requestMethod;

    public function __construct($pessoa, $bd, $requestMethod)
    {
        $this->pessoa = $pessoa;
        $this->bd = $bd;
        $this->requestMethod = $requestMethod;
    }

    public function processRequest(){

        if ($this->requestMethod == 'GET') {
            $conexao = $this->bd->conectar();
            $pessoas = $this->bd->ler($conexao, $this->pessoa);
            $response['body'] = json_encode($pessoas);

            if ($response['body']) {
                echo $response['body'];
            }
            
        } 
        
        else if ($this->requestMethod == 'POST') {
            $input = (array) json_decode(file_get_contents('php://input'), true);
            
            var_dump(
            $input
            );
            
            $conexao = $this->bd->conectar();

            $query = 'truncate table pessoas';
            $stmt = $conexao->prepare($query);
            $stmt->execute();
      
            $query = 'truncate table filhos';
            $stmt = $conexao->prepare($query);
            $stmt->execute();
            
            foreach ($input as $p) {
                $this->pessoa->__set('nome', $p['nome']);
                $this->pessoa->__set('filhos', $p['filhos']);
                $this->bd->gravar($conexao, $this->pessoa);
            }

            $response['body'] = json_encode(['msg' => 'Inserido']);
            
            
            if ($response['body']) {
                echo $response['body'];
            }
        }         
    }
}

?>