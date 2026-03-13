<?php
require_once __DIR__ . '/admin_auth.php';
require_admin();
require_once __DIR__ . '/db.php';

$adminEmail = $_SESSION['admin_email'] ?? '';
$db = db_connect();

// Handle form submissions
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $title = trim($_POST['title']);
                $content = trim($_POST['content']);
                $type = $_POST['type'];
                $status = $_POST['status'];
                $display_order = (int)$_POST['display_order'];
                
                if ($title && $content) {
                    $stmt = $db->prepare("INSERT INTO announcements (title, content, type, status, display_order, created_by) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssIs", $title, $content, $type, $status, $display_order, $adminEmail);
                    
                    if ($stmt->execute()) {
                        $message = "Announcement added successfully!";
                    } else {
                        $error = "Error adding announcement: " . $db->error;
                    }
                } else {
                    $error = "Title and content are required.";
                }
                break;
                
            case 'edit':
                $id = (int)$_POST['id'];
                $title = trim($_POST['title']);
                $content = trim($_POST['content']);
                $type = $_POST['type'];
                $status = $_POST['status'];
                $display_order = (int)$_POST['display_order'];
                
                if ($title && $content && $id) {
                    $stmt = $db->prepare("UPDATE announcements SET title=?, content=?, type=?, status=?, display_order=?, updated_at=CURRENT_TIMESTAMP WHERE id=?");
                    $stmt->bind_param("ssssii", $title, $content, $type, $status, $display_order, $id);
                    
                    if ($stmt->execute()) {
                        $message = "Announcement updated successfully!";
                    } else {
                        $error = "Error updating announcement: " . $db->error;
                    }
                } else {
                    $error = "All fields are required.";
                }
                break;
                
            case 'delete':
                $id = (int)$_POST['id'];
                if ($id) {
                    $stmt = $db->prepare("DELETE FROM announcements WHERE id=?");
                    $stmt->bind_param("i", $id);
                    
                    if ($stmt->execute()) {
                        $message = "Announcement deleted successfully!";
                    } else {
                        $error = "Error deleting announcement: " . $db->error;
                    }
                } else {
                    $error = "Invalid announcement ID.";
                }
                break;
                
            case 'toggle_status':
                $id = (int)$_POST['id'];
                $new_status = $_POST['new_status'];
                
                if ($id && in_array($new_status, ['active', 'inactive'])) {
                    $stmt = $db->prepare("UPDATE announcements SET status=?, updated_at=CURRENT_TIMESTAMP WHERE id=?");
                    $stmt->bind_param("si", $new_status, $id);
                    
                    if ($stmt->execute()) {
                        $message = "Announcement status updated!";
                    } else {
                        $error = "Error updating status: " . $db->error;
                    }
                }
                break;
        }
    }
}

// Get all announcements
$announcements = [];
$result = $db->query("SELECT * FROM announcements ORDER BY display_order ASC, created_at DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row;
    }
}

// Get announcement for editing if edit_id is provided
$edit_announcement = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $stmt = $db->prepare("SELECT * FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_announcement = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Announcements | St. Anthony's Church</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="threads-fix.css">
    <link rel="stylesheet" href="responsive.css">
    <style>
        .admin-header { display:flex; align-items:center; justify-content:space-between; gap:1rem; padding:1rem 1.25rem; background:var(--card-bg); border-bottom:1px solid rgba(0,0,0,0.08); position:sticky; top:0; z-index:10; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .admin-title { display:flex; align-items:center; gap:.75rem; font-weight:700; }
        .admin-title i { color: var(--primary-color); }
        .admin-actions { display:flex; align-items:center; gap:.5rem; }
        .admin-container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        
        .success-message { background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #c3e6cb; display: flex; align-items: center; gap: 0.5rem; }
        .error-message { background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #f5c6cb; display: flex; align-items: center; gap: 0.5rem; }
        
        .form-card { background: var(--card-bg); border-radius: 10px; padding: 1.5rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 2rem; border: 1px solid var(--border-color); }
        .form-card h3 { color: var(--primary-color); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
        
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-color); }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 5px; font-size: 1rem; transition: border-color 0.3s ease; background: var(--bg-color); color: var(--text-color); box-sizing: border-box; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: var(--primary-color); }
        .form-group textarea { resize: vertical; min-height: 100px; }
        
        .form-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
        @media (max-width: 768px) { .form-row { grid-template-columns: 1fr; } }
        
        .btn { padding: 0.75rem 1.5rem; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease; font-size: 0.9rem; font-weight: 600; }
        .btn-primary { background: linear-gradient(45deg, var(--primary-color), var(--secondary-color)); color: white; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(139, 69, 19, 0.3); }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-secondary:hover { background: #5a6268; }
        .btn-success { background: #28a745; color: white; }
        .btn-success:hover { background: #218838; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-danger:hover { background: #c82333; }
        .btn-warning { background: #ffc107; color: #212529; }
        .btn-warning:hover { background: #e0a800; }
        .btn-outline { border: 1px solid var(--primary-color); color: var(--primary-color); background: transparent; }
        .btn-outline:hover { background: var(--primary-color); color: white; }
        .btn-sm { padding: 0.5rem 1rem; font-size: 0.8rem; }
        
        .announcements-grid { display: grid; gap: 1rem; }
        .announcement-card { background: var(--card-bg); border-radius: 10px; padding: 1.5rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border: 1px solid var(--border-color); transition: transform 0.2s ease; }
        .announcement-card:hover { transform: translateY(-2px); }
        .announcement-card.inactive { opacity: 0.6; border-left: 4px solid #dc3545; }
        .announcement-card.active { border-left: 4px solid #28a745; }
        
        .announcement-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; gap: 1rem; }
        .announcement-title { color: var(--primary-color); margin: 0; font-size: 1.2rem; }
        .announcement-meta { font-size: 0.85rem; color: var(--text-secondary, #666); margin-bottom: 0.5rem; }
        .announcement-content { color: var(--text-color); line-height: 1.6; margin-bottom: 1rem; }
        .announcement-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }
        
        .status-badge { padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; }
        .status-badge.active { background: #d4edda; color: #155724; }
        .status-badge.inactive { background: #f8d7da; color: #721c24; }
        
        .type-badge { padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; margin-left: 0.5rem; }
        .type-badge.announcement { background: #cce5ff; color: #004085; }
        .type-badge.update { background: #fff3cd; color: #856404; }
        .type-badge.alert { background: #f8d7da; color: #721c24; }
        
        .order-badge { background: var(--primary-color); color: white; padding: 0.25rem 0.5rem; border-radius: 50%; font-size: 0.75rem; font-weight: 600; min-width: 24px; text-align: center; }
        
        .form-actions { display: flex; gap: 1rem; margin-top: 1.5rem; }
        @media (max-width: 768px) { .form-actions { flex-direction: column; } .announcement-header { flex-direction: column; align-items: flex-start; } }
    </style>
</head>
<body>
    <div class="stars" id="stars"></div>
    <button class="dark-mode-toggle" id="darkModeToggle" title="Toggle Dark Mode"><i class="fas fa-moon"></i></button>

    <header class="admin-header">
        <div class="admin-title">
            <i class="fas fa-bullhorn"></i>
            <span>Manage Announcements</span>
        </div>
        <div class="admin-actions">
            <span class="muted"><i class="fas fa-user"></i> <?php echo htmlspecialchars($adminEmail); ?></span>
            <a href="admin-dashboard.php" class="btn-outline btn-sm" title="Dashboard"><i class="fas fa-dashboard"></i> Dashboard</a>
            <a href="admin-logout.php" class="btn-danger btn-sm" title="Logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </header>

    <main class="admin-container">
        <?php if ($message): ?>
        <div class="success-message">
            <i class="fas fa-check-circle"></i>
            <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>

        <?php if ($error): ?>
        <div class="error-message">
            <i class="fas fa-exclamation-triangle"></i>
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <!-- Add/Edit Announcement Form -->
        <div class="form-card">
            <h3>
                <i class="fas fa-<?php echo $edit_announcement ? 'edit' : 'plus'; ?>"></i>
                <?php echo $edit_announcement ? 'Edit Announcement' : 'Add New Announcement'; ?>
            </h3>
            
            <form method="POST" action="">
                <input type="hidden" name="action" value="<?php echo $edit_announcement ? 'edit' : 'add'; ?>">
                <?php if ($edit_announcement): ?>
                <input type="hidden" name="id" value="<?php echo $edit_announcement['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="title">Announcement Title</label>
                    <input type="text" id="title" name="title" required 
                           value="<?php echo $edit_announcement ? htmlspecialchars($edit_announcement['title']) : ''; ?>"
                           placeholder="e.g., UPDATE 1, IMPORTANT NOTICE">
                </div>
                
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" required 
                              placeholder="Enter the announcement content..."><?php echo $edit_announcement ? htmlspecialchars($edit_announcement['content']) : ''; ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="type">Type</label>
                        <select id="type" name="type">
                            <option value="announcement" <?php echo ($edit_announcement && $edit_announcement['type'] === 'announcement') ? 'selected' : ''; ?>>Announcement</option>
                            <option value="update" <?php echo ($edit_announcement && $edit_announcement['type'] === 'update') ? 'selected' : ''; ?>>Update</option>
                            <option value="alert" <?php echo ($edit_announcement && $edit_announcement['type'] === 'alert') ? 'selected' : ''; ?>>Alert</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="active" <?php echo ($edit_announcement && $edit_announcement['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo ($edit_announcement && $edit_announcement['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="display_order">Display Order</label>
                        <input type="number" id="display_order" name="display_order" min="0" 
                               value="<?php echo $edit_announcement ? $edit_announcement['display_order'] : count($announcements) + 1; ?>"
                               placeholder="1">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-<?php echo $edit_announcement ? 'save' : 'plus'; ?>"></i>
                        <?php echo $edit_announcement ? 'Update Announcement' : 'Add Announcement'; ?>
                    </button>
                    
                    <?php if ($edit_announcement): ?>
                    <a href="announcements.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Existing Announcements -->
        <div class="form-card">
            <h3><i class="fas fa-list"></i> Current Announcements (<?php echo count($announcements); ?>)</h3>
            
            <?php if (empty($announcements)): ?>
            <p style="color: var(--text-secondary, #666); text-align: center; padding: 2rem;">
                <i class="fas fa-info-circle"></i> No announcements found. Add your first announcement above.
            </p>
            <?php else: ?>
            <div class="announcements-grid">
                <?php foreach ($announcements as $announcement): ?>
                <div class="announcement-card <?php echo $announcement['status']; ?>">
                    <div class="announcement-header">
                        <div>
                            <h4 class="announcement-title">
                                <span class="order-badge"><?php echo $announcement['display_order']; ?></span>
                                <?php echo htmlspecialchars($announcement['title']); ?>
                                <span class="type-badge <?php echo $announcement['type']; ?>">
                                    <?php echo ucfirst($announcement['type']); ?>
                                </span>
                            </h4>
                            <div class="announcement-meta">
                                Created: <?php echo date('M d, Y \a\t g:i A', strtotime($announcement['created_at'])); ?>
                                <?php if ($announcement['updated_at'] !== $announcement['created_at']): ?>
                                | Updated: <?php echo date('M d, Y \a\t g:i A', strtotime($announcement['updated_at'])); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <span class="status-badge <?php echo $announcement['status']; ?>">
                            <?php echo ucfirst($announcement['status']); ?>
                        </span>
                    </div>
                    
                    <div class="announcement-content">
                        <?php echo nl2br(htmlspecialchars($announcement['content'])); ?>
                    </div>
                    
                    <div class="announcement-actions">
                        <a href="?edit=<?php echo $announcement['id']; ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        
                        <form method="POST" style="display: inline;" onsubmit="return confirm('Toggle status for this announcement?')">
                            <input type="hidden" name="action" value="toggle_status">
                            <input type="hidden" name="id" value="<?php echo $announcement['id']; ?>">
                            <input type="hidden" name="new_status" value="<?php echo $announcement['status'] === 'active' ? 'inactive' : 'active'; ?>">
                            <button type="submit" class="btn <?php echo $announcement['status'] === 'active' ? 'btn-secondary' : 'btn-success'; ?> btn-sm">
                                <i class="fas fa-<?php echo $announcement['status'] === 'active' ? 'pause' : 'play'; ?>"></i>
                                <?php echo $announcement['status'] === 'active' ? 'Deactivate' : 'Activate'; ?>
                            </button>
                        </form>
                        
                        <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this announcement? This action cannot be undone.')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $announcement['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </main>
    
    <script src="script.js"></script>
</body>
</html>