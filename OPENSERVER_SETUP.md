# 📚 Полная инструкция по установке для OpenServer 5.4.3

## Требования

- ✅ OpenServer 5.4.3
- ✅ Apache 2.4+
- ✅ PHP 7.4+
- ✅ MySQL 5.7+
- ✅ Git (опционально)
- ✅ Администраторские права на компьютере

## Установка

### 1️⃣ Подготовка проекта

```bash
# Клонируйте репозиторий или скачайте ZIP
git clone https://github.com/MOLOD777/ovchinnikov.git

# Или распакуйте загруженный ZIP
```

### 2️⃣ Размещение в OpenServer

```bash
# Скопируйте всю папку проекта в domains
C:\OpenServer\domains\ratekom

# Финальная структура должна быть такой:
C:\OpenServer\domains\ratekom
├── .gitignore
├── README.md
├── INSTALL.md
├── OPENSERVER_SETUP.md
├── OPENSERVER_QUICK_START.md
├── config/
│   ├── config.php
│   └── database.php
├── public/
│   ├── .htaccess
│   ├── index.php
│   └── assets/
│       ├── css/
│       ├── js/
│       └── img/
├── src/
│   └── pages/
│       ├── login.php
│       ├── dashboard.php
│       ├── news.php
│       ├── employees.php
│       ├── profile.php
│       ├── documents.php
│       └── admin/
│           └── dashboard.php
├── templates/
│   ├── header.php
│   └── footer.php
├── database/
│   └── schema.sql
└── logs/ (создаётся автоматически)
```

### 3️⃣ Создание директорий

```bash
cd C:\OpenServer\domains\ratekom

# Создайте папки для логов и загрузок
mkdir logs
mkdir uploads

# Установите права доступа (для Windows это требуется редко)
icacls logs /grant Everyone:(OI)(CI)F
icacls uploads /grant Everyone:(OI)(CI)F
```

### 4️⃣ Создание и импорт базы данных

#### Способ 1: Через phpMyAdmin (рекомендуется)

1. **Запустите OpenServer**
   - Нажмите на иконку OpenServer в системном трее
   - Убедитесь, что Apache и MySQL запущены (зелёные индикаторы)

2. **Откройте phpMyAdmin**
   - Меню OpenServer → phpMyAdmin
   - Или откройте в браузере: `http://127.0.0.1/phpmyadmin/`

3. **Авторизуйтесь**
   - Пользователь: `root`
   - Пароль: (оставить пустым)
   - Нажать **Вход**

4. **Создайте базу данных**
   - Нажмите вкладку **Новая БД**
   - Имя БД: `ratecom_intranet`
   - Кодировка: `utf8mb4_unicode_ci`
   - Нажмите **Создать**

5. **Импортируйте схему**
   - Перейдите в вкладку **Импорт** (откроется автоматически)
   - Выберите файл: `C:\OpenServer\domains\ratekom\database\schema.sql`
   - Нажмите **Выполнить**

✅ База данных создана!

#### Способ 2: Через командную строку

```bash
# Откройте Command Prompt от администратора

# Перейдите в папку MySQL
cd "C:\OpenServer\modules\database\MySQL5.7.14"

# Импортируйте схему
bin\mysql.exe -u root < "C:\OpenServer\domains\ratekom\database\schema.sql"
```

### 5️⃣ Настройка Apache для виртуального хоста

Обычно OpenServer это делает автоматически, но если потребуется:

**Отредактируйте файл:**
```
C:\OpenServer\modules\Apache2.4\conf\extra\httpd-vhosts.conf
```

**Добавьте в конец:**
```apache
<VirtualHost *:80>
    ServerName ratekom.openserver
    ServerAlias ratekom.*
    DocumentRoot "C:\OpenServer\domains\ratekom\public"
    
    <Directory "C:\OpenServer\domains\ratekom\public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog "C:\OpenServer\domains\ratekom\logs\error.log"
    CustomLog "C:\OpenServer\domains\ratekom\logs\access.log" combined
</VirtualHost>
```

**Перезагрузите Apache:**
- Меню OpenServer → Apache → Перезагрузить

### 6️⃣ Проверка установки

1. **Убедитесь, что OpenServer запущен**
   ```
   http://127.0.0.1 - должна открыться главная страница OpenServer
   ```

2. **Откройте сайт в браузере**
   ```
   http://ratekom.openserver
   или
   http://localhost/domains/ratekom/public
   ```

3. **Вы должны увидеть страницу входа**
   - Если видите ошибку подключения к БД - см. раздел **Решение проблем**
   - Если видите 404 - проверьте структуру папок

### 7️⃣ Вход в систему

**Администратор:**
- Email: `admin@ratecom.ru`
- Пароль: `password123`

**Обычный пользователь:**
- Email: `user@ratecom.ru`
- Пароль: `password123`

✅ **Установка завершена!**

---

## ⚙️ Дополнительная конфигурация

### Изменение пароля БД (опционально)

Если вы хотите установить пароль для БД:

1. В phpMyAdmin: Учётные записи → Пользователь root → Изменить пароль
2. Обновите `config/database.php`:
   ```php
   define('DB_PASS', 'ваш_пароль');
   ```

### Размещение на другом домене

Если вы разместили проект в другой папке (например, `C:\OpenServer\domains\mysite`):

1. Скопируйте папку с новым именем
2. Убедитесь, что `.htaccess` находится в папке `public/`
3. Откройте в браузере: `http://mysite.openserver`

### Резервная копия БД

**Через phpMyAdmin:**
1. Выберите БД `ratecom_intranet`
2. Нажмите вкладку **Экспорт**
3. Выберите **Быстрый э��спорт**
4. Сохраните файл

**Через командную строку:**
```bash
cd "C:\OpenServer\modules\database\MySQL5.7.14"
bin\mysqldump.exe -u root ratecom_intranet > backup.sql
```

---

## 🐛 Решение проблем

### Ошибка: "Не удалось подключиться к БД"

**Решение:**
1. Убедитесь, что MySQL запущен (зелёный индикатор в OpenServer)
2. Проверьте файл `logs/error.log`
3. Откройте phpMyAdmin и убедитесь, что БД `ratecom_intranet` существует
4. Перезагрузите OpenServer (остановить и запустить)

### Ошибка: 404 Not Found

**Решение:**
1. Проверьте, что папка находится в `C:\OpenServer\domains\ratekom`
2. Убедитесь, что файл `.htaccess` находится в папке `public/`
3. Перезагрузите Apache через меню OpenServer

### Белый экран при входе

**Решение:**
1. Откройте `logs/error.log` и посмотрите текст ошибки
2. Проверьте права доступа к папкам `logs` и `uploads`
3. Убедитесь, что PHP может писать в эти папки
4. Перезагрузите OpenServer

### Медленная загрузка сайта

**Решение:**
1. Убедитесь, что MySQL не перегружен
2. Очистите логи: удалите содержимое папки `logs/`
3. Перезагрузите OpenServer
4. Проверьте, что других приложений не открыто на портах 80/3306

### Ошибка CORS при загрузке ресурсов

**Решение:**
Редактируйте `public/.htaccess` и добавьте:
```apache
<IfModule mod_headers.c>
    Header set Access-Control-Allow-Origin "*"
</IfModule>
```

---

## 📋 Файлы конфигурации

### config/database.php
Параметры подключения к БД. Обычно не требует изменений для OpenServer.

### config/config.php
Основная конфигурация приложения. Автоматически определяет URL.

### public/.htaccess
Правила Apache для маршрутизации. Не удалять!

---

## 🔒 Безопасность

### Рекомендации для продакшена:

1. **Измените пароли по умолчанию**
   - Администратор
   - БД (root)
   - OpenServer

2. **Установите SSL сертификат**
   - OpenServer поддерживает самоподписанные сертификаты

3. **Отключите debug режим**
   - Установите `display_errors` на `0` в `config/config.php`

4. **Регулярно архивируйте БД**
   - Используйте phpMyAdmin → Экспорт

5. **Мониторьте логи**
   - Проверяйте `logs/error.log` и `logs/access.log`

---

## 📞 Поддержка

Если возникнут вопросы:

1. Проверьте файл `logs/error.log`
2. Убедитесь, что все требования выполнены
3. Попробуйте перезагрузить OpenServer
4. Обратитесь к документации OpenServer: http://open-server.ru/

---

## ✅ Чеклист после установки

- [ ] OpenServer установлен и запущен
- [ ] Проект находится в `C:\OpenServer\domains\ratekom`
- [ ] БД `ratecom_intranet` создана и импортирована
- [ ] Папки `logs` и `uploads` существуют
- [ ] Сайт открывается по адресу `http://ratekom.openserver`
- [ ] Можно войти с учётными данными admin@ratecom.ru / password123
- [ ] Панель администратора доступна
- [ ] Логирование работает (файл `logs/error.log` создан)

**Поздравляем! Инструкция завершена! 🎉**
