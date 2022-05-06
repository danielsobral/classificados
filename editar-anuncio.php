<?php require 'pages/header.php'; ?>
<?php 
    if(empty($_SESSION['cLogin'])){
        ?>
         <script type="text/javascript">window.location.href="login.php";</script>
         <?php
        exit;
    }

require 'classes/Anuncio.php';
use \classes\Anuncio;

$a = new Anuncio();

if(isset($_POST['titulo']) && !empty($_POST['titulo'])){
    $titulo = addslashes($_POST['titulo']);
    $categoria = addslashes($_POST['categoria']);
    $valor = addslashes($_POST['valor']);
    $descricao = addslashes($_POST['descricao']);
    $estado = addslashes($_POST['estado']);
    if(isset($_FILES['fotos'])){
        $fotos = $_FILES['fotos'];
    } else {
        $fotos = [];
    }
    
    $a->editAnuncio($titulo, $categoria, $valor, $descricao, $estado, $fotos, $_GET['id']);

    ?>
    <div class="alert alert-success">
        Produto editado com sucesso!
    </div>
    <?php
}

if(isset($_GET['id']) && !empty($_GET['id'])){
    $info = $a->getAnuncio($_GET['id']);

} else {
    ?>
    <script type="text/javascript">window.location.href="meus-anuncios.php";</script>
    <?php
   exit;
}

?>
<?php use \classes\Categoria; ?>


<div class="container">
    <h1>Editar Anúncio</h1>

    <form action="" method="post" enctype="multipart/form-data">

        <div class="form-group">
            <label for="categoria">Categoria:</label>
            <select name="categoria" id="categoria" class="form-control">
                <?php 
                require 'classes/Categoria.php';
                $c = new Categoria();
                $categorias = $c->getCategorias();

                foreach($categorias as $categoria):
                ?>
                <option value="<?php echo $categoria['id']; ?>"<?php echo ($info['id_categoria']==$categoria['id'])?'selected="selected"':''; ?>><?php echo $categoria['nome']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="titulo">Titulo:</label>
            <input type="text" name="titulo" id="titulo" class="form-control" value="<?php echo $info['titulo']; ?>">
        </div>

        <div class="form-group">
            <label for="valor">Valor:</label>
            <input type="text" name="valor" id="valor" class="form-control" value="<?php echo $info['valor']; ?>">
        </div>

        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <textarea class="form-control" name="descricao"><?php echo $info['descricao']; ?></textarea>
        </div>

        <div class="form-group">
            <label for="estado">Estado de conservação:</label>
            <select name="estado" id="estado" class="form-control">
                <option value="0" <?php echo ($info['estado']=='0')?'selected="selected"':''; ?>>Ruim</option>
                <option value="1"<?php echo ($info['estado']=='1')?'selected="selected"':''; ?>>Bom</option>
                <option value="2"<?php echo ($info['estado']=='2')?'selected="selected"':''; ?>>Ótimo</option>
            </select>
        </div>
        <div class="form-group">
            <label for="add_foto">Fotos do anúncio:</label>
            <input type="file" name="fotos[]" multiple /> <br>

            <div class="panel panel-default">
                <div class="panel-heading">Fotos do Anúncio</div>
                <div class="panel-body">
                <?php foreach($info['url'] as $fotos):?>
                    <div class="foto_item">
                        <img src="<?php echo BASE_FOTOS.$fotos['url']; ?>" class="img-thumbnail" border="0" />
                        <a href="excluir-foto.php?id=<?php echo $fotos['id']; ?>" class="btn btn-danger">Excluir</a>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
        <input type="submit" value="Salvar" class="btn btn-default">
    </form>
</div>
<?php require 'pages/footer.php'; ?>