<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface[]|\Cake\Collection\CollectionInterface $businessDays
 */

$now = new \DateTimeImmutable();
?>

<h1 class="mb-3">米粉蒸しパンkaneki予約受付</h1>

<div class="table-responsive">
  <table class="table table-striped table-bordered align-middle">
    <thead class="table-light">
      <tr>
        <th style="min-width: 140px;">営業日</th>
        <th style="min-width: 170px;">予約締切時間</th>
        <th style="min-width: 130px;">受付状態</th>
        <th style="min-width: 280px;">メニュー</th>
        <th style="min-width: 100px;">価格（税込）</th>
        <th style="min-width: 140px;">予約へ進む</th>
        
      </tr>
    </thead>

    <tbody>
      <?php foreach ($businessDays as $day): ?>
        <?php
          $deadline = $day->order_deadline instanceof \DateTimeInterface
            ? \DateTimeImmutable::createFromInterface($day->order_deadline)
            : new \DateTimeImmutable((string)$day->order_deadline);

          $isClosed = $deadline < $now;

          // まもなく締切（例：締切まで24時間以内）
          $isSoon = !$isClosed && ($deadline->getTimestamp() - $now->getTimestamp() <= 24 * 60 * 60);

          // 表示用（例：2026/02/10(火)）
          $bizDate = $day->business_date;
          $bizLabel = $bizDate ? $bizDate->format('Y/m/d') . ' (' . ['日','月','火','水','木','金','土'][(int)$bizDate->format('w')] . ')' : '';
        ?>

        <tr class="<?= $isClosed ? 'table-secondary' : '' ?>">
          <td>
            <strong><?= h($bizLabel) ?></strong>
          </td>

          <td>
            <div><?= h($deadline->format('Y/m/d H:i')) ?></div>
            <?php if ($isSoon): ?>
              <div><span class="badge text-bg-warning">まもなく締切</span></div>
            <?php endif; ?>
          </td>

          <td>
            <?php if ($isClosed): ?>
              <span class="badge text-bg-secondary">受付終了</span>
            <?php else: ?>
              <span class="badge text-bg-success">受付中</span>
            <?php endif; ?>
          </td>

          <td class="small">
            <?php if (!empty($day->products)): ?>
              <ul class="list-unstyled mb-0">
                <?php foreach ($day->products as $product): ?>
                  <li class="mb-1">
                    <span class="fw-semibold">
                      <?= h($product->product_master->name ?? '商品名なし') ?>
                    </span>                 
                    
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <span class="text-muted">出品がありません</span>
            <?php endif; ?>
          </td>

          <td class="small">
            <?php if (!empty($day->products)): ?>
              <ul class="list-unstyled mb-0">
                <?php foreach ($day->products as $product): ?>
                  <li class="mb-1"><?= h(number_format((int)$product->price)) ?>円</li>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <span class="text-muted">-</span>
            <?php endif; ?>
          </td>

          <td>
            <?php if ($isClosed): ?>
              <button class="btn btn-sm btn-secondary" disabled>受付終了</button>
            <?php else: ?>
              <a class="btn btn-sm btn-primary"
                 href="<?= $this->Url->build(['action' => 'view', $day->id]) ?>">
                詳細・予約へ
              </a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>