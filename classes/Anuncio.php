<?php
namespace classes;
require_once 'Core/Model.php';

use \Core\Model;

class Anuncio extends Model
{

    public function getTotalAnuncios($filtros)
    {
        $filtrostring = ['1=1'];
        
        if(!empty($filtros['categoria'])){
            $filtrostring[] = 'anuncios.id_categoria = :id_categoria';
        }
        if(!empty($filtros['preco'])){
            $filtrostring[] = 'anuncios.valor BETWEEN :preco1 AND :preco2';
        }
        if(!empty($filtros['estado'])){
            $filtrostring[] = 'anuncios.estado = :estado';
        }

        $sql = "SELECT COUNT(*) as a FROM anuncios WHERE ".implode(' AND ', $filtrostring);
        $sql = $this->db->prepare($sql);

        if(!empty($filtros['categoria'])){
            $sql->bindValue(':id_categoria', $filtros['categoria']);
        }
        if(!empty($filtros['preco'])){
            $preco = explode('-', $filtros['preco']);
            $sql->bindValue(':preco1', $preco[0]);
            $sql->bindValue(':preco2', $preco[1]);
        }
        if(!empty($filtros['estado'])){
            $sql->bindValue(':estado', $filtros['estado']);
        }
        
        $sql->execute();

        $dados = $sql->fetch();

        return $dados['a'];
    }

    public function getMeusAnuncios()
    {
        $dados = [];
        $sql = "SELECT anuncios_imagens.url, anuncios.id, anuncios.titulo, anuncios.valor  
        FROM anuncios
        LEFT JOIN anuncios_imagens ON anuncios.id = anuncios_imagens.id_anuncio 
        WHERE anuncios.id_usuario = :id GROUP BY anuncios.titulo";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id', $_SESSION['cLogin']);
        $sql->execute();

        if($sql->rowCount() > 0){
            $dados = $sql->fetchAll(\PDO::FETCH_ASSOC);
            return $dados;
        }

        return $dados;
    }

    public function getAnuncio($id)
    {
        $dados = [];
        $sql = "SELECT anuncios.*, categorias.nome AS categoria, usuarios.nome AS nome_contato , usuarios.telefone
        FROM anuncios
        LEFT JOIN categorias ON anuncios.id_categoria = categorias.id 
        LEFT JOIN usuarios ON anuncios.id_usuario = usuarios.id 
        WHERE anuncios.id = :id";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id', $id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $dados = $sql->fetch(\PDO::FETCH_ASSOC);
            $dados['url'] = [];

            $sql = "SELECT id, url FROM anuncios_imagens WHERE id_anuncio = :id_anuncio";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(':id_anuncio', $id);
            $sql->execute();

            if($sql->rowCount() > 0){
                $dados['url'] = $sql->fetchAll(\PDO::FETCH_ASSOC);
            }

            return $dados;
        }
        return $dados;
    }

    public function getUltimosAnuncios($page, $perPage, $filtros)
    {
        $dados = [];
        $offset = ($page - 1) * $perPage;
        $filtrostring = ['1=1'];


        if(!empty($filtros['categoria'])){
            $filtrostring[] = 'anuncios.id_categoria = :id_categoria';
        }
        if(!empty($filtros['preco'])){
            $filtrostring[] = 'anuncios.valor BETWEEN :preco1 AND :preco2';
        }
        if(!empty($filtros['estado'])){
            $filtrostring[] = 'anuncios.estado = :estado';
        }

       $sql = "SELECT anuncios_imagens.url, anuncios.id, anuncios.id_categoria, anuncios.titulo, categorias.nome AS categoria, anuncios.valor, anuncios.estado  
        FROM anuncios
        LEFT JOIN anuncios_imagens ON anuncios.id = anuncios_imagens.id_anuncio 
        LEFT JOIN categorias ON categorias.id = anuncios.id_categoria
        WHERE ".implode(' AND ', $filtrostring)."
        GROUP BY anuncios.id DESC LIMIT $offset, $perPage";
        $sql = $this->db->prepare($sql);

        if(!empty($filtros['categoria'])){
            $sql->bindValue(':id_categoria', $filtros['categoria']);
        }
        if(!empty($filtros['preco'])){
            $preco = explode('-', $filtros['preco']);
            $sql->bindValue(':preco1', $preco[0]);
            $sql->bindValue(':preco2', $preco[1]);
        }
        if(!empty($filtros['estado'])){
            $sql->bindValue(':estado', $filtros['estado']);
        }
        $sql->execute();

        if($sql->rowCount() > 0){
            $dados = $sql->fetchAll(\PDO::FETCH_ASSOC);
            return $dados;
        }

        return $dados;
    }

    public function addAnuncio($titulo, $categoria, $valor, $descricao, $estado)
    {
        $sql = "INSERT INTO anuncios (id_usuario, id_categoria, titulo, valor, descricao, estado) 
        VALUES (:id_usuario, :id_categoria, :titulo, :valor, :descricao, :estado)";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id_usuario', $_SESSION['cLogin']);
        $sql->bindValue(':id_categoria', $categoria);
        $sql->bindValue(':titulo', $titulo);
        $sql->bindValue(':descricao', $descricao);
        $sql->bindValue(':valor', $valor);
        $sql->bindValue(':estado', $estado);
        $sql->execute();
    }

    public function editAnuncio($titulo, $categoria, $valor, $descricao, $estado, $fotos, $id)
    {
        $sql = "UPDATE anuncios SET titulo = :titulo, id_categoria = :id_categoria, id_usuario = :id_usuario, descricao = :descricao, valor = :valor, estado = :estado WHERE id = :id";
        $sql = $this->db->prepare($sql);
		$sql->bindValue(":titulo", $titulo);
		$sql->bindValue(":id_categoria", $categoria);
		$sql->bindValue(":id_usuario", $_SESSION['cLogin']);
		$sql->bindValue(":descricao", $descricao);
		$sql->bindValue(":valor", $valor);
		$sql->bindValue(":estado", $estado);
		$sql->bindValue(":id", $id);
        $sql->execute();

        if(count($fotos) > 0){
            for($q=0;$q<count($fotos['tmp_name']);$q++) {
				$tipo = $fotos['type'][$q];
				if(in_array($tipo, array('image/jpeg', 'image/png'))) {
					$tmpname = md5(time().rand(0,9999)).'.jpg';
					move_uploaded_file($fotos['tmp_name'][$q], 'assets/images/anuncios/'.$tmpname);

					list($width_orig, $height_orig) = getimagesize('assets/images/anuncios/'.$tmpname);
					$ratio = $width_orig/$height_orig;

					$width = 500;
					$height = 500;

					if($width/$height > $ratio) {
						$width = $height*$ratio;
					} else {
						$height = $width/$ratio;
					}

					$img = imagecreatetruecolor($width, $height);
					if($tipo == 'image/jpeg') {
						$origi = imagecreatefromjpeg('assets/images/anuncios/'.$tmpname);
					} elseif($tipo == 'image/png') {
						$origi = imagecreatefrompng('assets/images/anuncios/'.$tmpname);
					}

					imagecopyresampled($img, $origi, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

					imagejpeg($img, 'assets/images/anuncios/'.$tmpname, 80);

					$sql = ("INSERT INTO anuncios_imagens SET id_anuncio = :id_anuncio, url = :url");
                    $sql = $this->db->prepare($sql);
					$sql->bindValue(":id_anuncio", $id);
					$sql->bindValue(":url", $tmpname);
					$sql->execute();

				}
			}
        }
    }

    public function deleteAnuncio($id)
    {
        $sql = "SELECT * FROM anuncios WHERE id = :id";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id', $id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $sql = "DELETE FROM anuncios_imagens WHERE id_anuncio = :id";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(':id', $id);
            $sql->execute();

            $sql = "DELETE FROM anuncios WHERE id = :id";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(':id', $id);
            $sql->execute();
        }
    }

    public function deleteFoto($id)
    {
        $id_anuncio = 0;

        $sql = "SELECT id_anuncio FROM anuncios_imagens WHERE id = :id";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id', $id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $row = $sql->fetch();
            $id_anuncio = $row['id_anuncio'];
        }

        $sql = "DELETE FROM anuncios_imagens WHERE id = :id";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id', $id);
        $sql->execute();

        return $id_anuncio;
    }
}
