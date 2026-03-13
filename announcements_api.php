<?php
// announcements_api.php - API to fetch announcements for frontend display

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/db.php';

$db = db_connect();

// Function to get active announcements
function getActiveAnnouncements($db) {
    $announcements = [];
    $result = $db->query("SELECT * FROM announcements WHERE status = 'active' ORDER BY display_order ASC, created_at DESC");
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $announcements[] = $row;
        }
        $result->free();
    }
    
    return $announcements;
}

// Handle different request methods
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Get all active announcements
    $announcements = getActiveAnnouncements($db);
    
    // Format for different display types
    $action = $_GET['action'] ?? 'list';
    
    switch ($action) {
        case 'ticker':
            // Format for ticker display
            $ticker_text = '';
            foreach ($announcements as $i => $announcement) {
                if ($i > 0) $ticker_text .= ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ';
                $ticker_text .= $announcement['title'] . ': ' . $announcement['content'];
            }
            
            echo json_encode([
                'success' => true,
                'ticker' => $ticker_text,
                'count' => count($announcements)
            ]);
            break;
            
        case 'list':
            // Format for list display
            echo json_encode([
                'success' => true,
                'announcements' => $announcements,
                'count' => count($announcements)
            ]);
            break;
            
        case 'updates':
            // Format for updates section
            $updates = [];
            foreach ($announcements as $announcement) {
                $updates[] = [
                    'id' => $announcement['id'],
                    'text' => $announcement['title'] . ': ' . $announcement['content'],
                    'type' => $announcement['type'],
                    'created_at' => $announcement['created_at']
                ];
            }
            
            echo json_encode([
                'success' => true,
                'updates' => $updates,
                'count' => count($updates)
            ]);
            break;
            
        default:
            echo json_encode([
                'success' => false,
                'error' => 'Invalid action'
            ]);
            break;
    }
    
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Method not allowed'
    ]);
}

$db->close();
?>