<?php include 'database.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="Segundo Trabalho Final de Desenvolvimento WEB">
    <meta name="author" content="Aloizio Pita de Castro Júnior">
    <title>Banco de Dados - Livraria</title>
</head>
<body>
    <h1>Banco de Dados - Livraria</h1>

    <form id="livroForm">
        <input type="text" name="titulo" placeholder="Título" required>
        <input type="text" name="autor" placeholder="Autor" required>
        <input type="number" name="ano" placeholder="Ano" required>
        <button type="submit">Adicionar Livro</button>
    </form>

    <p id="msg"></p>

    <h2>Livros Cadastrados</h2>
    <ul id="listaLivros">
    <?php
        $stmt = $db->query("SELECT * FROM livros ORDER BY id DESC");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<li id='livro-{$row['id']}'>
                    <strong>{$row['titulo']}</strong> - {$row['autor']} ({$row['ano']})
                    <button onclick='excluirLivro({$row['id']})'>Excluir</button>
                  </li>";
        }
    ?>
    </ul>

    <script>
        // Função responsável por adicionar o livro no banco de dados e controlar a exibição para o usuário
        function adicionarLivro(formElement) {
            const formData = new FormData(formElement);

            fetch('add_book.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('msg').textContent = 'Livro adicionado com sucesso!';
                setTimeout(() => location.reload(), 1000);
            })
            .catch(error => {
                document.getElementById('msg').textContent = 'Erro ao adicionar o livro.';
                console.error(error);
                setTimeout(() => location.reload(), 1000);
            });
        }

        document.getElementById('livroForm').addEventListener('submit', function (e) {
            e.preventDefault();
            adicionarLivro(this);
        });

        // Função responsável pela remoção do livro no banco de dados e controlar exibição para o usuário
        function excluirLivro(id) {
            const formData = new FormData();
            formData.append('id', id);

            fetch('delete_book.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                const elemento = document.getElementById(`livro-${id}`);
                if (elemento) elemento.remove();
                document.getElementById('msg').textContent = 'Livro excluído com sucesso!';
                setTimeout(() => location.reload(), 1000);
            })
            .catch(error => {
                document.getElementById('msg').textContent = 'Erro ao excluir o livro.';
                console.error(error);
                setTimeout(() => location.reload(), 1000);
            });
        }
    </script>
</body>
</html>