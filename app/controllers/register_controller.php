<?php
include "../../config/connection.php";
include "../classes/User.php";
include "../classes/CrudUser.php";
include "../classes/CrudAccount.php";

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../views/register.php");
    exit();
}

// Obtener y validar datos del formulario
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($name) || empty($email) || empty($password)) {
    setErrorAndRedirect("Por favor, completa todos los campos.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setErrorAndRedirect("El correo no es válido.");
}

// Instanciar CrudUser y verificar si el correo ya está registrado
$crudUser = new CrudUser($pdo);

if ($crudUser->getUserByEmail($email)) {
    setErrorAndRedirect("El correo ya está registrado.");
}

// Crear el objeto de usuario y registrarlo
$user = new User(null, $name, $email, $password);

if ($crudUser->createUser($user)) {
    // Obtener el ID del usuario recién creado
    $createdUser = $crudUser->getUserByEmail($email);

    // Crear una cuenta asociada al usuario
    $crudAccount = new CrudAccount($pdo);
    if (!$crudAccount->createAccount($createdUser->getId())) {
        setErrorAndRedirect("Hubo un problema al crear la cuenta de ahorros.");
    }

    // Redirigir al login después del registro exitoso
    header("Location: ../views/index.php");
    exit();
}

setErrorAndRedirect("Hubo un problema al crear el usuario.");

// Función para manejar errores y redirigir
function setErrorAndRedirect($message)
{
    $_SESSION['error_message'] = $message;
    header("Location: ../views/register.php");
    exit();
}
