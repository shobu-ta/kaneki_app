<?php
/**
 * @var \App\Model\Entity\BusinessDay $businessDay
 */
?>

<h1>営業日：<?= h($businessDay->business_date->format('Y-m-d')) ?></h1>

<p>
    予約締切：
    <?= h($businessDay->order_deadline->format('Y-m-d H:i')) ?>
</p>

<h2>商品選択</h2>

<form>
    <table border="1" cellpadding="5">
        <tr>
            <th>商品名</th>
            <th>価格</th>
            <th>数量</th>
        </tr>

        <?php foreach ($businessDay->products as $product) : ?>
            <tr>
                <td><?= h($product->name) ?></td>
                <td><?= h(number_format($product->price)) ?>円</td>
                <td>
                    <input
                        type="number"
                        name="quantity[<?= $product->id ?>]"
                        min="0"
                        value="0"
                    >
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <button type="submit">次へ</button>
</form>
