<h2>商品内訳（数量変更・削除）</h2>

<?= $this->Form->create(null, [
    'url' => ['action' => 'updateItems', $reservation->id]
]) ?>

<table border="1" cellpadding="5">
  <thead>
    <tr>
      <th>商品名</th>
      <th>単価</th>
      <th>数量</th>
      <th>小計</th>
      <th>削除</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($reservation->reservation_items as $item): ?>
      <tr>
        <td><?= h($item->product_name_at_order) ?></td>
        <td><?= number_format($item->price_at_order) ?> 円</td>
        <td>
          <input type="number"
                 name="qty[<?= (int)$item->id ?>]"
                 min="0"
                 value="<?= (int)$item->quantity ?>">
        </td>
        <td><?= number_format($item->price_at_order * $item->quantity) ?> 円</td>
        <td style="text-align:center;">
          <input type="checkbox" name="delete[<?= (int)$item->id ?>]" value="1">
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
合計：<?= number_format($reservation->total_price) ?> 円
<p>
  <?= $this->Form->button('明細を更新する', [
      'confirm' => '数量変更／削除を反映します。よろしいですか？',
  ]) ?>
</p>

<?= $this->Form->end() ?>
<p>
  <?= $this->Html->link('予約一覧に戻る', [
      'action' => 'index',
  ]) ?>