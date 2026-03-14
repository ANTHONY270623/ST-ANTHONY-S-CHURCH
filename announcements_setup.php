<?php
// announcements_setup.php - Database setup for announcements

require_once __DIR__ . '/db.php';

$db = db_connect();

// Create announcements table if it doesn't exist
$create_table_sql = "
CREATE TABLE IF NOT EXISTS announcements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    type ENUM('announcement', 'update', 'alert') DEFAULT 'announcement',
    status ENUM('active', 'inactive') DEFAULT 'active',
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by VARCHAR(255) DEFAULT 'admin'
)";

if ($db->query($create_table_sql)) {
    echo "Announcements table created successfully.\n";
} else {
    echo "Error creating announcements table: " . $db->error . "\n";
}

// Insert some default announcements if table is empty
$count_result = $db->query("SELECT COUNT(*) as count FROM announcements");
if ($count_result) {
    $count = $count_result->fetch_assoc()['count'];
    
    if ($count == 0) {
        $default_announcements = [
            [
                'title' => 'UPDATE 1',
                'content' => 'ON 27th JULY 2025 (SUNDAY) MASS WILL BE ON NEW CHAPEL.',
                'type' => 'update',
                'display_order' => 1
            ],
            [
                'title' => 'UPDATE 2',
                'content' => 'FR SEBASTAIN WIL BE NOT AVAILABLE FOR ONE WEEK',
                'type' => 'update',
                'display_order' => 2
            ],
            [
                'title' => 'UPDATE 3',
                'content' => 'HOUSE BLESSINGS ARE GOING ON PLEASE CONTACT COUNCIL MEMBERS.',
                'type' => 'update',
                'display_order' => 3
            ]
        ];
        
        foreach ($default_announcements as $announcement) {
            $stmt = $db->prepare("INSERT INTO announcements (title, content, type, display_order) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $announcement['title'], $announcement['content'], $announcement['type'], $announcement['display_order']);
            $stmt->execute();
        }
        
        echo "Default announcements inserted.\n";
    }
}

$db->close();
echo "Announcements setup completed.\n";
?>