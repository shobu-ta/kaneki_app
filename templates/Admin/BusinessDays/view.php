<h2>準備用サマリ（予約確定分）</h2>

<p>
  予約件数：<?= (int)($totals->count ?? 0) ?> 件 /
  合計金額：<?= number_format((int)($totals->total_price ?? 0)) ?> 円
</p>

<?php if (empty($summary->toArray())): ?>
  <p>予約はまだありません。</p>
<?php else: ?>
  <table border="1" cellpadding="5">
    <thead>
      <tr>
        <th>商品</th>
        <th>単価</th>
        <th>合計数量</th>
        <th>合計金額</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($summary as $row): ?>
        <tr>
          <td><?= h($row->product_name) ?></td>
          <td><?= number_format((int)$row->unit_price) ?> 円</td>
          <td><?= (int)$row->total_qty ?></td>
          <td><?= number_format((int)$row->total_amount) ?> 円</td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
