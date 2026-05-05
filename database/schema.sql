-- Схема базы данных интранета ООО "РАТЕКОМ"

CREATE DATABASE IF NOT EXISTS ratecom_intranet CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ratecom_intranet;

-- Таблица сотрудников
CREATE TABLE employees (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    position VARCHAR(255) NOT NULL,
    department VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    hire_date DATE NOT NULL,
    role ENUM('user', 'manager', 'admin') DEFAULT 'user',
    avatar VARCHAR(255),
    active TINYINT DEFAULT 1,
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (email),
    INDEX (active),
    INDEX (department)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица новостей
CREATE TABLE news (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    author_id INT NOT NULL,
    published TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    published_at DATETIME,
    FOREIGN KEY (author_id) REFERENCES employees(id) ON DELETE CASCADE,
    INDEX (published),
    INDEX (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица документов
CREATE TABLE documents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description LONGTEXT,
    category VARCHAR(100) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_size INT,
    uploaded_by INT NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES employees(id) ON DELETE CASCADE,
    INDEX (category),
    INDEX (uploaded_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица комментариев к новостям
CREATE TABLE news_comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    news_id INT NOT NULL,
    author_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (news_id) REFERENCES news(id) ON DELETE CASCADE,
    FOREIGN KEY (author_id) REFERENCES employees(id) ON DELETE CASCADE,
    INDEX (news_id),
    INDEX (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица логов системы
CREATE TABLE system_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(255) NOT NULL,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES employees(id) ON DELETE SET NULL,
    INDEX (created_at),
    INDEX (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Вставка тестовых данных
INSERT INTO employees (name, email, password, position, department, phone, hire_date, role, active) VALUES
('Администратор', 'admin@ratecom.ru', '$2y$10$YoZADUlN7VGJ8h7U.4U7.u/Fz9gCGGVUuF.BLZ4E3Z3kZy5YT8X3u', 'Администратор', 'IT', '+7(999)999-99-99', '2020-01-01', 'admin', 1),
('Иван Петров', 'user@ratecom.ru', '$2y$10$YoZADUlN7VGJ8h7U.4U7.u/Fz9gCGGVUuF.BLZ4E3Z3kZy5YT8X3u', 'Специалист', 'HR', '+7(999)111-11-11', '2021-03-15', 'user', 1),
('Мария Сидорова', 'maria@ratecom.ru', '$2y$10$YoZADUlN7VGJ8h7U.4U7.u/Fz9gCGGVUuF.BLZ4E3Z3kZy5YT8X3u', 'Менеджер', 'Продажи', '+7(999)222-22-22', '2020-06-20', 'manager', 1),
('Алексей Иванов', 'alex@ratecom.ru', '$2y$10$YoZADUlN7VGJ8h7U.4U7.u/Fz9gCGGVUuF.BLZ4E3Z3kZy5YT8X3u', 'Инженер', 'IT', '+7(999)333-33-33', '2021-11-10', 'user', 1);

INSERT INTO news (title, content, author_id, published, published_at) VALUES
('Добро пожаловать в интранет!', 'Новый интранет-портал ООО "РАТЕКОМ" запущен! Здесь вы найдете всю необходимую информацию о компании, сотрудниках и документах.', 1, 1, NOW()),
('Важное объявление', 'Плановое обслуживание сервера будет проводиться в выходной день. Просим рассчитывать на возможные перерывы в работе.', 1, 1, NOW());

-- Примечание для пароля:
-- Хеш пароля: password123
-- Для создания хеша используйте: password_hash('password123', PASSWORD_BCRYPT)
