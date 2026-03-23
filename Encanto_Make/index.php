<?php
session_start();
include 'conexao.php'; 
include 'config.php';

criarTopo($SITE . " - Cadastro de Usuário");

$mensagem = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    if (empty($nome) || empty($email) || empty($senha)) {
        $mensagem = exibirMensagem("ERROR: Todos os campos são obrigatórios.", "erro");
    } else {
        $sql = "SELECT id FROM usuarios WHERE email = '$email'";
        $result = mysqli_query($conexao, $sql);

        if (mysqli_num_rows($result) > 0) {
            $mensagem = exibirMensagem("ERROR: Este e-mail já está cadastrado.", "erro");
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha_hash')";

            if (mysqli_query($conexao, $sql)) {
                $mensagem = exibirMensagem("Cadastro realizado com sucesso!", "sucesso");
                header("Location: login.php");
                exit();
            } else {
                $mensagem = exibirMensagem("ERROR: Erro ao cadastrar: " . mysqli_error($conexao), "erro");
            }
        }
    }
}
?>

<main>
    <div class="agendamento">
        <h2>Cadastro de Usuário</h2>
        <?php echo $mensagem;?>
        <form action="index.php" method="POST" class="agendamento-form">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" required>

            <label for="email">E-mail:</label>
            <input type="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" required>

            <button type="submit">Cadastrar</button>
        </form>
        <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
    </div>
</main>

<?php criarRodape(); ?>