<h1 class="mb-3">商品マスタ一覧</h1>

<p>
  <?= $this->Html->link('＋ 商品追加', ['action' => 'add'], ['class' => 'btn btn-primary']) ?>
</p>

<?php
$grouped = [];
foreach ($productMasters as $pm) {
    $g = $pm->genre ?: 'そのほか';
    $grouped[$g][] = $pm;
}
?>

<?php foreach ($grouped as $genre => $items): ?>
  <h2 class="mt-4 mb-2"><?= h($genre) ?></h2>

  <div class="table-responsive">
    <table class="table table-striped table-bordered align-middle">
      <thead class="table-light">
        <tr>
          <th style="width: 80px;">ID</th>
          <th>商品名</th>
          <th style="width: 140px;">基本価格</th>
          <th style="width: 110px;">状態</th>
          <th style="width: 180px;">操作</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($items as $pm): ?>
          <tr>
            <td><?= (int)$pm->id ?></td>

            <td><?= h($pm->name) ?></td>

            <td><?= number_format((int)$pm->base_price) ?> 円</td>

            <td>
              <?php if ($pm->is_active): ?>
                <span class="badge text-bg-success">有効</span>
              <?php else: ?>
                <span class="badge text-bg-secondary">無効</span>
              <?php endif; ?>
            </td>

            <td class="d-flex gap-2">
              <?= $this->Html->link(
                  '編集',
                  ['action' => 'edit', $pm->id],
                  ['class' => 'btn btn-sm btn-outline-primary']
              ) ?>

              <?= $this->Form->postLink(
                  '削除',
                  ['action' => 'delete', $pm->id],
                  [
                      'confirm' => '本当に削除しますか？',
                      'class' => 'btn btn-sm btn-outline-danger'
                  ]
              ) ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endforeach; ?>
