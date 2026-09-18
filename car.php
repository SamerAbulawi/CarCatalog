<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/layout.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($id === false || $id === null) {
    render_error_page(404, 'الصفحة غير موجودة', '⚠️ الصفحة غير موجودة');
}

try {
    $stmt = db()->prepare('SELECT * FROM cars WHERE id = :id LIMIT 1');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $c = $stmt->fetch();
} catch (PDOException $ex) {
    error_log('car_catalog details db error: ' . $ex->getMessage());
    render_error_page(500, 'تعذر تحميل البيانات', 'حدث خطأ أثناء الاتصال بقاعدة البيانات. يرجى المحاولة لاحقاً.');
}

if (!$c) {
    render_error_page(404, 'الصفحة غير موجودة', '⚠️ الصفحة غير موجودة');
}

// EVs store 0 fuel — never display a misleading "0 L/100km".
$isEV = ((float)$c['fuel_economy_l_100km'] == 0.0);
$fuelKpi = $isEV ? 'كهربائية' : e($c['fuel_economy_l_100km']) . ' لتر/100كم';
$fuelSpec = $isEV ? 'كهربائية (بدون وقود)' : e($c['fuel_economy_l_100km']) . ' لتر/100كم';
?>
<?php page_head($c['name'] . ' — تفاصيل'); ?>
  <header>
    <div class="container header-inner">
      <?php the_brand(); ?>
      <div style="display:flex;gap:10px">
        <a class="badge" href="index.php">الرجوع للقائمة</a>
        <button class="badge" onclick="window.print()">طباعة المواصفات</button>
      </div>
    </div>
  </header>

  <main class="container">
    <nav class="breadcrumb" aria-label="مسار التنقل"><a href="index.php">الرئيسية</a> › <span><?php echo e($c['name']); ?></span></nav>

    <div class="hero">
      <img src="<?php echo e($c['image']); ?>" alt="<?php echo e($c['name']); ?>" width="1200" height="514" onerror="this.onerror=null;this.src='<?php echo IMG_FALLBACK; ?>'" />
      <div class="overlay"></div>
      <div class="title">
        <div>
          <h2><?php echo e($c['name']); ?> (<span dir="ltr"><?php echo e($c['year']); ?></span>)</h2>
          <div style="display:flex;gap:8px;flex-wrap:wrap">
            <span class="badge"><?php echo e($c['type']); ?></span>
            <span class="badge">المقاعد: <span dir="ltr"><?php echo e($c['seats']); ?></span></span>
            <span class="badge">الدفع: <span dir="ltr"><?php echo e($c['drivetrain']); ?></span></span>
            <span class="badge">ناقل: <?php echo e($c['transmission']); ?></span>
          </div>
        </div>
      </div>
    </div>

    <div class="layout">
      <!-- Main content -->
      <section>
        <article class="panel">
          <div class="inner">
            <div class="section-head"><h3>نظرة عامة</h3></div>
            <p style="margin-top:0"><?php echo e($c['description']); ?></p>
            <div class="kpis">
              <div class="kpi"><div class="label">القوة (حصان)</div><div class="value"><span dir="ltr"><?php echo e($c['horsepower']); ?></span></div></div>
              <div class="kpi"><div class="label">0–100 كم/س</div><div class="value"><span dir="ltr"><?php echo e($c['zero_to_hundred_sec']); ?></span> ث</div></div>
              <div class="kpi"><div class="label">السرعة القصوى</div><div class="value"><span dir="ltr"><?php echo e($c['top_speed_kmh']); ?></span> كم/س</div></div>
              <div class="kpi"><div class="label">استهلاك الوقود</div><div class="value"><?php echo $fuelKpi; ?></div></div>
            </div>
          </div>
        </article>

        <article class="panel" style="margin-top:12px">
          <div class="inner">
            <div class="section-head"><h3>الأداء والمواصفات</h3></div>
            <div class="specs">
              <div class="spec"><p class="k">المحرك</p><p class="v"><span dir="ltr"><?php echo e($c['engine']); ?></span></p></div>
              <div class="spec"><p class="k">عزم الدوران</p><p class="v"><span dir="ltr"><?php echo e($c['torque_nm']); ?></span> نيوتن.م</p></div>
              <div class="spec"><p class="k">نظام الدفع</p><p class="v"><span dir="ltr"><?php echo e($c['drivetrain']); ?></span></p></div>
              <div class="spec"><p class="k">ناقل الحركة</p><p class="v"><?php echo e($c['transmission']); ?></p></div>
              <div class="spec"><p class="k">المقاعد</p><p class="v"><span dir="ltr"><?php echo e($c['seats']); ?></span></p></div>
              <div class="spec"><p class="k">0–100 كم/س</p><p class="v"><span dir="ltr"><?php echo e($c['zero_to_hundred_sec']); ?></span> ثانية</p></div>
              <div class="spec"><p class="k">السرعة القصوى</p><p class="v"><span dir="ltr"><?php echo e($c['top_speed_kmh']); ?></span> كم/س</p></div>
              <div class="spec"><p class="k">استهلاك الوقود</p><p class="v"><?php echo $fuelSpec; ?></p></div>
            </div>
          </div>
        </article>
      </section>

      <!-- Side -->
      <aside class="side">
        <div class="panel">
          <div class="inner">
            <div class="section-head"><h3>السعر التقريبي</h3></div>
            <div class="price-lg"><span dir="ltr">$<?php echo number_format((int)$c['price_usd']); ?></span></div>
            <div class="small">* السعر تقديري وقد يختلف حسب السوق والتجهيز.</div>
            <div class="side-actions">
              <button id="detailFav" class="btn primary" data-id="<?php echo (int)$id; ?>" data-add="إضافة إلى المفضلة" data-remove="إزالة من المفضلة" aria-pressed="false" onclick="toggleFav(event, <?php echo (int)$id; ?>)">إضافة إلى المفضلة</button>
              <button class="btn ghost" onclick="copyLink()">نسخ رابط الصفحة</button>
            </div>
          </div>
        </div>
      </aside>
    </div>
  </main>

  <?php site_footer('© ' . date('Y') . ' دليل السيارات — صفحة تفاصيل احترافية'); ?>

  <script src="main.js"></script>
  <script>
    function copyLink(){
      navigator.clipboard.writeText(location.href).then(()=>{
        alert('تم نسخ الرابط');
      }, ()=> alert('تعذر النسخ'));
    }
  </script>
</body>
</html>
