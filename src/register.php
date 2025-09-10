<?php

session_start();
include 'config.php'; //conexao com o banco

$message = "";

if($_SERVER["REQUEST_METHOD"] == 'POST') {
    $email = trim($_POST["email"]);
    $password = trim ($_POST["password"]);

    if(!empty($email) && !empty($password)) {
        //criptografa a senha
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
            $stmt->execute([$email, $hashedPassword]);

            $message = "Úsuario cadastrado com sucesso! Agora você pode fazer login.";
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
</head>
<body>
    <h2>Cadastro de Usúario</h2>

    <?php if ($message): ?>
        <p style="color:red;"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>E-mail:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Senha:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Cadastrar</button>
    </form>

    <p><a href="../index.php">Já tem conta? Faça login</a></p>
</body>
</html>