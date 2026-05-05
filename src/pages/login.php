<?php
/**
 * Страница входа
 */

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if (empty($email) || empty($password)) {
        $error = 'Заполните все поля';
    } else {
        $db = Database::getInstance();
        $conn = $db->getConnection();
        
        // Подготовленное выражение для безопасности
        $stmt = $conn->prepare('SELECT id, name, email, password, role FROM employees WHERE email = ? AND active = 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                // Успешный вход
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                
                // Обновление времени последнего входа
                $update = $conn->prepare('UPDATE employees SET last_login = NOW() WHERE id = ?');
                $update->bind_param('i', $user['id']);
                $update->execute();
                logAction('Вход в систему', $email);
                
                redirect(SITE_URL . '?page=dashboard');
            } else {
                $error = 'Неверный пароль';
                logAction('Ошибка входа', 'Неверный пароль для: ' . $email);
            }
        } else {
            $error = 'Пользователь не найден';
            logAction('Ошибка входа', 'Пользователь не найден: ' . $email);
        }
        
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Вход - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <h1><?php echo SITE_NAME; ?></h1>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo h($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo h($success); ?></div>
            <?php endif; ?>
            
            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">Пароль:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Войти</button>
            </form>
            
            <p class="login-info"><strong>Тестовые учётные данные:</strong><br>
            Email: <code>user@ratecom.ru</code><br>
            Пароль: <code>password123</code></p>
            
            <p class="login-info" style="background-color: #e8f4f8;">
            <strong>Администратор:</strong><br>
            Email: <code>admin@ratecom.ru</code><br>
            Пароль: <code>password123</code></p>
        </div>
    </div>
</body>
</html>
