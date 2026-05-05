<?php
/**
 * Основная конфигурация приложения для OpenServer
 */

// Подключение БД
require_once __DIR__ . '/database.php';

// Определение базового URL динамически
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base_path = dirname(dirname(__FILE__)) . '/public';
define('SITE_URL', $protocol . '://' . $host);
define('SITE_ROOT', dirname(dirname(__FILE__)));
define('PUBLIC_PATH', $base_path);

// Параметры сайта
define('SITE_NAME', 'ООО "РАТЕКОМ" - Интранет');
define('ADMIN_EMAIL', 'admin@ratecom.ru');

// Параметры сессии
define('SESSION_LIFETIME', 3600); // 1 час
define('REMEMBER_ME_LIFETIME', 86400 * 30); // 30 дней

// Параметры безопасности
define('PASSWORD_MIN_LENGTH', 8);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 900); // 15 минут

// Часовой пояс
date_default_timezone_set('Europe/Moscow');

// Обработка ошибок
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Создание папки логов если её нет
$log_dir = SITE_ROOT . '/logs';
if (!is_dir($log_dir)) {
    mkdir($log_dir, 0755, true);
}

ini_set('error_log', $log_dir . '/error.log');

// Запуск сессии
session_start();

// Функция для безопасного вывода
if (!function_exists('h')) {
    function h($text) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}

// Функция для редиректа
if (!function_exists('redirect')) {
    function redirect($url) {
        header('Location: ' . $url);
        exit();
    }
}

// Функция проверки авторизации
if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}

// Функция проверки прав администратора
if (!function_exists('isAdmin')) {
    function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
}

// Функция для логирования действий
if (!function_exists('logAction')) {
    function logAction($action, $details = null) {
        $db = Database::getInstance();
        $conn = $db->getConnection();
        
        $user_id = isLoggedIn() ? $_SESSION['user_id'] : null;
        $stmt = $conn->prepare('INSERT INTO system_logs (user_id, action, details) VALUES (?, ?, ?)');
        $stmt->bind_param('iss', $user_id, $action, $details);
        $stmt->execute();
        $stmt->close();
    }
}
?>
