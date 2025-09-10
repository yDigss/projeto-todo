<?php

session_start();
include 'config.php'; //conexao com o banco

$message = "";

if($_SERVER["REQUEST_METHOD"] == 'POST') {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = trim ($_POST["password"]);

    if(!empty($email) && !empty($password)) {
        //criptografa a senha
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hashedPassword]);

            $message = "Usuário cadastrado com sucesso! Redirecionando para login...";

        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { //email duplicado
                $message = "Este email já está cadastrado!";
            } else {
                $message = "Erro: " .$e->getMessage();
            }
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
    <title>Cadastro</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Cadastro de Usúario</h2>

        <form method="POST" action="">
            <label>Nome:</label><br>
            <input type="text" name="name" required><br><br>

            <label>E-mail:</label><br>
            <input type="email" name="email" required><br><br>

            <label>Senha:</label><br>
            <input type="password" name="password" required><br><br>

            <button type="submit">Cadastrar</button>
        </form>

        <?php if ($message): ?>

            <p style = "color:green;"><?= $message?></p>
            <script>
                //Redireciona para login apos 2 segundos
                setTimeout(() => {
                    window.location.href = "login.php";
                }, 2000);
            </script>
        <?php endif; ?>

        <p><a href="../index.php">Já tem conta? Faça login</a></p>
    </div>
    
</body>
</html>