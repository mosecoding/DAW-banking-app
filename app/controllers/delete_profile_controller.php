<?php
session_start();
include "../classes/CrudUser.php";
include "../../config/connection.php";

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];

    // Instanciar CrudUser
    $crudUser = new CrudUser($pdo);

    // Intentar eliminar el perfil
    if ($crudUser->deleteUser($userId)) {
        // Cerrar sesión después de eliminar el perfil
        session_destroy();
        header("Location: ../views/index.php.");
        exit();
    } else {
        $_SESSION['error_message'] = "Hubo un error al eliminar el perfil.";
        header("Location: ../views/profile.php");
        exit();
    }
}
