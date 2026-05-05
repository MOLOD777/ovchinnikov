/**
 * Основной JavaScript интранета
 */

// Инициализация
document.addEventListener('DOMContentLoaded', function() {
    console.log('Интранет-портал ООО "РАТЕКОМ" загружен');
    
    // Подтверждение при удалении
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Вы уверены? Это действие нельзя отменить.')) {
                e.preventDefault();
            }
        });
    });
});

// Функция для показа уведомления
function showNotification(message, type = 'info') {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.textContent = message;
    
    const mainContent = document.querySelector('.main-content');
    if (mainContent) {
        mainContent.insertBefore(alert, mainContent.firstChild);
        
        // Автоматическое удаление через 5 секунд
        setTimeout(() => {
            alert.remove();
        }, 5000);
    }
}

// Экспорт функций для использования на других страницах
window.intranett = {
    showNotification: showNotification
};
