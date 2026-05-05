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
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="<?php echo SITE_URL; ?>/assets/img/favicon.ico">
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <div class="logo">
                <a href="?page=dashboard">
                    <img src="<?php echo SITE_URL; ?>/assets/img/logo.png" alt="РАТЕКОМ" class="logo-img">
                    <span><?php echo SITE_NAME; ?></span>
                </a>
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
                <span class="user-profile">
                    <?php echo h($_SESSION['user_name']); ?>
                </span>
                <a href="?page=profile" class="btn btn-sm">Профиль</a>
                <a href="?page=logout" class="btn btn-logout">Выход</a>
            </div>
        </div>
    </header>
    
    <main class="main-content">
