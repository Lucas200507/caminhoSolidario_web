<?php
    session_start();
    // Verificando se fez o login
    if ((!isset($_SESSION['usuario']) && !isset($_SESSION['senha'])) || isset($_SESSION['deslogar'])){
        unset($_SESSION['usuario']);
        unset($_SESSION['senha']);
        header('Location: ../php/login.php');
    }
?>