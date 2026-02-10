<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface[]|\Cake\Collection\CollectionInterface $businessDays
 */
?>
<h1>営業日一覧</h1>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>営業日</th>
            <th>予約締切</th>
            <th>受付状態</th>
            <th>メニュー一覧</th>
            <th>詳細ページ</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($businessDays as $day) : ?>
            <?php
            // 受付状態判定
            $now = date('Y-m-d H:i:s');
            $status = $day->order_deadline < $now ? '受付終了' : '受付中';
            ?>
            <tr>
                <td><?= h($day->business_date->format('Y-m-d')) ?></td>
                <td><?= h($day->order_deadline->format('Y-m-d H:i')) ?></td>
                <td><?= h($status) ?></td>
                <td>
                    <ul>
                        <?php foreach ($day->products as $product) : ?>
                            <li>
                            <?= h($product->product_master->name ?? '商品名なし') ?>
                            - <?= h(number_format((int)$product->price)) ?>円
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </td>
                <td>
                    <a href="<?= $this->Url->build(['action' => 'view', $day->id]) ?>">詳細・予約へ</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
