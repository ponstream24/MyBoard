<?php
require '../includes/init_db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // ユーザー名の存在確認
    $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $userExists = $stmt->fetchColumn();

    if ($userExists) {
        $error = "このユーザー名はすでに使用されています。";
    } else {
        // 新しいユーザーの登録
        $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);

        try {
            $stmt->execute();
            header("Location: login.php");
            exit;
        } catch (PDOException $e) {
            $error = "エラー: " . $e->getMessage();
        }
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
        <?php if (!empty($error)): ?>
            <p class="help is-danger"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <div class="control">
            <button class="button is-primary" type="submit">登録</button>
        </div>
    </form>
    <p class="mt-4"><a href="login.php">すでにアカウントをお持ちですか？ログインはこちら</a></p>
</div>

<?php require '../includes/footer.php'; ?>
