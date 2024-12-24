<?php
session_start();
include "../classes/CrudAccount.php";
include "../../config/connection.php";

// Verificar si el usuario está autenticado
if (isset($_SESSION['user_id'])) {
    header("Location: account.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<?php
$title = "Acceder";
include "../layouts/head.php";
?>

<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md bg-white rounded-lg shadow-md p-6">
            <h2 class="text-3xl font-bold text-gray-800 text-center mb-4">Acceder</h2>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="bg-red-500 text-white p-4 mb-4 rounded">
                    <?php echo $_SESSION['error_message']; ?>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <form action="../controllers/login_controller.php" method="POST">
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-semibold">Correo</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="w-full mt-2 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="correo@dominio.com"
                        required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-semibold">Contraseña</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="w-full mt-2 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="••••••••"
                        required>
                </div>
                <div class="mt-6">
                    <button
                        type="submit"
                        class="w-full bg-blue-500 text-white py-2 px-4 font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Acceder
                    </button>
                </div>
            </form>
            <p class="mt-4 text-center text-sm text-gray-600">
                ¿No tienes una cuenta?
                <a href="register.php" class="text-blue-500 hover:underline">Crear una</a>
            </p>
        </div>
    </div>
</body>

</html>