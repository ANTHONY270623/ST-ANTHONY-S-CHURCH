<?php
require_once __DIR__ . '/config.php';

function db_connect_root(): mysqli {
    $mysqli = @new mysqli(DB_HOST, DB_USER, DB_PASS);
    if ($mysqli->connect_errno) {
        throw new Exception('Database connection failed: ' . $mysqli->connect_error);
    }
    $mysqli->set_charset('utf8mb4');
    return $mysqli;
}

function db_connect(): mysqli {
    // Ensure database exists
    $root = db_connect_root();
    $dbName = DB_NAME;
    if (!$root->select_db($dbName)) {
        $root->query("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $root->select_db($dbName);
    }
    // Ensure table exists
    $create = "CREATE TABLE IF NOT EXISTS contact_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(50) NULL,
        subject VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        is_read TINYINT(1) NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $root->query($create);
    return $root;
}
