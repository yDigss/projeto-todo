<?php

session_start();
require 'config.php'; //conecta ao banco

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '') ;
    $password = trim($_POST['password'] ?? '');

    if (!empty($email) && !empty($password)) {
        //busca o usuario pelo email
        $stmt = $pdo->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
    

        if ($user && password_verify($password, $user['password'])) {
            //salva informaçoes na sessao
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            header("Location: tasks.php"); //redireciona para a pagina de tarefas
            exit;
        } else {
            $error = "Email ou senha inválidos!";
        }
    } else {
        $message = "Preencha todos os campos!";
    }

}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>

    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST">
        <label>Email</label><br>
        <input type="email" name="email" required><b></b>
        <label>Senha</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Entrar</button>
    </form>

    <p>Não tem uma conta? <a href="register.php">Cadastre-se aqui</a></p>
    </div>
</body>
</html>