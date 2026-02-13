<?php
$now = new \DateTimeImmutable();
$deadline = $businessDay->order_deadline instanceof \DateTimeInterface
    ? \DateTimeImmutable::createFromInterface($businessDay->order_deadline)
    : new \DateTimeImmutable((string)$businessDay->order_deadline);

$isClosed = $deadline < $now;

// ジャンルでグルーピング
$grouped = [];
foreach ($businessDay->products as $p) {
    $g = $p->product_master->genre ?? 'そのほか';
    $grouped[$g][] = $p;
}
?>

<div class="mb-3">
  <h1 class="h4 mb-2">
    営業日：<?= h($businessDay->business_date->format('Y/m/d')) ?>
  </h1>

  <div class="card">
    <div class="card-body">
      <div class="d-flex flex-wrap gap-2 align-items-center">
        <div>
          <span class="text-muted">予約締切：</span>
          <strong><?= h($deadline->format('Y/m/d H:i')) ?></strong>
        </div>

        <?php if ($isClosed): ?>
          <span class="badge text-bg-secondary">受付終了</span>
        <?php else: ?>
          <span class="badge text-bg-success">受付中</span>
        <?php endif; ?>
      </div>

      <?php if ($isClosed): ?>
        <div class="alert alert-secondary mt-3 mb-0">
          この営業日は受付終了です。
        </div>
      <?php else: ?>
        <div class="text-muted small mt-2">
          数量を入力して「次へ」を押してください。
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php if (!$isClosed): ?>
  <?= $this->Form->create(null, ['url' => ['controller' => 'Reservations', 'action' => 'start', $businessDay->id]]) ?>
  <?= $this->Form->hidden('business_day_id', ['value' => $businessDay->id]) ?>

  <?php foreach ($grouped as $genre => $products): ?>
    <div class="card mb-3">
      <div class="card-header">
        <strong><?= h($genre) ?></strong>
      </div>

      <div class="card-body">
        <div class="d-grid gap-2">
          <?php foreach ($products as $p): ?>
            <div class="border rounded p-2">
              <div class="row g-2 align-items-center">
                <div class="col-12 col-md-8">
                  <div class="fw-semibold">
                    <?= h($p->product_master->name) ?>
                  </div>
                  <div class="text-muted small">
                    <?= number_format((int)$p->price) ?>円
                    <?php if ($p->max_quantity !== null): ?>
                      ・限定 <?= (int)$p->max_quantity ?>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="col-12 col-md-4">
                    <?php
                      $max = $p->max_quantity !== null ? (int)$p->max_quantity : 15; // 上限が無い商品は15など固定
                      $max = min($max, 30); // 例：安全のため最大30までに制限（運用に合わせて調整）
                    ?>

                  <label class="form-label small mb-1">数量</label>
                  <select class="form-select" name="quantity[<?= (int)$p->id ?>]" style="max-width: 180px;">
                    <?php for ($i = 0; $i <= $max; $i++) : ?>
                      <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                  </select>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>

  <div class="d-grid">
    <button type="submit" class="btn btn-primary btn-lg">
      次へ
    </button>
  </div>

  <?= $this->Form->end() ?>
<?php endif; ?>
