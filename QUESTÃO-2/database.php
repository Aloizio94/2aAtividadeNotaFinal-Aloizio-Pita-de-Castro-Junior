<?php
try {
    $db = new PDO('sqlite:tarefas.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->exec("CREATE TABLE IF NOT EXISTS tarefas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        titulo TEXT NOT NULL,
        descricao TEXT NOT NULL,
        vencimento TEXT NOT NULL,
        status INTEGER DEFAULT 0
    )");
} catch (PDOException $e) {
    echo "Erro na conexão com o banco: " . $e->getMessage();
    exit;
}
?>