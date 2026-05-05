<?php
/**
 * Конфигурация подключения к базе данных для OpenServer 5.4.3
 */

// Параметры подключения OpenServer (стандартные)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ratecom_intranet');
define('DB_PORT', 3306);
define('DB_CHARSET', 'utf8mb4');

// Класс для работы с БД
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            // Используем mysqli для подключения
            $this->connection = new mysqli(
                DB_HOST,
                DB_USER,
                DB_PASS,
                DB_NAME,
                DB_PORT
            );
            
            if ($this->connection->connect_error) {
                throw new Exception('Ошибка подключения: ' . $this->connection->connect_error);
            }
            
            $this->connection->set_charset(DB_CHARSET);
        } catch (Exception $e) {
            // Логирование ошибки
            error_log('Database Error: ' . $e->getMessage());
            die('Ошибка БД: ' . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function query($sql) {
        return $this->connection->query($sql);
    }
    
    public function prepare($sql) {
        return $this->connection->prepare($sql);
    }
    
    public function close() {
        $this->connection->close();
    }
}

// Предотвращение прямого доступа
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'])) {
    exit('Прямой доступ запрещен');
}
?>
