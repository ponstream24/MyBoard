<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>MyBoard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .hero-head {
            position: fixed;
            width: 100%;
            z-index: 1000;
            background-color: #00d1b2;
        }
    </style>
</head>
<body>
    <section class="hero is-primary is-fullheight">
        <div class="hero-head">
            <nav class="navbar">
                <div class="container">
                    <div class="navbar-brand">
                        <a class="navbar-item" href="index.php">
                            <h1 class="title has-text-white">MyBoard</h1>
                        </a>
                        <span class="navbar-burger burger" data-target="navbarMenu">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </div>
                    <div id="navbarMenu" class="navbar-menu">
                        <div class="navbar-end">
                            <a class="navbar-item" href="index.php">ホーム</a>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <a class="navbar-item" href="logout.php">ログアウト</a>
                            <?php else: ?>
                                <a class="navbar-item" href="login.php">ログイン</a>
                                <a class="navbar-item" href="register.php">登録</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <div class="hero-body">
            <div class="container has-text-centered">
                <h2 class="subtitle has-text-white">シンプルなPHP & SQLite掲示板アプリ</h2>
            </div>
        </div>
    </section>
    <div class="container mt-5">
