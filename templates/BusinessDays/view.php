<h1>営業日：<?= h($businessDay->business_date->format('Y-m-d')) ?></h1>
<p>予約締切：<?= h($businessDay->order_deadline->format('Y-m-d H:i')) ?></p>

<?php if ($isClosed) : ?>
  <p>この営業日は受付終了です。</p>
<?php else: ?>
    <?= $this->Form->create(null, ['url' => ['controller' => 'Reservations', 'action' => 'start']]) ?>
    <?= $this->Form->hidden('business_day_id', ['value' => $businessDay->id]) ?>

  <table border="1" cellpadding="5">
    <tr>
      <th>商品</th><th>価格</th><th>数量</th>
    </tr>

    <?php foreach ($businessDay->products as $p) : ?>
      <tr>
        <td><?= h($p->product_master->name) ?></td>
        <td><?= number_format($p->price) ?>円</td>
        <td>
          <input type="number" name="qty[<?= (int)$p->id ?>]" min="0" value="0">
        </td>
      </tr>
    <?php endforeach; ?>
  </table>

  <button type="submit">次へ</button>
    <?= $this->Form->end() ?>
<?php endif; ?>
