<?php
require_once __DIR__ . '/admin_auth.php';
require_admin();
require_once __DIR__ . '/db.php';

$adminEmail = $_SESSION['admin_email'] ?? '';

// Get database connection
$db = db_connect();

// Get statistics
$stats = [
    'total_messages' => 0,
    'unread_messages' => 0,
    'total_members' => 0
];

// Count contact messages
$result = $db->query('SELECT COUNT(*) as total FROM contact_messages');
if ($result) {
    $stats['total_messages'] = $result->fetch_assoc()['total'];
    $result->free();
}

$result = $db->query('SELECT COUNT(*) as unread FROM contact_messages WHERE is_read = 0');
if ($result) {
    $stats['unread_messages'] = $result->fetch_assoc()['unread'];
    $result->free();
}

// You can add member count here if you have a members table
// $result = $db->query('SELECT COUNT(*) as total FROM members');
// if ($result) {
//     $stats['total_members'] = $result->fetch_assoc()['total'];
//     $result->free();
// }

// Get recent contact messages
$recent_messages = [];
$result = $db->query('SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5');
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $recent_messages[] = $row;
    }
    $result->free();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | St. Anthony's Church Green Park</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="threads-fix.css">
    <link rel="stylesheet" href="responsive.css">
    <link rel="stylesheet" href="gallery.css">
    <link rel="stylesheet" href="mobile-enhancements.css">
    <style>
        .admin-header { display:flex; align-items:center; justify-content:space-between; gap:1rem; padding:1rem 1.25rem; background:var(--card-bg); border-bottom:1px solid rgba(0,0,0,0.08); position:sticky; top:0; z-index:10; }
        .admin-title { display:flex; align-items:center; gap:.75rem; font-weight:700; }
        .admin-title i { color: var(--primary-color); }
        .admin-actions { display:flex; align-items:center; gap:.5rem; }
        .admin-container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        
        .stats-section { margin-bottom: 2rem; }
        .stats-section h3 { color: var(--primary-color); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
        
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .stat-card { background: var(--card-bg); border-radius: 10px; padding: 1.5rem; box-shadow: 0 3px 10px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 1rem; transition: transform 0.2s ease; }
        .stat-card:hover { transform: translateY(-2px); }
        .stat-icon { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: linear-gradient(45deg, var(--primary-color), var(--secondary-color)); color: white; font-size: 1.5rem; }
        .stat-icon.unread { background: linear-gradient(45deg, #ff6b6b, #ff8787); }
        .stat-info h4 { margin: 0; font-size: 1.8rem; font-weight: bold; color: var(--text-color); }
        .stat-info p { margin: 0; color: var(--text-secondary, #666); font-size: 0.9rem; }
        
        .cards { margin-bottom: 2rem; }
        .cards h3 { color: var(--primary-color); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
        .cards-grid { display:grid; grid-template-columns: repeat(auto-fill, minmax(280px,1fr)); gap:1rem; }
        .card { background:var(--card-bg); border-radius:10px; padding:1.25rem; box-shadow:0 5px 15px rgba(0,0,0,0.08); transition: transform .15s ease; border: 1px solid var(--border-color); }
        .card:hover { transform: translateY(-2px); }
        .card h3 { display:flex; align-items:center; gap:.5rem; margin:0 0 .5rem; color: var(--primary-color); }
        .muted { color: var(--text-secondary, #666); font-size: .95rem; margin-bottom: 1rem; }
        
        .unread-notice { color: #ff6b6b; font-weight: 600; margin: 0.5rem 0; display: flex; align-items: center; gap: 0.5rem; }
        
        .btn-outline { border:1px solid var(--primary-color); color:var(--primary-color); background:transparent; padding:.5rem .75rem; border-radius:8px; cursor:pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease; }
        .btn-outline:hover { background: var(--primary-color); color: white; }
        .btn-danger { background:#b00020; color:#fff; border:none; }
        .admin-toolbar { display:flex; gap:.5rem; flex-wrap:wrap; margin:1rem 0 1.25rem; }
        .toolbar-chip { background:rgba(0,0,0,0.05); padding:.35rem .6rem; border-radius:999px; font-size:.9rem; display: flex; align-items: center; gap: 0.25rem; }
        .toolbar-chip.unread { background: rgba(255, 107, 107, 0.1); color: #ff6b6b; font-weight: 600; }
        .grid-actions { margin-top:.75rem; display:flex; gap:.5rem; flex-wrap:wrap; }
        .notice { margin: 1rem 0; padding:.75rem; border-radius:8px; background: rgba(25,135,84,.12); color:#0f5132; }
        
        .recent-section { margin-top: 2rem; }
        .recent-section h3 { color: var(--primary-color); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
        .recent-messages { background: var(--card-bg); border-radius: 10px; border: 1px solid var(--border-color); overflow: hidden; }
        .message-item { padding: 1rem; border-bottom: 1px solid var(--border-color); transition: background-color 0.2s ease; }
        .message-item:last-child { border-bottom: none; }
        .message-item:hover { background: rgba(0,0,0,0.02); }
        .message-item.unread { border-left: 4px solid #ff6b6b; background: rgba(255, 107, 107, 0.05); }
        .message-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem; flex-wrap: wrap; }
        .message-header strong { color: var(--text-color); }
        .message-email { color: var(--primary-color); font-size: 0.9rem; }
        .message-date { color: var(--text-secondary, #666); font-size: 0.85rem; margin-left: auto; }
        .unread-badge { background: #ff6b6b; color: white; padding: 0.2rem 0.5rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600; }
        .message-preview { color: var(--text-secondary, #666); font-size: 0.9rem; line-height: 1.4; }
        .view-all { padding: 1rem; text-align: center; background: rgba(0,0,0,0.02); }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); }
            .cards-grid { grid-template-columns: 1fr; }
            .message-header { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
            .message-date { margin-left: 0; }
        }
    </style>
</head>
<body>
    <div class="stars" id="stars"></div>
    <button class="dark-mode-toggle" id="darkModeToggle" title="Toggle Dark Mode"><i class="fas fa-moon"></i></button>

    <header class="admin-header">
        <div class="admin-title">
            <i class="fas fa-shield-alt"></i>
            <span>Admin Dashboard</span>
        </div>
        <div class="admin-actions">
            <span class="muted"><i class="fas fa-user"></i> <?php echo htmlspecialchars($adminEmail); ?></span>
            <a href="index.html" class="btn-outline" title="Home"><i class="fas fa-home"></i> Home</a>
            <a href="admin-logout.php" class="btn-danger" title="Logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </header>

    <main class="admin-container">
        <div class="notice">
            <strong>Welcome!</strong> You are securely logged in to the admin panel. Here you can manage all church activities and communications.
        </div>

        <div class="admin-toolbar">
            <span class="toolbar-chip"><i class="fas fa-user-shield"></i> Admin</span>
            <span class="toolbar-chip"><i class="fas fa-lock"></i> Session Active</span>
            <span class="toolbar-chip"><i class="fas fa-envelope"></i> <?php echo $stats['total_messages']; ?> Total Messages</span>
            <?php if ($stats['unread_messages'] > 0): ?>
            <span class="toolbar-chip unread"><i class="fas fa-exclamation-circle"></i> <?php echo $stats['unread_messages']; ?> Unread</span>
            <?php endif; ?>
        </div>

        <!-- Statistics Cards -->
        <section class="stats-section">
            <h3><i class="fas fa-chart-bar"></i> Dashboard Overview</h3>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="stat-info">
                        <h4><?php echo $stats['total_messages']; ?></h4>
                        <p>Total Messages</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon unread">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h4><?php echo $stats['unread_messages']; ?></h4>
                        <p>Unread Messages</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h4><?php echo $stats['total_members']; ?></h4>
                        <p>Registered Members</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div class="stat-info">
                        <h4><?php echo date('M d'); ?></h4>
                        <p>Today's Date</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quick Actions -->
        <section class="cards">
            <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
            <div class="cards-grid">
                <article class="card">
                    <h3><i class="fas fa-envelope-open-text"></i> Contact Messages</h3>
                    <p class="muted">Review and manage messages submitted via the contact form.</p>
                    <?php if ($stats['unread_messages'] > 0): ?>
                    <p class="unread-notice"><i class="fas fa-exclamation-triangle"></i> You have <?php echo $stats['unread_messages']; ?> unread messages!</p>
                    <?php endif; ?>
                    <div class="grid-actions">
                        <a class="btn-outline" href="admin-inbox.php"><i class="fas fa-inbox"></i> View Messages</a>
                    </div>
                </article>
                
                <article class="card">
                    <h3><i class="fas fa-users"></i> Member Management</h3>
                    <p class="muted">View and manage church member registrations and details.</p>
                    <div class="grid-actions">
                        <button class="btn-outline" onclick="alert('Member management feature coming soon!')"><i class="fas fa-users"></i> View Members</button>
                    </div>
                </article>
                
                <article class="card">
                    <h3><i class="fas fa-bullhorn"></i> Church Updates</h3>
                    <p class="muted">Post or edit announcements shown on the homepage.</p>
                    <div class="grid-actions">
                        <a class="btn-outline" href="announcements.php"><i class="fas fa-plus"></i> Manage Announcements</a>
                        <a class="btn-outline" href="announcements.php"><i class="fas fa-edit"></i> View All</a>
                    </div>
                </article>
                
                <article class="card">
                    <h3><i class="fas fa-calendar-alt"></i> Events</h3>
                    <p class="muted">Add upcoming events and schedules.</p>
                    <div class="grid-actions">
                        <button class="btn-outline" onclick="alert('Feature coming soon!')"><i class="fas fa-plus"></i> New Event</button>
                        <button class="btn-outline" onclick="alert('Feature coming soon!')"><i class="fas fa-edit"></i> Manage</button>
                    </div>
                </article>
                
                <article class="card">
                    <h3><i class="fas fa-images"></i> Gallery</h3>
                    <p class="muted">Upload and organize gallery images.</p>
                    <div class="grid-actions">
                        <button class="btn-outline" onclick="alert('Feature coming soon!')"><i class="fas fa-upload"></i> Upload</button>
                        <button class="btn-outline" onclick="alert('Feature coming soon!')"><i class="fas fa-edit"></i> Manage</button>
                    </div>
                </article>
                
                <article class="card">
                    <h3><i class="fas fa-cog"></i> Site Settings</h3>
                    <p class="muted">Configure website settings and preferences.</p>
                    <div class="grid-actions">
                        <button class="btn-outline" onclick="alert('Feature coming soon!')"><i class="fas fa-cog"></i> Settings</button>
                    </div>
                </article>
            </div>
        </section>

        <!-- Recent Messages -->
        <?php if (!empty($recent_messages)): ?>
        <section class="recent-section">
            <h3><i class="fas fa-clock"></i> Recent Contact Messages</h3>
            <div class="recent-messages">
                <?php foreach ($recent_messages as $message): ?>
                <div class="message-item <?php echo $message['is_read'] ? 'read' : 'unread'; ?>">
                    <div class="message-header">
                        <strong><?php echo htmlspecialchars($message['name']); ?></strong>
                        <span class="message-email"><?php echo htmlspecialchars($message['email']); ?></span>
                        <span class="message-date"><?php echo date('M d, Y', strtotime($message['created_at'])); ?></span>
                        <?php if (!$message['is_read']): ?>
                        <span class="unread-badge">New</span>
                        <?php endif; ?>
                    </div>
                    <div class="message-preview">
                        <?php echo htmlspecialchars(substr($message['message'], 0, 100)); ?>...
                    </div>
                </div>
                <?php endforeach; ?>
                <div class="view-all">
                    <a href="admin-inbox.php" class="btn-outline">
                        <i class="fas fa-envelope-open"></i> View All Messages
                    </a>
                </div>
            </div>
        </section>
        <?php endif; ?>
    </main>
    <script src="script.js"></script>
</body>
</html>
