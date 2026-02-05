<p>
  <?= $this->Html->link('＋ Instagram予約を追加', ['action' => 'add']) ?>
</p>


<h1>予約一覧</h1>

<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>ID</th>
            <th>営業日</th>
            <th>受付経路</th>
            <th>ステータス</th>
            <th>氏名</th>
            <th>電話</th>
            <th>メール</th>
            <th>合計</th>
            <th>予約日時</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($reservations as $r): ?>
        <tr>
            <td><?= $r->id ?></td>
            <td>
                <?= $r->business_day ? h($r->business_day->business_date) : '-' ?>
            </td>
            <td><?= h($r->source) ?></td>
            <td><?= h($r->status) ?></td>
            <td><?= h($r->customer_name) ?></td>
            <td><?= h($r->phone) ?></td>
            <td><?= h($r->email ?? '-') ?></td>
            <td><?= number_format($r->total_price) ?> 円</td>
            <td><?= h($r->created) ?></td>
            <td>
                <?= $this->Html->link('詳細', ['action' => 'view', $r->id]) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p>
<?= $this->Html->link(
    'ダッシュボードへ戻る',
    ['controller' => 'Dashboards', 'action' => 'index']
) ?>
</p>
