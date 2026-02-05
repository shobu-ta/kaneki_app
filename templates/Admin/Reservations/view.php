<h1>予約詳細 #<?= $reservation->id ?></h1>

<h2>基本情報</h2>
<ul>
    <li>営業日：<?= $reservation->business_day ? h($reservation->business_day->business_date) : '-' ?></li>
    <li>受付経路：<?= h($reservation->source) ?></li>
    <li>ステータス：<?= h($reservation->status) ?></li>
    <li>予約日時：<?= h($reservation->created) ?></li>
</ul>

<h2>お客様情報</h2>
<ul>
    <li>氏名：<?= h($reservation->customer_name) ?></li>
    <li>電話：<?= h($reservation->phone) ?></li>
    <li>メール：<?= h($reservation->email ?? '-') ?></li>
    <li>備考：<?= nl2br(h($reservation->note ?? '')) ?></li>
</ul>

<h2>商品内訳</h2>
<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>商品名</th>
            <th>単価</th>
            <th>数量</th>
            <th>小計</th>
        </tr>
    </thead>
    <tbody>
        <?php $sum = 0; ?>
        <?php foreach ($reservation->reservation_items as $item): ?>
            <?php $line = $item->price_at_order * $item->quantity; ?>
            <?php $sum += $line; ?>
            <tr>
                <td><?= h($item->product_name_at_order) ?></td>
                <td><?= number_format($item->price_at_order) ?> 円</td>
                <td><?= (int)$item->quantity ?></td>
                <td><?= number_format($line) ?> 円</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p><strong>合計：<?= number_format($reservation->total_price) ?> 円</strong></p>

<?php
$label = ($reservation->status === 'canceled')
    ? 'キャンセルを取り消す（reservedに戻す）'
    : '予約をキャンセルする';
?>

<?= $this->Form->postLink(
    $label,
    ['action' => 'toggleStatus', $reservation->id],
    [
        'confirm' => 'ステータスを変更します。よろしいですか？'
    ]
) ?>


<p>
<?= $this->Html->link('一覧へ戻る', ['action' => 'index']) ?>
</p>
