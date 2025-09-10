<?php

session_start();
session_unset(); //limpa todas as variaveis da sessao
session_destroy(); //destroi a sessao

//redireciona para o login
header("Location: login.php");
exit;

?>