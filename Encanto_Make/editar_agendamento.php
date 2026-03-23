<?php
session_start();
include 'conexao.php'; 
include 'config.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php"); 
    exit();
}

criarTopo($SITE . " - Editar Agendamento");

$mensagem = ""; 
$agendamento = []; 

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $usuario_id = $_SESSION['usuario']['id'];

    $sql = "SELECT * FROM agendamentos WHERE id = '$id' AND usuario_id = '$usuario_id'";
    $result = mysqli_query($conexao, $sql);

    if (mysqli_num_rows($result) > 0) {
        $agendamento = mysqli_fetch_assoc($result);
    } else {
        $mensagem = exibirMensagem("ERROR: Agendamento não encontrado ou você não tem permissão para editá-lo.", "erro");
    }
} else {
    $mensagem = exibirMensagem("ERROR: ID do agendamento não fornecido.", "erro");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = $_POST["data"];
    $hora = $_POST["hora"];
    $servico = $_POST["servico"];
    $descricao = $_POST["descricao"];
    $id = $_POST["id"]; 
    $usuario_id = $_SESSION['usuario']['id']; 

    if (empty($data) || empty($hora) || empty($servico)) {
        $mensagem = exibirMensagem("ERROR: Preencha todos os campos obrigatórios.", "erro");
    } else {
        $sql = "UPDATE agendamentos 
                SET data = '$data', hora = '$hora', servico = '$servico', descricao = '$descricao' 
                WHERE id = '$id' AND usuario_id = '$usuario_id'";

        if (mysqli_query($conexao, $sql)) {
            header("Location: agendamentos.php");
            exit();
        } else {
            $mensagem = exibirMensagem("ERROR: Erro ao editar agendamento: " . mysqli_error($conexao), "erro");
        }
    }
}
?>

<main>
    <div class="agendamento">
        <h2>Editar Agendamento</h2>
        <?php echo $mensagem;?>

        <?php if (!empty($agendamento)): ?>
            <form action="editar_agendamento.php" method="POST" class="agendamento-form">
                <input type="hidden" name="id" value="<?php echo $agendamento['id']; ?>">

                <label for="data">Data:</label>
                <input type="date" name="data" value="<?php echo $agendamento['data']; ?>" required>

                <label for="hora">Hora:</label>
                <input type="time" name="hora" value="<?php echo substr($agendamento['hora'], 0, 5); ?>" required>

                <label for="servico">Serviço:</label>
                <select name="servico" required>
                    <option value="corte" <?php echo ($agendamento['servico'] == 'corte') ? 'selected' : ''; ?>>Corte de Cabelo</option>
                    <option value="escova" <?php echo ($agendamento['servico'] == 'escova') ? 'selected' : ''; ?>>Escova</option>
                    <option value="hidratacao" <?php echo ($agendamento['servico'] == 'hidratacao') ? 'selected' : ''; ?>>Hidratação</option>
                    <option value="coloracao" <?php echo ($agendamento['servico'] == 'coloracao') ? 'selected' : ''; ?>>Coloração</option>
                    <option value="outro" <?php echo ($agendamento['servico'] == 'outro') ? 'selected' : ''; ?>>Outro</option>
                </select>

                <label for="descricao">Descrição (opcional):</label>
                <textarea name="descricao" rows="4"><?php echo $agendamento['descricao']; ?></textarea>

                <button type="submit">Salvar Alterações</button>
            </form>
        <?php endif; ?>
    </div>
</main>

<?php criarRodape(); ?>