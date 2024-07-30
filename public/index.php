<?php
require '../includes/session.php';
require '../includes/init_db.php';

// チャンネルの取得
$channels = [];
try {
    $stmt = $db->query("SELECT * FROM channels");
    $channels = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
}

// 投稿の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['content'])) {
        // 新しい投稿の処理
        $content = $_POST['content'];
        $user_id = $_SESSION['user_id'];
        $channel_id = $_POST['channel_id'];

        $stmt = $db->prepare("INSERT INTO posts (user_id, content, channel_id) VALUES (:user_id, :content, :channel_id)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':channel_id', $channel_id);

        try {
            $stmt->execute();
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $error = "エラー: " . $e->getMessage();
        }
    } elseif (isset($_POST['delete_post'])) {
        // 投稿の削除処理
        $post_id = $_POST['post_id'];
        $user_id = $_SESSION['user_id'];

        $stmt = $db->prepare("DELETE FROM posts WHERE id = :post_id AND user_id = :user_id");
        $stmt->bindParam(':post_id', $post_id);
        $stmt->bindParam(':user_id', $user_id);

        try {
            $stmt->execute();
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $error = "エラー: " . $e->getMessage();
        }
    } elseif (isset($_POST['channel_name'])) {
        // チャンネルの作成処理
        $channel_name = $_POST['channel_name'];

        $stmt = $db->prepare("INSERT INTO channels (name) VALUES (:channel_name)");
        $stmt->bindParam(':channel_name', $channel_name);

        try {
            $stmt->execute();
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $error = "エラー: " . $e->getMessage();
        }
    }
}

// 投稿の取得（チャンネル指定あり）
$selected_channel_id = isset($_GET['channel_id']) ? intval($_GET['channel_id']) : 0;
$query = "SELECT posts.id, posts.content, posts.created_at, users.username, channels.name AS channel_name 
          FROM posts 
          JOIN users ON posts.user_id = users.id 
          JOIN channels ON posts.channel_id = channels.id";
$params = [];

if ($selected_channel_id > 0) {
    $query .= " WHERE posts.channel_id = :channel_id";
    $params[':channel_id'] = $selected_channel_id;
}

$query .= " ORDER BY posts.created_at DESC";

try {
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
}

require '../includes/header.php';
?>

<!-- チャンネル選択フォーム -->
<div class="box">
    <form method="GET" action="">
        <div class="field">
            <label class="label">チャンネルを選択</label>
            <div class="control">
                <div class="select">
                    <select name="channel_id" onchange="this.form.submit()">
                        <option value="0">すべてのチャンネル</option>
                        <?php foreach ($channels as $channel): ?>
                            <option value="<?= htmlspecialchars($channel['id']) ?>" <?= $selected_channel_id == $channel['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($channel['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- チャンネル作成フォーム -->
<?php if (isset($_SESSION['user_id'])): ?>
    <div class="box">
        <form method="POST" action="">
            <div class="field">
                <label class="label">新しいチャンネル名</label>
                <div class="control">
                    <input class="input" type="text" name="channel_name" placeholder="チャンネル名" required>
                </div>
            </div>
            <?php if (isset($error)): ?>
                <p class="help is-danger"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <div class="control">
                <button class="button is-primary" type="submit">チャンネルを作成</button>
            </div>
        </form>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['user_id'])): ?>
    <div class="box">
        <form method="POST" action="">
            <div class="field">
                <label class="label">チャンネル</label>
                <div class="control">
                    <div class="select">
                        <select name="channel_id" required>
                            <?php foreach ($channels as $channel): ?>
                                <option value="<?= htmlspecialchars($channel['id']) ?>"><?= htmlspecialchars($channel['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="field">
                <label class="label">新しい投稿</label>
                <div class="control">
                    <textarea class="textarea" name="content" placeholder="投稿内容を記入..." required></textarea>
                </div>
            </div>
            <?php if (isset($error)): ?>
                <p class="help is-danger"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <div class="control">
                <button class="button is-primary" type="submit">投稿</button>
            </div>
        </form>
    </div>
<?php else: ?>
    <p>投稿するには<a href="login.php">ログイン</a>が必要です。</p>
<?php endif; ?>

<h2 class="title">投稿一覧</h2>

<?php foreach ($posts as $post): ?>
    <div class='box'>
        <strong><?= htmlspecialchars($post['username']) ?>:</strong> <?= htmlspecialchars($post['content']) ?> <em>(<?= htmlspecialchars($post['created_at']) ?> | チャンネル: <?= htmlspecialchars($post['channel_name']) ?>)</em>
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']): ?>
            <form method='POST' action='' style='margin-top:10px;'>
                <input type='hidden' name='post_id' value='<?= $post['id'] ?>'>
                <button class='button is-danger' type='submit' name='delete_post'>削除</button>
            </form>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

<?php require '../includes/footer.php'; ?>
