<?php
session_start();
include "../classes/CrudUser.php";
include "../../config/connection.php";

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<?php
$title = "Perfil";
include "../layouts/head.php";
?>

<body class="bg-gray-100">
    <?php
    include "../layouts/navbar.php";
    ?>
    <div class="max-w-4xl mx-auto p-6 mt-10 bg-white rounded-lg shadow-md">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Editar Perfil</h2>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="bg-green-500 text-white p-4 mb-4 rounded">
                <?php echo $_SESSION['success_message']; ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="bg-red-500 text-white p-4 mb-4 rounded">
                <?php echo $_SESSION['error_message']; ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <form action="../controllers/update_profile_controller.php" method="POST">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium">Nombre</label>
                <input type="text" id="name" name="name" class="w-full mt-2 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium">Correo electrónico</label>
                <input type="email" id="email" name="email" class="w-full mt-2 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" value="<?php echo htmlspecialchars($_SESSION['user_email']); ?>" required>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-medium">Contraseña</label>
                <input type="password" id="password" name="password" class="w-full mt-2 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Deja vacío si no deseas cambiarla">
            </div>

            <button type="submit" class="w-full py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition duration-300">Actualizar Perfil</button>
        </form>

        <form action="delete_account.php" method="POST" class="mt-6">
            <button type="submit" class="w-full py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition duration-300">Eliminar Cuenta</button>
        </form>
    </div>

</body>

</html>