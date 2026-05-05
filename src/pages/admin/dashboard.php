<?php
/**
 * Панель администратора
 */

require_once __DIR__ . '/../../../templates/header.php';

$db = Database::getInstance();
$conn = $db->getConnection();

// Получение статистики
$stats_result = $conn->query('SELECT 
    (SELECT COUNT(*) FROM employees WHERE active = 1) as employees,
    (SELECT COUNT(*) FROM employees WHERE active = 0) as inactive_employees,
    (SELECT COUNT(*) FROM news WHERE published = 1) as published_news,
    (SELECT COUNT(*) FROM news WHERE published = 0) as draft_news,
    (SELECT COUNT(*) FROM documents) as total_documents');

$stats = $stats_result->fetch_assoc();
?>

<div class="admin-dashboard">
    <h1>Панель администратора</h1>
    
    <div class="admin-stats">
        <div class="stat-box">
            <h3>Активные сотрудники</h3>
            <p class="big-number"><?php echo $stats['employees']; ?></p>
            <a href="?page=admin&action=employees">Управление</a>
        </div>
        
        <div class="stat-box">
            <h3>Неактивные сотрудники</h3>
            <p class="big-number"><?php echo $stats['inactive_employees']; ?></p>
        </div>
        
        <div class="stat-box">
            <h3>Опубликованные новости</h3>
            <p class="big-number"><?php echo $stats['published_news']; ?></p>
            <a href="?page=admin&action=news">Управление</a>
        </div>
        
        <div class="stat-box">
            <h3>Черновики новостей</h3>
            <p class="big-number"><?php echo $stats['draft_news']; ?></p>
        </div>
        
        <div class="stat-box">
            <h3>Всего документов</h3>
            <p class="big-number"><?php echo $stats['total_documents']; ?></p>
            <a href="?page=admin&action=documents">Управление</a>
        </div>
    </div>
    
    <div class="admin-menu">
        <h2>Управление</h2>
        <ul>
            <li><a href="?page=admin&action=employees" class="btn btn-primary">Управление сотрудниками</a></li>
            <li><a href="?page=admin&action=news" class="btn btn-primary">Управление новостями</a></li>
            <li><a href="?page=admin&action=documents" class="btn btn-primary">Управление документами</a></li>
            <li><a href="?page=admin&action=settings" class="btn btn-primary">Настройки системы</a></li>
        </ul>
    </div>
</div>

<?php require_once __DIR__ . '/../../../templates/footer.php'; ?>