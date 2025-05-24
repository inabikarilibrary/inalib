<?php
// Simple PHP Wiki

// Directory to store wiki pages
$pagesDir = __DIR__ . '/pages';
if (!is_dir($pagesDir)) mkdir($pagesDir);

// Get page name from URL, default to 'Home'
$page = isset($_GET['page']) ? preg_replace('/[^a-zA-Z0-9_\-]/', '', $_GET['page']) : 'Home';
$pageFile = "$pagesDir/$page.txt";

// Handle saving edits
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    file_put_contents($pageFile, $_POST['content']);
    header("Location: ?page=" . urlencode($page));
    exit;
}

// Load page content
$content = file_exists($pageFile) ? htmlspecialchars(file_get_contents($pageFile)) : '';

// List all pages
$pages = array_diff(scandir($pagesDir), ['.', '..']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Wiki: <?= htmlspecialchars($page) ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2em; }
        .sidebar { float: left; width: 200px; }
        .content { margin-left: 220px; }
        textarea { width: 100%; height: 300px; }
    </style>
</head>
<body>
<div class="sidebar">
    <h3>Pages</h3>
    <ul>
        <?php foreach ($pages as $p): ?>
            <li><a href="?page=<?= urlencode(basename($p, '.txt')) ?>">
                <?= htmlspecialchars(basename($p, '.txt')) ?>
            </a></li>
        <?php endforeach; ?>
    </ul>
    <form method="get">
        <input type="text" name="page" placeholder="New page" required>
        <button type="submit">Go</button>
    </form>
</div>
<div class="content">
    <h1><?= htmlspecialchars($page) ?></h1>
    <form method="post">
        <textarea name="content"><?= $content ?></textarea><br>
        <button type="submit">Save</button>
    </form>
</div>
</body>
</html>