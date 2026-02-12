<?php
/**
 * @var \App\Model\Entity\ProductMaster $productMaster
 */
?>

<h1 class="h4 mb-3">商品マスタ追加</h1>

<?= $this->Flash->render() ?>

<div class="card">
  <div class="card-body">

    <?= $this->Form->create($productMaster) ?>

    <div class="mb-3">
      <?= $this->Form->control('name', [
        'label' => '商品名（必須）',
        'required' => true,
        'class' => 'form-control',
        'placeholder' => '例）豆乳蒸しパン',
        'autocomplete' => 'off',
      ]) ?>
    </div>

    <div class="mb-3">
      <?= $this->Form->control('genre', [
        'label' => 'ジャンル（必須）',
        'type' => 'select',
        'options' => $genres,
        'empty' => false,
        'class' => 'form-select',
        'required' => true,
      ]) ?>
    </div>

    <div class="mb-3">
      <?= $this->Form->control('base_price', [
        'label' => '基本価格（円）（必須）',
        'type' => 'number',
        'required' => true,
        'min' => 0,
        'class' => 'form-control',
        'inputmode' => 'numeric',
        'placeholder' => '例）300',
      ]) ?>
      <div class="form-text">出品時にこの価格が自動反映されます（出品画面でも変更できます）。</div>
    </div>

    <div class="form-check mb-3">
      <?= $this->Form->checkbox('is_active', [
        'class' => 'form-check-input',
        'id' => 'is-active',
        'checked' => true,
      ]) ?>
      <label class="form-check-label" for="is-active">有効にする</label>
    </div>

    <div class="d-grid gap-2 mt-4">
      <?= $this->Form->button('登録する', ['class' => 'btn btn-primary btn-lg']) ?>
      <?= $this->Html->link('一覧へ戻る', ['action' => 'index'], ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?= $this->Form->end() ?>

  </div>
</div>
