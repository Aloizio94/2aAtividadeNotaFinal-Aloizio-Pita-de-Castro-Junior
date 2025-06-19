<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);

    if ($id > 0) {
        $stmt = $db->prepare("DELETE FROM livros WHERE id = ?");
        $stmt->execute([$id]);
        echo "OK";
    } else {
        http_response_code(400);
        echo "ID inválido.";
    }
}