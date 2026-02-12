<?php
$now = new \DateTimeImmutable();
$deadline = $businessDay->order_deadline instanceof \DateTimeInterface
  ? \DateTimeImmutable::createFromInterface($businessDay->order_deadline)
  : new \DateTimeImmutable((string)$businessDay->order_deadline);

$isClosed = $deadline < $now;
?>

<div class="card mb-3">
  <div class="card-body">
    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
      <div>
        <div class="text-muted small">営業日</div>
        <div class="fs-4 fw-bold">
          <?= h($businessDay->business_date->format('Y/m/d')) ?>
        </div>
      </div>

      <div class="text-end">
        <div class="text-muted small">予約締切</div>
        <div class="fw-semibold">
          <?= h($deadline->format('Y/m/d H:i')) ?>
        </div>
      </div>
    </div>

    <div class="mt-3">
      <?php if ($isClosed): ?>
        <span class="badge text-bg-secondary">受付終了</span>
      <?php else: ?>
        <span class="badge text-bg-success">受付中</span>
      <?php endif; ?>
    </div>
  </div>
</div>

<h2 class="h5 mb-3">準備用サマリ（予約確定分）</h2>

<?php
$count = (int)($totals->count ?? 0);
$totalPrice = (int)($totals->total_price ?? 0);
$hasRows = !empty($summary->toArray());
?>

<div class="row g-3 mb-3">
  <div class="col-12 col-md-6">
    <div class="card h-100">
      <div class="card-body">
        <div class="text-muted small">予約件数</div>
        <div class="fs-4 fw-bold"><?= number_format($count) ?> 件</div>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-6">
    <div class="card h-100">
      <div class="card-body">
        <div class="text-muted small">合計金額（税込）</div>
        <div class="fs-4 fw-bold"><?= number_format($totalPrice) ?> 円</div>
      </div>
    </div>
  </div>
</div>

<?php if (!$hasRows): ?>
  <div class="alert alert-secondary mb-0">
    予約はまだありません。
  </div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table table-striped table-bordered align-middle">
      <thead class="table-light">
        <tr>
          <th style="min-width: 220px;">商品</th>
          <th style="width: 120px;" class="text-end">単価</th>
          <th style="width: 110px;" class="text-center">合計数量</th>
          <th style="width: 140px;" class="text-end">合計金額</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($summary as $row): ?>
          <tr>
            <td class="fw-semibold"><?= h($row->product_name) ?></td>
            <td class="text-end"><?= number_format((int)$row->unit_price) ?> 円</td>
            <td class="text-center"><?= (int)$row->total_qty ?></td>
            <td class="text-end"><?= number_format((int)$row->total_amount) ?> 円</td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <p class="text-muted small mt-2 mb-0">
    ※「予約確定」ステータスのみ集計しています。
  </p>
<?php endif; ?>
