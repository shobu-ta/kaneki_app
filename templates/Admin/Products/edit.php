<h1>出品商品編集</h1>

<?php if ($reservedReservationCount > 0): ?>
  <div style="border:1px solid #f0ad4e; padding:10px; margin-bottom:15px;">
    <strong>注意：</strong>
    この出品は既に予約に含まれています。<br>
    予約件数：<?= (int)$reservedReservationCount ?> 件
    （明細行数：<?= (int)$reservedItemCount ?> 行）<br>
    <br>
    <strong>重要：</strong>
    予約済みの金額は <code>reservation_items.price_at_order</code> が使われるため、
    ここで価格を変更しても「過去の予約金額」は変わりません。<br>
    ただし、管理上の表示や想定価格と齟齬が出る可能性があります。
  </div>
<?php endif; ?>

<?= $this->Form->create($product) ?>

<fieldset>
    <legend>出品情報</legend>

    <!-- 商品マスタ -->
    <?= $this->Form->control('product_master_id', [
        'label' => '商品',
        'type' => 'select',
        'options' => $productMasters,
    ]) ?>

    <!-- 価格 -->
    <?= $this->Form->control('price', [
        'label' => '販売価格（円）',
        'type' => 'number',
    ]) ?>

    <!-- 数量上限 -->
    <?= $this->Form->control('max_quantity', [
        'label' => '数量上限（空欄＝無制限）',
        'type' => 'number',
        'required' => false,
    ]) ?>

    <!-- 表示状態 -->
    <?= $this->Form->control('is_active', [
        'label' => '表示する',
    ]) ?>

</fieldset>

<?= $this->Form->button('更新する') ?>
<?= $this->Form->end() ?>

<p>
<?= $this->Html->link(
    '一覧へ戻る',
    ['action' => 'index', $product->business_day_id]
) ?>
</p>
