<h1>確認</h1>

<h2>商品</h2>
<table border="1" cellpadding="5">
  <tr><th>商品</th><th>単価</th><th>数量</th><th>小計</th></tr>
  <?php foreach ($lines as $l): ?>
    <tr>
      <td><?= h($l['name']) ?></td>
      <td><?= number_format($l['price']) ?>円</td>
      <td><?= (int)$l['quantity'] ?></td>
      <td><?= number_format($l['line_total']) ?>円</td>
    </tr>
  <?php endforeach; ?>
</table>

<p><strong>合計：<?= number_format($total) ?>円</strong></p>

<h2>お客様情報</h2>
<ul>
  <li>氏名：<?= h($customer['customer_name']) ?></li>
  <li>電話：<?= h($customer['phone']) ?></li>
  <li>メール：<?= h($customer['email']) ?></li>
  <li>備考：<?= h($customer['note'] ?? '') ?></li>
</ul>

<?= $this->Form->create(null, ['url' => ['action' => 'complete']]) ?>
<?= $this->Form->button('予約確定') ?>
<?= $this->Form->end() ?>

<p><?= $this->Html->link('戻る', ['action' => 'add']) ?></p>
