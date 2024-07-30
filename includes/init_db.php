<?php
require '../includes/db.php';

try {
    // SQLファイルのパス
    $sqlFile = '../db/database.sql';
    
    // SQLファイルの内容を読み込む
    $sql = file_get_contents($sqlFile);
    
    // SQLファイルが正しく読み込まれたか確認
    if ($sql === false) {
        throw new Exception("SQLファイルの読み込みに失敗しました。");
    }

    // SQLを実行
    $db->exec($sql);
} catch (PDOException $e) {
    echo "データベース初期化エラー: " . htmlspecialchars($e->getMessage());
} catch (Exception $e) {
    echo "エラー: " . htmlspecialchars($e->getMessage());
}
?>
