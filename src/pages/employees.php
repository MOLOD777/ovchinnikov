<?php
/**
 * База данных сотрудников
 */

require_once __DIR__ . '/../../templates/header.php';

$db = Database::getInstance();
$conn = $db->getConnection();

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page_num = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$per_page = 20;
$offset = ($page_num - 1) * $per_page;

// Построение запроса с поиском
if (!empty($search)) {
    $search_param = '%' . $search . '%';
    $query = 'SELECT id, name, position, department, email, phone, active FROM employees 
              WHERE active = 1 AND (name LIKE ? OR position LIKE ? OR department LIKE ?)
              ORDER BY name ASC 
              LIMIT ? OFFSET ?';
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssii', $search_param, $search_param, $search_param, $per_page, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $employees = $result->fetch_all(MYSQLI_ASSOC);
    
    // Подсчет общего количества
    $count_query = 'SELECT COUNT(*) as total FROM employees 
                   WHERE active = 1 AND (name LIKE ? OR position LIKE ? OR department LIKE ?)';
    $count_stmt = $conn->prepare($count_query);
    $count_stmt->bind_param('sss', $search_param, $search_param, $search_param);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $count_row = $count_result->fetch_assoc();
    $total = $count_row['total'];
    $count_stmt->close();
    $stmt->close();
} else {
    $query = 'SELECT id, name, position, department, email, phone, active FROM employees 
              WHERE active = 1
              ORDER BY name ASC 
              LIMIT ? OFFSET ?';
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $per_page, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $employees = $result->fetch_all(MYSQLI_ASSOC);
    
    $count_result = $conn->query('SELECT COUNT(*) as total FROM employees WHERE active = 1');
    $count_row = $count_result->fetch_assoc();
    $total = $count_row['total'];
    $stmt->close();
}

$total_pages = ceil($total / $per_page);
?>

<div class="container">
    <h1>База данных сотрудников</h1>
    
    <div class="search-box">
        <form method="GET">
            <input type="hidden" name="page" value="employees">
            <input type="text" name="search" placeholder="Поиск по имени, должности, отделу..." 
                   value="<?php echo h($search); ?>">
            <button type="submit" class="btn btn-primary">Поиск</button>
            <?php if (!empty($search)): ?>
                <a href="?page=employees" class="btn btn-secondary">Очистить</a>
            <?php endif; ?>
        </form>
    </div>
    
    <div class="employees-table">
        <table>
            <thead>
                <tr>
                    <th>ФИО</th>
                    <th>Должность</th>
                    <th>Отдел</th>
                    <th>Email</th>
                    <th>Телефон</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $emp): ?>
                    <tr>
                        <td><a href="?page=profile&id=<?php echo $emp['id']; ?>"><?php echo h($emp['name']); ?></a></td>
                        <td><?php echo h($emp['position']); ?></td>
                        <td><?php echo h($emp['department']); ?></td>
                        <td><?php echo h($emp['email']); ?></td>
                        <td><?php echo h($emp['phone'] ?? '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <?php 
                    $search_param = !empty($search) ? '&search=' . urlencode($search) : '';
                    if ($i === $page_num): 
                ?>
                    <span class="active"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="?page=employees&p=<?php echo $i; ?><?php echo $search_param; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../templates/footer.php'; ?>