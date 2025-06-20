<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $vencimento = trim($_POST['vencimento'] ?? '');

    if ($id > 0 && $titulo && $descricao && $vencimento) {
        $stmt = $db->prepare("UPDATE tarefas SET titulo = ?, descricao = ?, vencimento = ?, status = status WHERE id = ?");
        $stmt->execute([$titulo, $descricao, $vencimento, $id]);
        echo "OK";
    } elseif (isset($_POST['id']) && !isset($_POST['titulo'])) {
        $stmt = $db->prepare("UPDATE tarefas SET status = 1 WHERE id = ?");
        $stmt->execute([$id]);
        echo "OK";
    } else {
        http_response_code(400);
        echo "Dados inválidos.";
    }
}
?>