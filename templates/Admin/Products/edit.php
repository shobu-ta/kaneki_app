<h1>出品商品編集</h1>

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
