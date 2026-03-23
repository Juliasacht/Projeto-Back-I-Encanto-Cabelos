<?php
session_start();
include 'conexao.php'; 
include 'config.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php"); 
    exit();
}

criarTopo($SITE . " - Agendar Horário");

$mensagem = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = $_POST["data"];
    $hora = $_POST["hora"];
    $servico = $_POST["servico"];
    $descricao = $_POST["descricao"];
    $usuario_id = $_SESSION['usuario']['id']; 

    if (empty($data) || empty($hora) || empty($servico)) {
        $mensagem = exibirMensagem("ERROR: Preencha todos os campos obrigatórios.", "erro");
    } else {
        $sql = "INSERT INTO agendamentos (usuario_id, data, hora, servico, descricao) 
                VALUES ('$usuario_id', '$data', '$hora', '$servico', '$descricao')";

        if (mysqli_query($conexao, $sql)) {
            header("Location: agendamentos.php");
            exit();
        } else {
            $mensagem = exibirMensagem("ERROR: Erro ao agendar: " . mysqli_error($conexao), "erro");
        }
    }
}
?>

<main>
    <div class="agendamento">
        <h2>Agendar Horário</h2>
        <?php echo $mensagem;?>
        <form action="agendar.php" method="POST" class="agendamento-form">
            <label for="data">Data:</label>
            <input type="date" name="data" required>

            <label for="hora">Hora:</label>
            <input type="time" name="hora" required>

            <label for="servico">Serviço:</label>
            <select name="servico" required>
                <option value="corte">Corte de Cabelo</option>
                <option value="escova">Escova</option>
                <option value="hidratacao">Hidratação</option>
                <option value="coloracao">Coloração</option>
                <option value="outro">Outro</option>
            </select>

            <label for="descricao">Descrição (opcional):</label>
            <textarea name="descricao" rows="4"></textarea>

            <button type="submit">Agendar</button>
        </form>
    </div>
</main>

<?php criarRodape(); ?>