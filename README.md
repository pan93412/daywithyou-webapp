# 「潑墨日子」NKUST 期末專案

「潑墨日子」是一個現代化的電商平台，專門提供精選的禮品商品。使用 Laravel 和 React 打造，提供流暢的購物體驗以及美觀、回應式的介面。

## 報告書



## 技術堆疊

- **後端**：Laravel 12
- **前端**：React 19 搭配 TypeScript
- **UI 框架**：Tailwind CSS
- **狀態管理**：Inertia.js
- **資料庫**：MySQL/PostgreSQL
- **部署**：[Zeabur](https://zeabur.com) 一鍵部署

## 開發

### 前置需求

- PHP 8.2+
- Composer
- Node.js (v18+)
- npm 或 pnpm

### 設定

1. 複製儲存庫：

   ```
   git clone https://github.com/yourusername/daywithyou-webapp.git
   cd daywithyou-webapp
   ```

2. 複製環境設定檔：
   ```
   cp .env.example .env
   ```

3. 啟動 [Laravel Sail](https://laravel.com/docs/sail)：
   ```
   ./vendor/bin/sail up -d
   ```

4. 安裝 JavaScript 依賴套件：
   ```
   ./vendor/bin/sail pnpm install
   ```

5. 產生應用程式金鑰：
   ```
   ./vendor/bin/sail artisan key:generate
   ```

6. 執行資料庫遷移：
   ```
   ./vendor/bin/sail artisan migrate
   ./vendor/bin/sail artisan db:seed DatabaseSeeder
   ./vendor/bin/sail artisan db:seed ProductSeeder
   ./vendor/bin/sail artisan db:seed NewsSeeder
   ```

7. 啟動 Vite 開發伺服器：
   ```
   ./vendor/bin/sail pnpm dev
   ```

8. 在瀏覽器中開啟 `http://localhost`。

### 測試

執行測試套件：

```
./vendor/bin/sail artisan test
```

### Linting

```
./vendor/bin/sail pint
./vendor/bin/sail pnpm lint
./vendor/bin/sail pnpm format
./vendor/bin/sail pnpm types
```

## 部署

### Zeabur 一鍵部署

1. 部署好 MySQL、Redis 服務
2. 使用 GitHub 儲存庫部署這個專案，並將 `.env.zeabur` 的內容貼上「環境變數」。
   注意您需要先自行產生 `APP_KEY`。
3. 待服務啟動完成後，執行 seeding：
   ```
   php artisan db:seed DatabaseSeeder
   php artisan db:seed ProductSeeder
   php artisan db:seed NewsSeeder
   ```
4. 將 `APP_ENV` 切回 `production` 後重新啟動服務即可。

## 貢獻

歡迎貢獻！請隨時提交 Pull Request。

## 授權

本專案採用 Apache 2.0 授權 - 詳細資訊請參閱 LICENSE 檔案。
