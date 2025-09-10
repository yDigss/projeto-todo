<?php

session_start();
require 'config.php';

//verifica se o usuario esta logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

//adiciona tarefa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $title = trim($_POST['title']);
    if (!empty($title)) {
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $title]);
    }
    header("Location: tasks.php"); //evita reenvio do formulario
    exit;
}

//marcar como concluida
if (isset($_GET['complete'])) {
    $taskid = (int)$_GET['complete'];
    $stmt = $pdo->prepare("UPDATE tasks SET completed = 1 WHERE id = ? AND user_id = ?");
    $stmt->execute([$taskid, $_SESSION['user_id']]);
    header("Location: tasks.php");
    exit;
}

//deletar tarefa
if (isset($_GET['delete'])) {
    $taskid = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$taskid, $_SESSION['user_id']]);
    header("Location: tasks.php");
    exit;
}

//buscar tarefa do usuario
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$tasks = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Tarefas</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
    <h1>Olá, <?= htmlspecialchars($_SESSION['user_name']);?></h1>
    <a href="logout.php" class="logout-btn">Sair</a>

    <form method="POST" class="task-form">
        <input type="text" name="title" placeholder="Digite sua tarefa" required>
        <button type="submit">Adicionar</button>
    </form>

    <ul class="task-list">
        <?php foreach($tasks as $task): ?>
            <li class="<?= $task['completed'] ? 'completed' : '' ?>">
                <span><?= htmlspecialchars($task['title']); ?></span>
                <div class="actions">
                    <?php if (!$task['completed']): ?>
                        <a href="?complete=<?= $task['id']; ?> "class="complete-btn">✔</a>
                    <?php endif; ?>
                    <a href="?delete=<?= $task['id']; ?> "class="delete-btn">✖</a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
    </div>
</body>
</html>