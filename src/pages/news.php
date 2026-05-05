<?php
/**
 * Раздел новостей и объявлений
 */

require_once __DIR__ . '/../../templates/header.php';

$db = Database::getInstance();
$conn = $db->getConnection();

$page_num = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$per_page = 10;
$offset = ($page_num - 1) * $per_page;

// Получение новостей с пагинацией
$query = 'SELECT id, title, content, author_id, created_at FROM news 
          WHERE published = 1 
          ORDER BY created_at DESC 
          LIMIT ? OFFSET ?';

$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();
$news_list = $result->fetch_all(MYSQLI_ASSOC);

// Получение общего количества
$count_result = $conn->query('SELECT COUNT(*) as total FROM news WHERE published = 1');
$count_row = $count_result->fetch_assoc();
$total_pages = ceil($count_row['total'] / $per_page);

$stmt->close();
?>

<div class="container">
    <h1>Новости и объявления</h1>
    
    <div class="news-list">
        <?php foreach ($news_list as $news): ?>
            <div class="news-card">
                <h2><?php echo h($news['title']); ?></h2>
                <div class="news-meta">
                    <small>Опубликовано: <?php echo date('d.m.Y H:i', strtotime($news['created_at'])); ?></small>
                </div>
                <div class="news-content">
                    <?php echo nl2br(h($news['content'])); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <?php if ($i === $page_num): ?>
                    <span class="active"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="?page=news&p=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../templates/footer.php'; ?>