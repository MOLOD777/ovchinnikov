<?php
/**
 * Документооборот и архив
 */

require_once __DIR__ . '/../../templates/header.php';

$db = Database::getInstance();
$conn = $db->getConnection();

$category = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : '';
$page_num = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$per_page = 15;
$offset = ($page_num - 1) * $per_page;

// Получение категорий
$categories_result = $conn->query('SELECT DISTINCT category FROM documents ORDER BY category ASC');
$categories = $categories_result->fetch_all(MYSQLI_ASSOC);

// Получение документов
if (!empty($category)) {
    $query = 'SELECT id, name, category, file_path, uploaded_at, uploaded_by FROM documents 
              WHERE category = ? 
              ORDER BY uploaded_at DESC 
              LIMIT ? OFFSET ?';
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sii', $category, $per_page, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $documents = $result->fetch_all(MYSQLI_ASSOC);
    
    $count_query = 'SELECT COUNT(*) as total FROM documents WHERE category = ?';
    $count_stmt = $conn->prepare($count_query);
    $count_stmt->bind_param('s', $category);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $count_row = $count_result->fetch_assoc();
    $total = $count_row['total'];
    
    $count_stmt->close();
    $stmt->close();
} else {
    $query = 'SELECT id, name, category, file_path, uploaded_at, uploaded_by FROM documents 
              ORDER BY uploaded_at DESC 
              LIMIT ? OFFSET ?';
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $per_page, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $documents = $result->fetch_all(MYSQLI_ASSOC);
    
    $count_result = $conn->query('SELECT COUNT(*) as total FROM documents');
    $count_row = $count_result->fetch_assoc();
    $total = $count_row['total'];
    
    $stmt->close();
}

$total_pages = ceil($total / $per_page);
?>

<div class="container">
    <h1>Документооборот</h1>
    
    <div class="doc-filters">
        <h3>Категории:</h3>
        <ul>
            <li><a href="?page=documents" <?php echo empty($category) ? 'class="active"' : ''; ?>>Все документы</a></li>
            <?php foreach ($categories as $cat): ?>
                <li>
                    <a href="?page=documents&category=<?php echo urlencode($cat['category']); ?>" 
                       <?php echo $category === $cat['category'] ? 'class="active"' : ''; ?>>
                        <?php echo h($cat['category']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    
    <div class="documents-table">
        <table>
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Категория</th>
                    <th>Дата загрузки</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($documents as $doc): ?>
                    <tr>
                        <td><?php echo h($doc['name']); ?></td>
                        <td><?php echo h($doc['category']); ?></td>
                        <td><?php echo date('d.m.Y', strtotime($doc['uploaded_at'])); ?></td>
                        <td>
                            <a href="/uploads/<?php echo h($doc['file_path']); ?>" class="btn btn-sm" download>
                                Скачать
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <?php 
                    $cat_param = !empty($category) ? '&category=' . urlencode($category) : '';
                    if ($i === $page_num): 
                ?>
                    <span class="active"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="?page=documents&p=<?php echo $i; ?><?php echo $cat_param; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../templates/footer.php'; ?>