<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/layout.php';

try {
    $cars = db()->query(
        'SELECT id, name, year, type, engine, horsepower, price_usd, image
         FROM cars
         ORDER BY id'
    )->fetchAll();
} catch (PDOException $ex) {
    error_log('car_catalog index db error: ' . $ex->getMessage());
    render_error_page(500, 'تعذر تحميل البيانات', 'حدث خطأ أثناء الاتصال بقاعدة البيانات. يرجى المحاولة لاحقاً.');
}
$total = count($cars);
?>
<?php page_head('دليل السيارات — الرئيسية'); ?>
  <header>
    <div class="container header-inner">
      <?php the_brand(); ?>
      <div class="controls">
        <label class="visually-hidden" for="q">ابحث بالاسم</label>
        <input id="q" type="search" placeholder="ابحث بالاسم..." aria-label="ابحث بالاسم" oninput="applyFilters()" />
        <label class="visually-hidden" for="type">نوع السيارة</label>
        <select id="type" aria-label="نوع السيارة" onchange="applyFilters()">
          <option value="">كل الأنواع</option>
          <option value="Sedan">سيدان</option>
          <option value="Coupe">كوبيه</option>
          <option value="SUV">SUV</option>
          <option value="Electric Sedan">كهربائية</option>
        </select>
        <label class="visually-hidden" for="sort">ترتيب النتائج</label>
        <select id="sort" aria-label="ترتيب النتائج" onchange="applyFilters()">
          <option value="name">الاسم</option>
          <option value="price_up">السعر ⬆︎</option>
          <option value="price_down">السعر ⬇︎</option>
        </select>
        <button class="primary" onclick="applyFilters()">تطبيق</button>
        <button class="ghost btn" onclick="resetFilters()">مسح</button>
        <label class="favonly"><input type="checkbox" id="favonly" onchange="applyFilters()" /> المفضلة فقط</label>
      </div>
    </div>
  </header>

  <main class="container">
    <div class="result-row">
      <p id="count" class="result-count" aria-live="polite">عرض <?php echo $total; ?> من <?php echo $total; ?></p>
    </div>
    <section id="grid" class="grid" aria-label="قائمة السيارات">
      <?php foreach ($cars as $c): $id = (int)$c['id']; ?>
      <article class="card reveal" data-id="<?php echo $id; ?>"
               data-name="<?php echo e($c['name']); ?>"
               data-type="<?php echo e($c['type']); ?>"
               data-price="<?php echo e($c['price_usd']); ?>">
        <div class="card-actions">
          <button class="icon-btn fav" aria-label="إضافة إلى المفضلة" aria-pressed="false" data-id="<?php echo $id; ?>" onclick="toggleFav(event, <?php echo $id; ?>)">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 4 4 6.5 4c1.74 0 3.41 1.01 4.22 2.5C11.09 5.01 12.76 4 14.5 4 17 4 19 6 19 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
          </button>
        </div>
        <img src="<?php echo e($c['image']); ?>" alt="<?php echo e($c['name']); ?>" width="800" height="450" loading="lazy" onerror="this.onerror=null;this.src='<?php echo IMG_FALLBACK; ?>'" />
        <div class="content">
          <span class="badge"><?php echo e($c['type']); ?> • <span dir="ltr"><?php echo e($c['year']); ?></span></span>
          <h3 class="title"><?php echo e($c['name']); ?></h3>
          <div class="meta">المحرك: <span dir="ltr"><?php echo e($c['engine']); ?></span> • القوة: <span dir="ltr"><?php echo e($c['horsepower']); ?></span> حصان</div>
          <div class="price"><span dir="ltr">$<?php echo number_format((int)$c['price_usd']); ?></span></div>
          <a class="badge" href="car.php?id=<?php echo $id; ?>">التفاصيل</a>
        </div>
      </article>
      <?php endforeach; ?>
    </section>
    <div id="empty" class="empty" hidden>
      <p>لا توجد سيارات مطابقة. جرّب تعديل البحث أو <button class="linklike" onclick="resetFilters()">مسح الفلاتر</button>.</p>
    </div>
  </main>

  <?php site_footer('© ' . date('Y') . ' دليل السيارات — تصميم احترافي'); ?>

  <script src="main.js"></script>
</body>
</html>
