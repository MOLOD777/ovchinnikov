<?php
/**
 * Главная панель после входа
 */

require_once __DIR__ . '/../../templates/header.php';

$db = Database::getInstance();
$conn = $db->getConnection();

// Получение статистики
$stats = [
    'employees' => 0,
    'news' => 0,
    'documents' => 0
];

$result = $conn->query('SELECT COUNT(*) as count FROM employees WHERE active = 1');
$row = $result->fetch_assoc();
$stats['employees'] = $row['count'];

$result = $conn->query('SELECT COUNT(*) as count FROM news WHERE published = 1');
$row = $result->fetch_assoc();
$stats['news'] = $row['count'];

$result = $conn->query('SELECT COUNT(*) as count FROM documents');
$row = $result->fetch_assoc();
$stats['documents'] = $row['count'];

// Получение последних новостей
$news_query = 'SELECT id, title, content, created_at FROM news WHERE published = 1 ORDER BY created_at DESC LIMIT 5';
$news_result = $conn->query($news_query);
$news_list = $news_result->fetch_all(MYSQLI_ASSOC);
?>

<div class="dashboard">
    <h1>Добро пожаловать, <?php echo h($_SESSION['user_name']); ?>!</h1>
    
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Сотрудники</h3>
            <p class="stat-number"><?php echo $stats['employees']; ?></p>
            <a href="?page=employees" class="btn btn-sm">Просмотр</a>
        </div>
        
        <div class="stat-card">
            <h3>Новости</h3>
            <p class="stat-number"><?php echo $stats['news']; ?></p>
            <a href="?page=news" class="btn btn-sm">Просмотр</a>
        </div>
        
        <div class="stat-card">
            <h3>Документы</h3>
            <p class="stat-number"><?php echo $stats['documents']; ?></p>
            <a href="?page=documents" class="btn btn-sm">Просмотр</a>
        </div>
    </div>
    
    <h2>Последние новости</h2>
    <div class="news-feed">
        <?php foreach ($news_list as $item): ?>
            <div class="news-item">
                <h3><?php echo h($item['title']); ?></h3>
                <p><?php echo nl2br(h(substr($item['content'], 0, 100))); ?>...</p>
                <small><?php echo date('d.m.Y H:i', strtotime($item['created_at'])); ?></small>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../../templates/footer.php'; ?>