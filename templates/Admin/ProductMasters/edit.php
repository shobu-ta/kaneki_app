<?php
/**
 * @var \App\Model\Entity\ProductMaster $productMaster
 */
?>

<h1 class="h4 mb-3">商品マスタ編集</h1>

<?= $this->Flash->render() ?>

<!-- 編集フォーム -->
<div class="card mb-3">
  <div class="card-body">

    <?= $this->Form->create($productMaster) ?>

    <div class="mb-3">
      <?= $this->Form->control('name', [
        'label' => '商品名（必須）',
        'class' => 'form-control',
        'required' => true,
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
        'label' => '基本価格（円）',
        'type' => 'number',
        'min' => 0,
        'class' => 'form-control',
        'inputmode' => 'numeric',
      ]) ?>
      <div class="form-text">出品時に自動反映されます（出品側で変更可能）。</div>
    </div>

    <div class="form-check mb-3">
      <?= $this->Form->checkbox('is_active', [
        'class' => 'form-check-input',
        'id' => 'is-active',
      ]) ?>
      <label class="form-check-label" for="is-active">有効にする</label>
    </div>

    <div class="d-grid gap-2">
      <?= $this->Form->button('更新する', ['class' => 'btn btn-primary btn-lg']) ?>
      <?= $this->Html->link('一覧へ戻る', ['action' => 'index'], ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?= $this->Form->end() ?>

  </div>
</div>

<!-- 危険操作：削除 -->
<div class="card border-danger">
  <div class="card-header bg-danger text-white fw-bold">
    注意事項
  </div>
  <div class="card-body">
    <p class="mb-3">
      過去の出品に紐づいている場合は削除することができません（削除に対する制御が自動で働きます）。<br>
      削除できない場合は、商品マスタの「有効にする」のチェックを外して無効化してください。
    </p>

    <div class="text-end">
      <?= $this->Form->postLink(
        'この商品を削除する',
        ['action' => 'delete', $productMaster->id],
        [
          'confirm' => 'この商品マスタを削除してもよろしいですか？',
          'class' => 'btn btn-outline-danger'
        ]
      ) ?>
    </div>
  </div>
</div>
