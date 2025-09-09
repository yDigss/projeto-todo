<?php

session_start();

//se ja estiver logado, vai para a pagina de tarefas
if (isset($_SESSION['user_id'])) {
    header("Location: tasks.php");
    exit;
}

//se nao estiver logado, vai para o login
header("Location: login.php");
exit;

?>