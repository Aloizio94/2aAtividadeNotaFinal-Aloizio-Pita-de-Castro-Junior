<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $autor = trim($_POST['autor'] ?? '');
    $ano = (int) ($_POST['ano'] ?? 0);

    if ($titulo && $autor && $ano > 0) {
        $stmt = $db->prepare("INSERT INTO livros (titulo, autor, ano) VALUES (?, ?, ?)");
        $stmt->execute([$titulo, $autor, $ano]);
        echo "OK";
    } else {
        http_response_code(400);
        echo "Dados inválidos.";
    }
}