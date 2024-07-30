<?php
require '../includes/init_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    $stmt = $db->prepare("INSERT INTO posts (user_id, content) VALUES (:user_id, :content)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':content', $content);
    
    try {
        $stmt->execute();
        header("Location: post.php");
        exit;
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

require '../includes/header.php';
?>

<?php if (isset($_SESSION['user_id'])): ?>
    <div class="box">
        <form method="POST" action="">
            <div class="field">
                <label class="label">New Post</label>
                <div class="control">
                    <textarea class="textarea" name="content" placeholder="Write your post..." required></textarea>
                </div>
            </div>
            <?php if (isset($error)): ?>
                <p class="help is-danger"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <div class="control">
                <button class="button is-primary" type="submit">Post</button>
            </div>
        </form>
    </div>
<?php else: ?>
    <p>You need to <a href="login.php">login</a> to post.</p>
<?php endif; ?>

<h2 class="title">Posts</h2>

<?php
$stmt = $db->query("SELECT posts.id, posts.content, posts.created_at, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($posts as $post) {
    echo "<div class='box'><strong>" . htmlspecialchars($post['username']) . ":</strong> " . htmlspecialchars($post['content']) . " <em>(" . $post['created_at'] . ")</em></div>";
}
?>

<?php require '../includes/footer.php'; ?>
