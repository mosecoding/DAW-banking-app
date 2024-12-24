<?php
class CrudAccount
{
    private $pdo;

    // Constructor que inicializa la conexión PDO
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Crear una nueva cuenta
    public function createAccount($userId): bool
    {
        $sql = "INSERT INTO accounts (user_id, account_number, balance) VALUES (:userId, :accountNumber, :balance)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':userId' => $userId,
            ':accountNumber' => $this->generateAccountNumber(),
            ':balance' => 0
        ]);
    }

    // Obtener una cuenta por ID de usuario
    public function getAccountByUserId($userId): ?array
    {
        $sql = "SELECT * FROM accounts WHERE user_id = :userId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':userId' => $userId]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ?: null;
    }

    // Agregar dinero a una cuenta
    public function addMoney($accountId, $newBalance): bool
    {
        // Actualizar el saldo en la base de datos
        $sql = "UPDATE accounts SET balance = :balance WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $accountId,
            ':balance' => $newBalance
        ]);
    }

    // Retirar dinero de una cuenta
    public function withdrawMoney($accountId, $newBalance): bool
    {
        // Actualizar el saldo en la base de datos
        $sql = "UPDATE accounts SET balance = :balance WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        // Ejecutar la actualización y retornar el resultado
        return $stmt->execute([
            ':id' => $accountId,
            ':balance' => $newBalance
        ]);
    }

    // Generar un número de cuenta único
    private function generateAccountNumber(): string
    {
        return uniqid('ACC');
    }
}
