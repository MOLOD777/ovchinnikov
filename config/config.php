<?php
/**
 * Основная конфигурация приложения
 */

// Подключение БД
require_once __DIR__ . '/database.php';

// Параметры сайта
define('SITE_NAME', 'ООО "РАТЕКОМ" - Интранет');
define('SITE_URL', 'http://localhost:8000');
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
ini_set('error_log', __DIR__ . '/../logs/error.log');

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
?>