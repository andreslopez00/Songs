<?php
session_start();
require 'conexion.php';

$error = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = $pdo->prepare("SELECT * FROM usuario WHERE email = ?");
    $query->execute([$email]);
    $usuario = $query->fetch();

    if ($usuario && password_verify($password, $usuario['contrasenia_hash'])) {
        $_SESSION['usuario'] = $usuario['email'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Credenciales incorrectas.";
    }
}

include 'login_form.html';
?>
