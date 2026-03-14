<?php
require_once __DIR__ . '/admin_auth.php';
require_admin();
require_once __DIR__ . '/db.php';

$db = db_connect();

// Actions: mark read/unread, delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = intval($_POST['id'] ?? 0);
    if ($id > 0) {
        if ($action === 'read') {
            $stmt = $db->prepare('UPDATE contact_messages SET is_read=1 WHERE id=?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
        } elseif ($action === 'unread') {
            $stmt = $db->prepare('UPDATE contact_messages SET is_read=0 WHERE id=?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
        } elseif ($action === 'delete') {
            $stmt = $db->prepare('DELETE FROM contact_messages WHERE id=?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
        }
    }
    header('Location: admin-inbox.php');
    exit;
}

// Fetch messages
$messages = [];
$res = $db->query('SELECT * FROM contact_messages ORDER BY is_read ASC, created_at DESC');
if ($res) {
    while ($row = $res->fetch_assoc()) { $messages[] = $row; }
    $res->free();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Inbox - Contact Messages</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .container{ max-width:1100px; margin: 1.25rem auto; padding: 0 1rem; }
        .inbox-card{ background:var(--card-bg,#fff); border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,.08); }
        .inbox-head{ display:flex; align-items:center; justify-content:space-between; gap:1rem; padding:1rem 1.25rem; border-bottom:1px solid rgba(0,0,0,.08); }
        .inbox-table{ width:100%; border-collapse: collapse; }
        .inbox-table th, .inbox-table td{ padding:.75rem .75rem; border-bottom:1px solid rgba(0,0,0,.08); vertical-align: top; }
        .badge{ padding:.2rem .5rem; border-radius:999px; font-size:.8rem; }
        .badge.unread{ background:#ffe9a8; color:#7a5a00; }
        .badge.read{ background:#d1e7dd; color:#0f5132; }
        .actions{ display:flex; gap:.4rem; }
        .btn{ border:1px solid #ccc; background:#f8f9fa; padding:.35rem .55rem; border-radius:6px; cursor:pointer; }
        .btn.danger{ border-color:#dc3545; color:#dc3545; }
        .subject{ font-weight:600; }
        .message{ white-space: pre-wrap; }
    </style>
</head>
<body>
    <div class="container">
        <div class="inbox-card">
            <div class="inbox-head">
                <div style="display:flex; align-items:center; gap:.6rem;">
                    <i class="fas fa-inbox"></i>
                    <h2 style="margin:0; font-size:1.1rem;">Contact Messages</h2>
                </div>
                <div>
                    <a href="admin-dashboard.php" class="btn"><i class="fas fa-arrow-left"></i> Back</a>
                </div>
            </div>
            <div style="padding: .75rem 1rem; overflow-x:auto;">
                <?php if (empty($messages)): ?>
                    <p style="margin: .75rem 0;">No messages yet.</p>
                <?php else: ?>
                <table class="inbox-table">
                    <thead>
                        <tr>
                            <th style="width:72px;">Status</th>
                            <th>From</th>
                            <th>Email / Phone</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th style="width:150px;">Received</th>
                            <th style="width:220px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $m): ?>
                        <tr>
                            <td>
                                <span class="badge <?php echo $m['is_read'] ? 'read' : 'unread'; ?>">
                                    <?php echo $m['is_read'] ? 'Read' : 'Unread'; ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($m['name']); ?></td>
                            <td>
                                <div><?php echo htmlspecialchars($m['email']); ?></div>
                                <?php if (!empty($m['phone'])): ?>
                                    <div><?php echo htmlspecialchars($m['phone']); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="subject"><?php echo htmlspecialchars($m['subject']); ?></td>
                            <td class="message"><?php echo nl2br(htmlspecialchars($m['message'])); ?></td>
                            <td><?php echo htmlspecialchars($m['created_at']); ?></td>
                            <td>
                                <form method="post" style="display:inline-block;" onsubmit="return confirm('Mark as read?');">
                                    <input type="hidden" name="id" value="<?php echo (int)$m['id']; ?>">
                                    <input type="hidden" name="action" value="read">
                                    <button class="btn" <?php echo $m['is_read'] ? 'disabled' : ''; ?>>Read</button>
                                </form>
                                <form method="post" style="display:inline-block;" onsubmit="return confirm('Mark as unread?');">
                                    <input type="hidden" name="id" value="<?php echo (int)$m['id']; ?>">
                                    <input type="hidden" name="action" value="unread">
                                    <button class="btn" <?php echo !$m['is_read'] ? 'disabled' : ''; ?>>Unread</button>
                                </form>
                                <form method="post" style="display:inline-block;" onsubmit="return confirm('Delete this message?');">
                                    <input type="hidden" name="id" value="<?php echo (int)$m['id']; ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button class="btn danger"><i class="fas fa-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
