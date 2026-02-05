<h1>営業日管理</h1>

<p>
    <?= $this->Html->link(
        '＋ 新規営業日追加',
        ['action' => 'add']
    ) ?>
</p>

<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>ID</th>
            <th>営業日</th>
            <th>予約締切</th>
            <th>状態</th>
            <th>操作</th>
            <th>出品管理</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($businessDays as $day) : ?>
        <tr>
            <td><?= $day->id ?></td>
            <td><?= h($day->business_date->i18nFormat('yyyy/MM/dd')) ?></td>
            <td><?= h($day->order_deadline->i18nFormat('yyyy/MM/dd HH:mm')) ?></td>
            <td>
                <?= $day->is_active ? '有効' : '無効' ?>
            </td>
            <td>
                <?= $this->Html->link('編集', ['action' => 'edit', $day->id]) ?>
                
            </td>
            <td><?= $this->Html->link('出品管理',['controller' => 'Products', 'action' => 'index', $day->id])?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
