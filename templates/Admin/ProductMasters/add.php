<?php
/**
 * @var \App\Model\Entity\ProductMaster $productMaster
 */
?>

<h1>商品マスタ追加</h1>

<?= $this->Form->create($productMaster) ?>

<fieldset>
    <legend>商品情報</legend>

    <?= $this->Form->control('name', [
        'label' => '商品名',
        'required' => true,
    ]) ?>

    <?= $this->Form->control('genre', [
    'label' => 'ジャンル',
    'type' => 'select',
    'options' => $genres,
    'empty' => false,
    ]) ?>


    <?= $this->Form->control('base_price', [
        'label' => '基本価格',
        'type' => 'number',
        'required' => true,
        'min' => 0,
    ]) ?>

    <?= $this->Form->control('is_active', [
        'type' => 'checkbox',
        'label' => '有効',
        'default' => true,
    ]) ?>
</fieldset>

<?= $this->Form->button('登録する') ?>

<?= $this->Form->end() ?>

<p>
    <?= $this->Html->link('一覧へ戻る', ['action' => 'index']) ?>
</p>
