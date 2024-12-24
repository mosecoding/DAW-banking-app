<?php
session_start();
include "../classes/CrudAccount.php";
include "../../config/connection.php";

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/index.php");
    exit();
}

// Verificar si se envió un formulario válido
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action'], $_POST['amount'])) {
    header("Location: ../views/account.php");
    exit();
}

// Asignar los valores de la acción y el monto enviados en el formulario
$action = $_POST['action'];
$amount = floatval($_POST['amount']);

// Validar que el monto sea mayor que 0
if ($amount <= 0) {
    $_SESSION['error_message'] = "El monto debe ser mayor a 0.";
    header("Location: ../views/account.php");
    exit();
}

// Crear una instancia de la clase CrudAccount para interactuar con la base de datos
$crudAccount = new CrudAccount($pdo);

// Obtener la cuenta del usuario basado en su ID de sesión
$account = $crudAccount->getAccountByUserId($_SESSION['user_id']);

// Verificar si la cuenta no existe
if (!$account) {
    $_SESSION['success_message'] = "No se ha encontrado su cuenta.";
    exit();
}

// Obtener los detalles de la cuenta
$accountId = $account["id"];
$balance = $account["balance"];


// Si la acción es agregar dinero
if ($action === 'add') {
    // Intenta agregar el monto al saldo actual
    if ($crudAccount->addMoney($accountId, $balance + $amount)) {
        $_SESSION['success_message'] = "Se ha agregado dinero.";
    } else {
        $_SESSION['error_message'] = "No se pudo agregar el dinero.";
    }
} elseif ($action === 'withdraw') {
    // Si la acción es retirar dinero
    if ($balance < $amount) {
        $_SESSION['error_message'] = "Fondos insuficientes.";
    } else {
        // Intenta retirar el dinero
        if ($crudAccount->withdrawMoney($accountId, $balance - $amount)) {
            $_SESSION['success_message'] = "Se ha retirado el dinero.";
        } else {
            $_SESSION['error_message'] = "No se pudo retirar el dinero.";
        }
    }
}
header("Location: ../views/account.php");
exit();
