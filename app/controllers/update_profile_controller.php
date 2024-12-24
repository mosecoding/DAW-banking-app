<?php
session_start();
include "../classes/CrudUser.php";
include "../classes/User.php";
include "../../config/connection.php";

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/index.php");
    exit();
}

// Redirigir si no es un POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../views/profile.php");
    exit();
}

// Obtener los datos del formulario
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);

// Validar los campos
if (empty($name) || empty($email)) {
    $_SESSION['error_message'] = "El nombre y el correo electrónico son obligatorios.";
    header("Location: ../views/profile.php");
    exit();
}

// Validar el formato del correo electrónico
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_message'] = "El correo electrónico no tiene un formato válido.";
    header("Location: ../views/profile.php");
    exit();
}

// Instanciar CrudUser
$crudUser = new CrudUser($pdo);
$currentUser = $crudUser->getUserByEmail($_SESSION['user_email']);

// Verificar si el correo ya está en uso
$existingUser = $crudUser->getUserByEmail($email);
if ($existingUser && $existingUser->getId() !== $_SESSION['user_id']) {
    $_SESSION['error_message'] = "Este correo electrónico ya está en uso.";
    header("Location: ../views/profile.php");
    exit();
}

// Evitar actualizaciones innecesarias
$isNameChanged = $name !== $currentUser->getName();
$isEmailChanged = $email !== $currentUser->getEmail();
$isPasswordChanged = !empty($password) && !password_verify($password, $currentUser->getPassword());

if (!$isNameChanged && !$isEmailChanged && !$isPasswordChanged) {
    $_SESSION['error_message'] = "No se detectaron cambios en el perfil.";
    header("Location: ../views/profile.php");
    exit();
}

// Si se cambia la contraseña, encriptarla
if ($isPasswordChanged) {
    $newPassword = $password;
} else {
    $newPassword = $currentUser->getPassword(); // Mantener la contraseña actual
}

// Crear un objeto User con los nuevos datos
$user = new User($_SESSION['user_id'], $name, $email, $newPassword);

// Actualizar el perfil en la base de datos
if ($crudUser->updateUser($user)) {
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;
    $_SESSION['success_message'] = "Perfil actualizado con éxito.";
} else {
    $_SESSION['error_message'] = "Hubo un error al actualizar el perfil.";
}

header("Location: ../views/profile.php");
exit();
