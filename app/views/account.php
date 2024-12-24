<?php
session_start();
include "../classes/CrudAccount.php";
include "../../config/connection.php";

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Obtener el balance del usuario desde la base de datos
$crudAccount = new CrudAccount($pdo);
$account = $crudAccount->getAccountByUserId($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">

<?php
$title = "Cuenta";
include "../layouts/head.php";
?>

<body class="bg-gray-100">
    <?php
    include "../layouts/navbar.php";
    ?>
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md bg-white rounded-lg shadow-md p-6">
            <h2 class="text-3xl font-bold text-gray-800 text-center mb-4">Cuenta de Ahorros</h2>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="bg-red-500 text-white p-4 mb-4 rounded">
                    <?php echo $_SESSION['error_message']; ?>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="bg-green-500 text-white p-4 mb-4 rounded">
                    <?php echo $_SESSION['success_message']; ?>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <p class="text-center text-gray-700">
                Hola, <span class="font-semibold"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>. Bienvenido a tu cuenta.
            </p>
            <div class="mt-6">
                <h3 class="text-2xl font-semibold text-gray-800 text-center mb-4">Saldo Actual</h3>
                <div class="text-center text-4xl font-bold text-green-500">
                    S/<?php echo number_format($account["balance"], 2); ?>
                </div>
            </div>
            <div class="mt-8 flex gap-4">
                <!-- Botón para agregar dinero -->
                <button onclick="openModal('add-money-modal')" class="w-1/2 bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                    Agregar Dinero
                </button>
                <!-- Botón para sacar dinero -->
                <button onclick="openModal('withdraw-money-modal')" class="w-1/2 bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-600">
                    Sacar Dinero
                </button>
            </div>
        </div>
    </div>

    <div id="add-money-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Agregar Dinero</h2>
            <form method="POST" action="../controllers/account_controller.php">
                <input type="hidden" name="action" value="add">
                <label for="add_amount" class="block text-gray-700 mb-2">Monto:</label>
                <input type="number" name="amount" id="add_amount" class="w-full border border-gray-300 rounded px-4 py-2 mb-4" min="1" step="0.01" required>
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 w-full">Agregar</button>
            </form>
            <button onclick="closeModal('add-money-modal')" class="text-red-500 mt-4 block mx-auto">Cancelar</button>
        </div>
    </div>

    <div id="withdraw-money-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Sacar Dinero</h2>
            <form method="POST" action="../controllers/account_controller.php">
                <input type="hidden" name="action" value="withdraw">
                <label for="withdraw_amount" class="block text-gray-700 mb-2">Monto:</label>
                <input type="number" name="amount" id="withdraw_amount" class="w-full border border-gray-300 rounded px-4 py-2 mb-4" min="1" step="0.01" required>
                <button type="submit" class="bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-600 w-full">Sacar</button>
            </form>
            <button onclick="closeModal('withdraw-money-modal')" class="text-red-500 mt-4 block mx-auto">Cancelar</button>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>
</body>

</html>