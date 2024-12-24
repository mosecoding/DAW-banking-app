<?php
include "../../config/connection.php";
include "../classes/User.php";
include "../classes/CrudUser.php";

session_start();

// Redirigir si no es un POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../views/index.php");
    exit();
}

// Obtener y validar datos del formulario
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($email) || empty($password)) {
    setErrorAndRedirect("Por favor, completa todos los campos.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setErrorAndRedirect("El correo no es válido.");
}

// Instanciar CrudUser y buscar el usuario por email
$crudUser = new CrudUser($pdo);
$user = $crudUser->getUserByEmail($email);

// Verificar si el usuario existe
if (!$user) {
    setErrorAndRedirect("Este correo no está registrado.");
}

// Validar la contraseña si el usuario existe
if (!password_verify($password, $user->getPassword())) {
    setErrorAndRedirect("Contraseña incorrecta.");
}

// Iniciar sesión si las credenciales son correctas
$_SESSION['user_id'] = $user->getId();
$_SESSION['user_email'] = $user->getEmail();
$_SESSION['user_name'] = $user->getName();

header("Location: ../views/account.php");
exit();

// Función para manejar errores y redirigir
function setErrorAndRedirect($message)
{
    $_SESSION['error_message'] = $message;
    header("Location: ../views/index.php");
    exit();
}
