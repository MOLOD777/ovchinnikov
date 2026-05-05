<?php
/**
 * Шаблон шапки сайта
 */

if (!isLoggedIn()) {
    redirect(SITE_URL . '?page=login');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <div class="logo">
                <a href="?page=dashboard"><?php echo SITE_NAME; ?></a>
            </div>
            
            <nav class="main-nav">
                <ul>
                    <li><a href="?page=dashboard">Главная</a></li>
                    <li><a href="?page=news">Новости</a></li>
                    <li><a href="?page=employees">Сотрудники</a></li>
                    <li><a href="?page=documents">Документы</a></li>
                    <?php if (isAdmin()): ?>
                        <li><a href="?page=admin">Администрация</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            
            <div class="user-menu">
                <a href="?page=profile" class="user-profile">
                    <?php echo h($_SESSION['user_name']); ?>
                </a>
                <a href="?page=logout" class="btn btn-logout">Выход</a>
            </div>
        </div>
    </header>
    
    <main class="main-content">