<?php
/**
 * Главная точка входа приложения
 */

require_once __DIR__ . '/../config/config.php';

// Определение текущей страницы
$page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 'home';
$action = isset($_GET['action']) ? htmlspecialchars($_GET['action']) : null;

// Маршрутизация
switch ($page) {
    case 'login':
        if (isLoggedIn()) {
            redirect(SITE_URL . '?page=dashboard');
        }
        require_once __DIR__ . '/../src/pages/login.php';
        break;
        
    case 'logout':
        session_destroy();
        redirect(SITE_URL . '?page=login');
        break;
        
    case 'dashboard':
        if (!isLoggedIn()) {
            redirect(SITE_URL . '?page=login');
        }
        require_once __DIR__ . '/../src/pages/dashboard.php';
        break;
        
    case 'news':
        if (!isLoggedIn()) {
            redirect(SITE_URL . '?page=login');
        }
        require_once __DIR__ . '/../src/pages/news.php';
        break;
        
    case 'employees':
        if (!isLoggedIn()) {
            redirect(SITE_URL . '?page=login');
        }
        require_once __DIR__ . '/../src/pages/employees.php';
        break;
        
    case 'profile':
        if (!isLoggedIn()) {
            redirect(SITE_URL . '?page=login');
        }
        require_once __DIR__ . '/../src/pages/profile.php';
        break;
        
    case 'documents':
        if (!isLoggedIn()) {
            redirect(SITE_URL . '?page=login');
        }
        require_once __DIR__ . '/../src/pages/documents.php';
        break;
        
    case 'admin':
        if (!isLoggedIn() || !isAdmin()) {
            redirect(SITE_URL . '?page=dashboard');
        }
        require_once __DIR__ . '/../src/pages/admin/dashboard.php';
        break;
        
    default:
        if (isLoggedIn()) {
            redirect(SITE_URL . '?page=dashboard');
        } else {
            redirect(SITE_URL . '?page=login');
        }
}
?>