# MyApp

MyAppはシンプルなPHPとSQLiteを使用したサンプルアプリケーションです。このアプリケーションでは、ユーザー登録、ログイン、投稿、およびチャンネル機能があります。

## インストール手順

### 必要な環境

- PHP 7.4以上
- SQLite

### セットアップ手順

1. リポジトリをクローンします。

   ```bash
   git clone https://github.com/ponstream24/MyBoard.git
   cd MyBoard
   ```

2. 必要な依存関係をインストールします。PHPとSQLiteが正しくインストールされていることを確認してください。

> データベースの初期化は不要です。初回アクセス時に自動的に初期化されます。

## 使用方法

1. ローカルサーバーを起動します。

   ```bash
   php -S localhost:8000
   ```

2. ブラウザで http://localhost:8000/public/ にアクセスし、アプリケーションを使用します。

3. 初めてのアクセス時に、ユーザー登録を行ってください。

## 機能

- **ユーザー登録**: 新規ユーザーを登録し、アカウントを作成します。
- **ログイン/ログアウト**: ユーザー認証とセッション管理。
- **投稿**: 認証されたユーザーが投稿を作成し、チャンネルに投稿を分類できます。
- **チャンネル管理**: チャンネルの作成と、チャンネルごとの投稿の表示。

## 開発および貢献

このプロジェクトに貢献するには、以下の手順に従ってください：

1. プロジェクトをフォークします。
2. ブランチを作成します (`git checkout -b feature-branch`)。
3. 変更をコミットします (`git commit -m 'Add some feature'`)。
4. ブランチにプッシュします (`git push origin feature-branch`)。
5. プルリクエストを作成します。

## ライセンス

このプロジェクトはMITライセンスのもとで公開されています。詳細は`LICENSE`ファイルを参照してください。