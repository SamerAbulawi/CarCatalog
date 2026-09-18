<?php
// Shared page chrome (plain PHP, no framework).
// Load AFTER config/database.php — helpers below use e().

/** Inline SVG placeholder used when a car image is missing/broken (no external services). */
const IMG_FALLBACK = "data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='800' height='450'%3E%3Crect width='100%25' height='100%25' fill='%23111826'/%3E%3Ctext x='50%25' y='52%25' fill='%2394a3b8' font-size='28' text-anchor='middle' font-family='sans-serif'%3ENo image%3C/text%3E%3C/svg%3E";

/** Opens the document: doctype, head, body. Marks <html> as .js so CSS reveal never hides cards without JS. */
function page_head(string $title): void
{
    ?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="دليل سيارات عربي: تصفح السيارات، قارن المواصفات والأسعار، واحفظ المفضلة." />
  <title><?php echo e($title); ?></title>
  <link rel="stylesheet" href="style.css" />
  <script>document.documentElement.className += ' js';</script>
</head>
<body>
    <?php
}

/** Brand block shared by every page header. */
function the_brand(): void
{
    ?>
      <div class="brand">
        <div class="logo" aria-hidden="true"></div>
        <h1>دليل السيارات</h1>
      </div>
    <?php
}

/** Shared footer. */
function site_footer(string $text): void
{
    ?>
  <footer><?php echo e($text); ?></footer>
    <?php
}
