<h1>営業日：<?= h($businessDay->business_date->format('Y-m-d')) ?></h1>
<p>予約締切：<?= h($businessDay->order_deadline->format('Y-m-d H:i')) ?></p>

<?php if ($isClosed) : ?>
  <p>この営業日は受付終了です。</p>
<?php else: ?>
    <?= $this->Form->create(null, ['url' => ['controller' => 'Reservations', 'action' => 'start', $businessDay->id]]) ?>
    <?= $this->Form->hidden('business_day_id', ['value' => $businessDay->id]) ?>

    <?php
    $grouped = [];
    foreach ($businessDay->products as $p) {
        $g = $p->product_master->genre ?? 'そのほか';
        $grouped[$g][] = $p;
    }
    ?>

    <?php foreach ($grouped as $genre => $products): ?>
      <h3><?= h($genre) ?></h3>

      <?php foreach ($products as $p): ?>
        <div>
          <?= h($p->product_master->name) ?>
          （<?= number_format($p->price) ?>円）
          数量：<input type="number" name="quantity[<?= (int)$p->id ?>]" min="0" value="0">
        </div>
      <?php endforeach; ?>
    <?php endforeach; ?>


  <button type="submit">次へ</button>
    <?= $this->Form->end() ?>
<?php endif; ?>
