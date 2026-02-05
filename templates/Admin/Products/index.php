<h1>出品管理</h1>

<p>
<?= $this->Html->link(
    '＋ 出品商品追加',
    ['action' => 'add', $businessDayId]
) ?>
</p>

<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>ID</th>
            <th>商品名</th>
            <th>価格</th>
            <th>数量上限</th>
            <th>状態</th>
            <th>操作</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($products as $product) : ?>
        <tr>
            <td><?= $product->id ?></td>

            <td>
                <?= h($product->product_master->name) ?>
            </td>

            <td>
                <?= number_format($product->price) ?> 円
            </td>

            <td>
                <?= $product->max_quantity ?? '無制限' ?>
            </td>

            <td>
                <?= $product->is_active ? '表示' : '非表示' ?>
            </td>

            <td>
                <?= $this->Html->link(
                    '編集',
                    ['action' => 'edit', $product->id]
                ) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p>
<?= $this->Html->link(
    '営業日一覧へ戻る',
    ['controller' => 'BusinessDays', 'action' => 'index']
) ?>
</p>
