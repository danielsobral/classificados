<?php require 'pages/header.php'; ?>
<?php use \classes\Usuario; ?>

<div class="container">
    <h1>Cadastre-se</h1>
    <?php require 'classes/Usuario.php';
    $u = new Usuario();

    if (isset($_POST['nome']) && !empty($_POST['nome'])) {

        $nome = addslashes($_POST['nome']);
        $email = addslashes($_POST['email']);
        $senha = $_POST['senha'];
        $telefone = addslashes($_POST['telefone']);

        if(!empty($_POST['nome']) && !empty($_POST['email']) && !empty($_POST['senha'])){
            if($u->cadastrar($nome, $email, $senha, $telefone)){
                ?>
                <div class="alert alert-success">
                    <strong>Usuário cadastrado com sucesso! <a href="login.php" class="alert-link">Agora faça o login</a></strong>
                </div>
                <?php
            } else {
                ?>
                <div class="alert alert-warning">
                    <strong>Este usuário já existe <a href="login.php" class="alert-link">retorne para a pagina de login</a></strong>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="alert alert-warning">
                Preencha todos os campos obrigatórios!
            </div>
            <?php
        }
    }
    ?>

    <form action="" method="post">
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" class="form-control" />
            <p>campo obrigatório</p>
        </div>
        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" class="form-control" />
            <p>campo obrigatório</p>
        </div>
        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" class="form-control" />
            <p>campo obrigatório</p>
        </div>
        <div class="form-group">
            <label for="telefone">Telefone:</label>
            <input type="tel" name="telefone" id="telefone" class="form-control">
        </div>
        <input type="submit" value="Cadastrar" class="btn btn-default" />
    </form>
</div>

<?php require 'pages/footer.php'; ?>