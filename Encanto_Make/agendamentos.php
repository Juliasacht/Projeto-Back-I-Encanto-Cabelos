<?php
session_start();
include 'conexao.php'; 
include 'config.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php"); 
    exit();
}

criarTopo($SITE . " - Agendamentos");

$mensagem = ""; 

if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $usuario_id = $_SESSION['usuario']['id'];

    $sql = "DELETE FROM agendamentos WHERE id = '$id' AND usuario_id = '$usuario_id'";

    if (mysqli_query($conexao, $sql)) {
        $mensagem = exibirMensagem("Agendamento excluído com sucesso!", "sucesso");
    } else {
        $mensagem = exibirMensagem("ERROR: Erro ao excluir agendamento: " . mysqli_error($conexao), "erro");
    }
}


$usuario_id = $_SESSION['usuario']['id'];
$sql = "SELECT * FROM agendamentos WHERE usuario_id = '$usuario_id' ORDER BY data, hora";
$result = mysqli_query($conexao, $sql);

$agendamentos = []; 
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $agendamentos[] = $row;
    }
}
?>

<main>
    <div class="agendamento">
        <h2>Meus Agendamentos</h2>
        <?php echo $mensagem;?>

        <table>
    <thead>
        <tr>
            <th>Data</th>
            <th>Hora</th>
            <th>Serviço</th>
            <th>Descrição</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($agendamentos)): ?>
            <tr>
                <td colspan="5">Nenhum agendamento encontrado.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($agendamentos as $agendamento): ?>
                <tr>
                    <td><?php echo $agendamento['data']; ?></td>
                    <td><?php echo substr($agendamento['hora'], 0, 5); ?></td>
                    <td><?php echo $agendamento['servico']; ?></td>
                    <td><?php echo $agendamento['descricao']; ?></td>
                    <td>
                        <a href="editar_agendamento.php?id=<?php echo $agendamento['id']; ?>">Editar</a> |
                        <a href="agendamentos.php?excluir=<?php echo $agendamento['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este agendamento?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
        <div>
            <a href="agendar.php" class="btn">Agendar Novo Horário</a>
        </div>
    </div>
</main>

<?php criarRodape(); ?>