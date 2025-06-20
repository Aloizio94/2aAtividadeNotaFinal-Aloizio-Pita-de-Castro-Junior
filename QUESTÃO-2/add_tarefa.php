<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $vencimento = trim($_POST['vencimento'] ?? '');

    if ($titulo && $descricao && $vencimento) {
        $stmt = $db->prepare("INSERT INTO tarefas (titulo, descricao, vencimento, status) VALUES (?, ?, ?, 0)");
        $stmt->execute([$titulo, $descricao, $vencimento]);
        echo "Tarefa adicionada com sucesso.";
    } else {
        http_response_code(400);
        echo "Preencha todos os campos.";
    }
}