<?php
/**
 * Личный кабинет / профиль сотрудника
 */

require_once __DIR__ . '/../../templates/header.php';

$db = Database::getInstance();
$conn = $db->getConnection();

$emp_id = isset($_GET['id']) ? (int)$_GET['id'] : $_SESSION['user_id'];

// Получение данных сотрудника
$stmt = $conn->prepare('SELECT id, name, email, position, department, phone, hire_date, avatar FROM employees WHERE id = ? AND active = 1');
$stmt->bind_param('i', $emp_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<div class="alert alert-danger">Сотрудник не найден</div>';
    require_once __DIR__ . '/../../templates/footer.php';
    exit();
}

$employee = $result->fetch_assoc();
$stmt->close();

// Обработка обновления профиля (только для своего профиля)
$update_msg = '';
if ($emp_id === $_SESSION['user_id'] && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    
    $update = $conn->prepare('UPDATE employees SET phone = ? WHERE id = ?');
    $update->bind_param('si', $phone, $emp_id);
    
    if ($update->execute()) {
        $update_msg = 'Профиль успешно обновлен';
        $employee['phone'] = $phone;
    } else {
        $update_msg = 'Ошибка при обновлении профиля';
    }
    
    $update->close();
}
?>

<div class="container">
    <div class="profile-header">
        <h1><?php echo h($employee['name']); ?></h1>
    </div>
    
    <?php if ($update_msg): ?>
        <div class="alert alert-success"><?php echo h($update_msg); ?></div>
    <?php endif; ?>
    
    <div class="profile-info">
        <div class="info-group">
            <label>Должность:</label>
            <p><?php echo h($employee['position']); ?></p>
        </div>
        
        <div class="info-group">
            <label>Отдел:</label>
            <p><?php echo h($employee['department']); ?></p>
        </div>
        
        <div class="info-group">
            <label>Email:</label>
            <p><?php echo h($employee['email']); ?></p>
        </div>
        
        <div class="info-group">
            <label>Телефон:</label>
            <p><?php echo h($employee['phone'] ?? '-'); ?></p>
        </div>
        
        <div class="info-group">
            <label>Дата приёма:</label>
            <p><?php echo date('d.m.Y', strtotime($employee['hire_date'])); ?></p>
        </div>
    </div>
    
    <?php if ($emp_id === $_SESSION['user_id']): ?>
        <div class="profile-edit">
            <h2>Редактирование профиля</h2>
            <form method="POST" class="edit-form">
                <div class="form-group">
                    <label for="phone">Телефон:</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo h($employee['phone'] ?? ''); ?>">
                </div>
                
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../templates/footer.php'; ?>