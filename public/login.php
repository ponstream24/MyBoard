<?php
require '../includes/init_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
        exit;
    } else {
        $error = "ユーザー名またはパスワードが無効です。";
    }
}

require '../includes/header.php';
?>

<div class="box">
    <form method="POST" action="">
        <div class="field">
            <label class="label">ユーザー名</label>
            <div class="control">
                <input class="input" type="text" name="username" placeholder="ユーザー名" required>
            </div>
        </div>
        <div class="field">
            <label class="label">パスワード</label>
            <div class="control">
                <input class="input" type="password" name="password" placeholder="パスワード" required>
            </div>
        </div>
        <?php if (isset($error)): ?>
            <p class="help is-danger"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <div class="control">
            <button class="button is-primary" type="submit">ログイン</button>
        </div>
    </form>
    <p class="mt-4"><a href="register.php">アカウントがないですか？こちらから登録できます</a></p>
</div>

<?php require '../includes/footer.php'; ?>
