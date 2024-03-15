<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $host = 'localhost';
    $dbname = 'bd_tela_login';
    $user = 'seu_usuario';
    $pass = 'nova_senha';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

        $sql = "INSERT INTO usuarios (nome_usuario, email, senha_hash) VALUES (:username, :email, :password)";
        $stmt = $pdo->prepare($sql);

        $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt->execute([
            ':username' => $_POST['username'],
            ':email' => $_POST['email'],
            ':password' => $passwordHash
        ]);

        echo "Usuário cadastrado com sucesso!";
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "Usuário ou email já cadastrado.";
        } else {
            die("Erro ao cadastrar usuário: " . $e->getMessage());
        }
    }
} else {
    echo "Método de requisição inválido.";
}
