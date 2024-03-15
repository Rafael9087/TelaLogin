<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $host = 'localhost';
    $dbname = 'bd_tela_login';
    $user = 'seu_usuario';
    $pass = 'nova_senha';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

        $sql = "SELECT id, nome_usuario, senha_hash FROM usuarios WHERE nome_usuario = :username OR email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':username' => $_POST['username'],
            ':email' => $_POST['username']
        ]);

        if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (password_verify($_POST['password'], $user['senha_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['nome_usuario'];

                header('Location: painel.php');
                exit;
            } else {
                echo "Senha incorreta!";
            }
        } else {
            echo "Usuário não encontrado!";
        }
    } catch (PDOException $e) {
        die("Erro de conexão com o banco de dados: " . $e->getMessage());
    }
} else {
    echo "Método de requisição inválido.";
}
