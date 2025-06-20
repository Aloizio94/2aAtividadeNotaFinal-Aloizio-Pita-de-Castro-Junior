<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);

    if ($id > 0) {
        $stmt = $db->prepare("DELETE FROM tarefas WHERE id = ?");
        $success = $stmt->execute([$id]);

        if ($success) {
            echo "OK";
        } else {
            echo "Erro ao excluir do banco.";
        }
    } else {
        http_response_code(400);
        echo "ID inválido.";
    }
} else {
    http_response_code(405);
    echo "Método não permitido.";
}