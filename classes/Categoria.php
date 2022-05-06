<?php
namespace classes;
require_once 'Core/Model.php';

use \Core\Model;

class Categoria extends Model
{
    public function getCategorias()
    {
        $dados = [];
        $sql = "SELECT * FROM categorias";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0){
            $dados = $sql->fetchAll(\PDO::FETCH_ASSOC);
            return $dados;
        }

        return $dados;
    }
}
