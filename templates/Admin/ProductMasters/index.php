<h1>商品マスタ一覧</h1>

<?= $this->Html->link('商品追加', ['action' => 'add']) ?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>商品名</th>
            <th>価格</th>
            <th>状態</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($productMasters as $productMaster): ?>
        <tr>
            <td><?= h($productMaster->id) ?></td>
            <td><?= h($productMaster->name) ?></td>
            <td><?= h($productMaster->base_price) ?>円</td>
            <td><?= $productMaster->is_active ? '有効' : '無効' ?></td>
            <td>
                <?= $this->Html->link(
                    '編集',
                    ['action' => 'edit', $productMaster->id]
                ) ?>

                <?= $this->Form->postLink(
                    '削除',
                    ['action' => 'delete', $productMaster->id],
                    [
                        'confirm' => '本当に削除しますか？'
                    ]
                ) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

