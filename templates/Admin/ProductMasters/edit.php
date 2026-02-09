<?php
/**
 * @var \App\Model\Entity\ProductMaster $productMaster
 */
?>

<h1>商品マスタ編集</h1>

<?= $this->Form->create($productMaster) ?>

<fieldset>
    <legend>商品マスタ編集</legend>

    <?= $this->Form->control('name', ['label' => '商品名']) ?>
    <?= $this->Form->control('genre', [
    'label' => 'ジャンル',
    'type' => 'select',
    'options' => $genres,
    'empty' => false,
    ]) ?>
    <?= $this->Form->control('base_price', [
        'label' => '基本価格',
        'type' => 'number',
        'min' => 0,
    ]) ?>
    <?= $this->Form->control('is_active', [
        'type' => 'checkbox',
        'label' => '有効'
    ]) ?>
</fieldset>

<?= $this->Form->button('更新する') ?>
<?= $this->Form->end() ?>

<hr>

<?= $this->Form->postLink(
    '🗑 この商品を削除',
    ['action' => 'delete', $productMaster->id],
    [
        'confirm' => 'この商品マスタを削除してもよろしいですか？',
        'class' => 'danger'
    ]
) ?>

<p>
    <?= $this->Html->link('一覧へ戻る', ['action' => 'index']) ?>
</p>

