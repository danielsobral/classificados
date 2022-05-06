<?php
namespace classes;
require_once 'Core/Model.php';

use \Core\Model;
class Usuario extends Model
{
    public function cadastrar($nome, $email, $senha, $telefone)
    {
        $sql = "SELECT id FROM usuarios WHERE email = :email";
        $sql =  $this->db->prepare($sql);
        $sql->bindValue(':email', $email);
        $sql->execute();

        if($sql->rowCOUNT() == 0){

            $sql = "INSERT INTO usuarios (nome, email, senha, telefone) VALUES (:nome, :email, :senha, :telefone)";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(':nome', $nome);
            $sql->bindValue(':email', $email);
            $sql->bindValue(':senha', md5($senha));
            $sql->bindValue(':telefone', $telefone);
            $sql->execute();

            return true;

        } else {
            return false;
        }
    }

    public function login($email, $senha)
    {
        $sql = "SELECT id FROM usuarios WHERE email = :email AND senha = :senha";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':email', $email);
        $sql->bindValue(':senha', md5($senha));
        $sql->execute();

        if($sql->rowCount() > 0){
            $dados = $sql->fetch(\PDO::FETCH_ASSOC);
            $_SESSION['cLogin'] = $dados['id'];
            return true;
        } else {
            return false;
        }
    }

    public function getTotalUsuarios()
    {
        $sql = "SELECT COUNT(*) AS u FROM usuarios";
        $sql = $this->db->query($sql);
        $sql->execute();

        $dados = $sql->fetch();

        return $dados['u'];
    }

    public function getNome()
    {
        $sql = "SELECT nome FROM usuarios WHERE id = :id";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id', $_SESSION['cLogin']);
        $sql->execute();

        if($sql->rowCount() > 0){
            $dados = $sql->fetch(\PDO::FETCH_ASSOC);
            return $dados;
        }
    }
}
