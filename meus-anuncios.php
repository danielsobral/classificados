<?php require 'pages/header.php'; ?>
<?php 
    if(empty($_SESSION['cLogin'])){
        ?>
         <script type="text/javascript">window.location.href="login.php";</script>
         <?php
        exit;
    }
?>
<?php use \classes\Anuncio;?>

<div class="container">
    <h1>Meus Anúncios</h1>

    <a href="add-anuncio.php" class="btn btn-default">Adicionar Anúncio</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Foto</th>
                <th>Título</th>
                <th>Valor</th>
                <th>Ações</th>
            </tr>
        </thead>
        <?php
        require 'classes/Anuncio.php';
        $a = new Anuncio();
        $anuncios = $a->getMeusAnuncios();

        foreach($anuncios as $anuncio):
        ?>
        <tr>
            <td>
                <?php if(!empty($anuncio['url'])): ?>
                    <img src="<?php echo BASE_FOTOS.$anuncio['url']; ?>" height="70" border="0"/>
                    <?php else: ?>
                        <img src="<?php echo BASE_FOTOS_DEFAULT; ?>" height="70" border="0"/>
                <?php endif; ?>
            </td>
            <td><?php echo $anuncio['titulo']; ?></td>
            <td>R$ <?php echo number_format($anuncio['valor'], 2); ?></td>
            <td>
                <a href="editar-anuncio.php?id=<?php echo $anuncio['id']; ?>" class="btn btn-success">Editar</a>
                <a href="excluir-anuncio.php?id=<?php echo $anuncio['id']; ?>" class="btn btn-danger">Excluir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php require 'pages/footer.php'; ?>
