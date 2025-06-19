<?php include 'database.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="Segundo Trabalho Final de Desenvolvimento WEB">
    <meta name="author" content="Aloizio Pita de Castro Júnior">
    <title>Banco de Dados - Livraria</title>
    <style>
        h1 {
            text-align: center;
            background-color: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 20px;
            margin-top: 0;
            font-size: 2rem;
            border-bottom: 2px solid #ccc;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-image: url('imagens/livro.jpg');
            background-size: cover;
        }

        .main-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
        }
        form#livroForm {
            display: flex;
            flex-direction: column;
            gap: 10px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            margin-bottom: 30px;
        }

        form#livroForm input,
        form#livroForm button {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form#livroForm button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        form#livroForm button:hover {
            background-color: #218838;
        }
        ul#listaLivros {
            list-style: none;
            padding: 0;
            width: 100%;
            max-width: 600px;
        }

        ul#listaLivros li {
            background-color: white;
            margin-bottom: 10px;
            padding: 12px;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.05);
        }

        ul#listaLivros button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        ul#listaLivros button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <h1>Banco de Dados - Livraria</h1>

        <div class="container-form">
            <form id="livroForm">
                <input type="text" name="titulo" placeholder="Título" required>
                <input type="text" name="autor" placeholder="Autor" required>
                <input type="number" name="ano" placeholder="Ano" required>
                <button type="submit">Adicionar Livro</button>
            </form>
        </div>

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
    </div>

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