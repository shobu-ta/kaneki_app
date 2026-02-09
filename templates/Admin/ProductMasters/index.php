<h1>商品マスタ一覧</h1>

<?= $this->Html->link('商品追加', ['action' => 'add']) ?>

<?php
$grouped = [];
foreach ($productMasters as $pm) {
    $g = $pm->genre ?: 'そのほか';
    $grouped[$g][] = $pm;
}
?>

<?php foreach ($grouped as $genre => $items): ?>
  <h2><?= h($genre) ?></h2>

  <table border="1" cellpadding="5">
    <thead>
      <tr>
        <th>ID</th><th>商品名</th><th>基本価格</th><th>状態</th><th>操作</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $pm): ?>
      <tr>
        <td><?= $pm->id ?></td>
        <td><?= h($pm->name) ?></td>
        <td><?= number_format($pm->base_price) ?> 円</td>
        <td><?= $pm->is_active ? '有効' : '無効' ?></td>
        <td><?= $this->Html->link('編集', ['action' => 'edit', $pm->id]) ?>
            <?= $this->Form->postLink(
                '削除',
                ['action' => 'delete', $pm->id],
                ['confirm' => '本当に削除しますか？']
            ) ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endforeach; ?>
