<?php
// Database configuration + tiny PDO helpers (plain PHP, no framework).
// MySQL/MariaDB via XAMPP. Credentials stay server-side only:
// this file is never printed into HTML or JavaScript.

// Local XAMPP defaults. Adjust here if your MySQL differs
// (e.g. custom password or port). For hosting, set these via
// environment variables instead of editing the file.
$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_NAME = getenv('DB_NAME') ?: 'car_catalog';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: ''; // XAMPP default: empty root password

/**
 * Shared PDO connection (singleton).
 *
 * @throws PDOException on connection failure (caller renders a safe page).
 */
function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    global $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS;
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $DB_HOST, $DB_NAME);
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false, // real prepared statements
    ]);
    return $pdo;
}

/** HTML-escape helper (output safety; separate from SQL safety). */
function e($value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

/**
 * Render a clean error page and stop. Never leaks SQL/credentials/traces.
 */
function render_error_page(int $status, string $title, string $message): void
{
    http_response_code($status);
    require_once __DIR__ . '/../includes/layout.php';
    page_head($title);
    ?>
  <header>
    <div class="container header-inner">
      <?php the_brand(); ?>
      <div style="display:flex;gap:10px">
        <a class="badge" href="index.php">الرجوع للقائمة</a>
      </div>
    </div>
  </header>
  <main class="container">
    <nav class="breadcrumb" aria-label="مسار التنقل"><a href="index.php">الرئيسية</a> › <span><?php echo e($title); ?></span></nav>
    <article class="panel" style="margin-top:16px">
      <div class="inner">
        <div class="section-head"><h3><?php echo e($title); ?></h3></div>
        <p style="margin-top:0"><?php echo e($message); ?></p>
      </div>
    </article>
  </main>
  <?php site_footer('© ' . date('Y') . ' دليل السيارات'); ?>
</body>
</html>
    <?php
    exit;
}
