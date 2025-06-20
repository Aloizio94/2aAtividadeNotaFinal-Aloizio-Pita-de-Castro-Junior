<?php include 'database.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="Segundo Trabalho Final de Desenvolvimento WEB">
    <meta name="author" content="Aloizio Pita de Castro Júnior">
    <title>Gerenciador de Tarefas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: linear-gradient(#f5f5f5, #e2e2e2);
            padding: 20px;
            box-sizing: border-box;
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            background-color: #007bff;
            color: white;
            padding: 15px;
            border-radius: 6px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 30px;
        }

        input, button {
            padding: 10px;
            font-size: 1rem;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .tarefa {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f0f0f0;
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .tarefa.concluida {
            background-color: #d4edda;
            text-decoration: line-through;
        }

        #msg {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            color: green;
        }

        .tarefa.atrasada {
            background-color: #ffe5e5;
            border: 1px solid #ff9999;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gerenciador de Tarefas</h1>

        <form id="formTarefa">
            <input type="text" name="titulo" placeholder="Título" required>
            <textarea name="descricao" placeholder="Escreva uma descrição para a tarefa" rows="4" required></textarea>
            <input type="date" name="vencimento" required>
            <button type="submit">Adicionar Tarefa</button>
        </form>

        <p id="msg"></p>

        <h2>Tarefas Pendentes</h2>
        <div id="pendentes">
        <?php
            $hoje = date('Y-m-d');
            $stmt = $db->prepare("SELECT * FROM tarefas WHERE status = 0 AND vencimento >= ? ORDER BY vencimento ASC");
            $stmt->execute([$hoje]);
            while ($t = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='tarefa' id='tarefa-{$t['id']}'>
                        <span class='conteudo' data-id='{$t['id']}'>
                            <strong class='titulo'>{$t['titulo']}</strong><br>
                            <small class='descricao'>{$t['descricao']}</small><br>
                            <em class='vencimento'>Vencimento: {$t['vencimento']}</em>
                        </span>
                        <div>
                            <button onclick='concluirTarefa({$t['id']})'>Concluir</button>
                            <button onclick='editarTarefa({$t['id']})'>Editar</button>
                            <button onclick='excluirTarefa({$t['id']})'>Excluir</button>
                        </div>
                      </div>";
            }
        ?>
        </div>

        <h2>Tarefas Concluídas</h2>
        <div id="concluidas">
        <?php
            $stmt = $db->query("SELECT * FROM tarefas WHERE status = 1 ORDER BY vencimento ASC");
            while ($t = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='tarefa concluida' id='tarefa-{$t['id']}'>
                        <span class='conteudo' data-id='{$t['id']}'>
                            <strong class='titulo'>{$t['titulo']}</strong><br>
                            <small class='descricao'>{$t['descricao']}</small><br>
                            <em class='vencimento'>Vencimento: {$t['vencimento']}</em>
                        </span>
                        <div>
                            <button onclick='excluirTarefa({$t['id']})'>Excluir</button>
                        </div>
                      </div>";
            }
        ?>
        </div>

        <h2 style="color: darkred;">Tarefas em Atraso</h2>
        <div id="atrasadas">
        <?php
            $stmt = $db->prepare("SELECT * FROM tarefas WHERE status = 0 AND vencimento < ? ORDER BY vencimento ASC");
            $stmt->execute([$hoje]);

            while ($t = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='tarefa atrasada' id='tarefa-{$t['id']}'>
                        <span class='conteudo' data-id='{$t['id']}'>
                            <strong class='titulo'>{$t['titulo']}</strong><br>
                            <small class='descricao'>{$t['descricao']}</small><br>
                            <em class='vencimento'>Vencimento: {$t['vencimento']}</em>
                        </span>
                        <div>
                            <button onclick='concluirTarefa({$t['id']})'>Concluir</button>
                            <button onclick='editarTarefa({$t['id']})'>Editar</button>
                            <button onclick='excluirTarefa({$t['id']})'>Excluir</button>
                        </div>
                    </div>";
            }
        ?>
        </div>
    </div>

    <script>
        document.getElementById('formTarefa').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('add_tarefa.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text())
            .then(data => {
                document.getElementById('msg').textContent = 'Tarefa adicionada com sucesso!';
                setTimeout(() => location.reload(), 1000);
            })
            .catch(() => {
                document.getElementById('msg').textContent = 'Erro ao adicionar a tarefa.';
            });
        });

        function concluirTarefa(id) {
            const formData = new FormData();
            formData.append('id', id);

            fetch('update_tarefa.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text())
            .then(data => {
                document.getElementById('msg').textContent = 'Tarefa concluída!';
                setTimeout(() => location.reload(), 1000);
            })
            .catch(() => {
                document.getElementById('msg').textContent = 'Erro ao concluir a tarefa.';
            });
        }

        function excluirTarefa(id) {
            const formData = new FormData();
            formData.append('id', id);

            fetch('delete_tarefa.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text())
            .then(data => {
                document.getElementById(`tarefa-${id}`).remove();
                document.getElementById('msg').textContent = 'Tarefa excluída!';
            })
            .catch(() => {
                document.getElementById('msg').textContent = 'Erro ao excluir a tarefa.';
            });
        }

        function editarTarefa(id) {
            const tarefaEl = document.getElementById(`tarefa-${id}`);
            const conteudo = tarefaEl.querySelector('.conteudo');

            const tituloAtual = conteudo.querySelector('.titulo').textContent;
            const descricaoAtual = conteudo.querySelector('.descricao').textContent;
            const vencimentoAtual = conteudo.querySelector('.vencimento').textContent.split(': ')[1];

            conteudo.innerHTML = `
                <input type="text" id="edit-titulo-${id}" value="${tituloAtual}" required><br>
                <textarea id="edit-descricao-${id}" rows="3" required>${descricaoAtual}</textarea><br>
                <input type="date" id="edit-vencimento-${id}" value="${vencimentoAtual}" required>
            `;

            const botoes = tarefaEl.querySelector('div:last-child');
            botoes.innerHTML = `
                <button onclick="salvarEdicao(${id})">Salvar</button>
                <button onclick="location.reload()">Cancelar</button>
            `;
        }

        function salvarEdicao(id) {
            const titulo = document.getElementById(`edit-titulo-${id}`).value.trim();
            const descricao = document.getElementById(`edit-descricao-${id}`).value.trim();
            const vencimento = document.getElementById(`edit-vencimento-${id}`).value;

            if (!titulo || !descricao || !vencimento) {
                document.getElementById('msg').textContent = 'Todos os campos são obrigatórios.';
                return;
            }

            const formData = new FormData();
            formData.append('id', id);
            formData.append('titulo', titulo);
            formData.append('descricao', descricao);
            formData.append('vencimento', vencimento);

            fetch('update_tarefa.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text())
            .then(data => {
                if (data.trim() === 'OK') {
                    document.getElementById('msg').textContent = 'Tarefa atualizada com sucesso!';
                    setTimeout(() => location.reload(), 1000);
                } else {
                    document.getElementById('msg').textContent = 'Erro: ' + data;
                }
            })
            .catch(() => {
                document.getElementById('msg').textContent = 'Erro ao salvar a tarefa.';
            });
        }
    </script>
</body>
</html>