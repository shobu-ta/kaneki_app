<h1 class="mb-3">
  営業日<?= h($businessDay->business_date->i18nFormat('yyyy/MM/dd')) ?>の出品管理
</h1>


<!-- 上部：追加ボタンだけ -->
<div class="mb-3">
  <?= $this->Html->link(
    '＋ 出品商品追加',
    ['action' => 'add', $businessDayId],
    ['class' => 'btn btn-primary']
  ) ?>
</div>

<!-- 中央：表 -->
<div class="table-responsive">
  <table class="table table-striped table-bordered align-middle">
    <thead class="table-light">
      <tr>
        <th style="width: 80px;">ID</th>
        <th style="min-width: 220px;">商品名</th>
        <th style="width: 140px;">価格</th>
        <th style="width: 140px;">数量上限</th>
        <th style="width: 110px;">状態</th>
        <th style="width: 210px;">操作</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach ($products as $product) : ?>
        <tr class="<?= $product->is_active ? '' : 'table-secondary' ?>">
          <td><?= (int)$product->id ?></td>

          <td>
            <div class="fw-semibold"><?= h($product->product_master->name) ?></div>
          </td>

          <td><?= number_format((int)$product->price) ?> 円</td>

          <td>
            <?= $product->max_quantity !== null ? (int)$product->max_quantity : '無制限' ?>
          </td>

          <td>
            <?php if ($product->is_active): ?>
              <span class="badge text-bg-success">表示</span>
            <?php else: ?>
              <span class="badge text-bg-secondary">非表示</span>
            <?php endif; ?>
          </td>

          <td>
            <div class="d-flex flex-wrap gap-2">
              <?= $this->Html->link(
                 '編集',
                ['action' => 'edit', $product->id],
                ['class' => 'btn btn-sm btn-outline-primary']
              ) ?>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- 下部：戻る -->
<div class="mt-3">
  <?= $this->Html->link(
    '← 営業日一覧へ戻る',
    ['controller' => 'BusinessDays', 'action' => 'index'],
    ['class' => 'btn btn-outline-secondary']
  ) ?>
</div>
