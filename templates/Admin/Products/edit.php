<h1 class="mb-3">
  営業日<?= h($businessDay->business_date->i18nFormat('yyyy/MM/dd')) ?>の出品商品編集
</h1>

<?php if ($reservedReservationCount > 0): ?>
  <div class="alert alert-warning">
    <div class="fw-bold mb-1">注意：この出品は既に予約に含まれています。</div>

    <div class="small">
      予約件数：<?= (int)$reservedReservationCount ?> 件
      （明細行数：<?= (int)$reservedItemCount ?> 行）
    </div>

    <hr class="my-2">

    <div class="small">
      <span class="fw-bold">重要：</span>
      予約済みの商品は <code>予約時の金額</code> が優先的に使われるため、
      ここで価格を変更しても<strong>予約時の金額</strong>は変わりません。<br>
      そのため、管理上の予約金額と出品価格に齟齬が出る可能性があります。
    </div>
  </div>
<?php endif; ?>

<div class="card">
  <div class="card-body">

    <?= $this->Form->create($product, ['novalidate' => true]) ?>

    <div class="row g-3">

      <!-- 商品マスタ -->
      <div class="col-12 col-md-6">
        <?= $this->Form->control('product_master_id', [
          'label' => '商品（必須）',
          'type' => 'select',
          'options' => $productMasters,
          'class' => 'form-select',
          'required' => true,
        ]) ?>
      </div>

      <!-- 価格 -->
      <div class="col-12 col-md-6">
        <?= $this->Form->control('price', [
          'label' => '販売価格（円）（必須）',
          'type' => 'number',
          'class' => 'form-control',
          'required' => true,
          'min' => 0,
          'step' => 1,
          'inputmode' => 'numeric',
        ]) ?>
      </div>

      <!-- 数量上限 -->
      <div class="col-12 col-md-6">
        <?= $this->Form->control('max_quantity', [
          'label' => '数量限定（空欄＝無制限）',
          'type' => 'number',
          'class' => 'form-control',
          'required' => false,
          'min' => 0,
          'step' => 1,
          'inputmode' => 'numeric',
          'placeholder' => '例）10',
        ]) ?>
        <div class="form-text">未入力の場合は無制限として扱います。</div>
      </div>

      <!-- 表示状態 -->
      <div class="col-12 col-md-6">
        <label class="form-label d-block">表示<strong>（チェックを外して更新すると非表示になります）</strong></label>
        <div class="form-check">
          <?= $this->Form->control('is_active', [
            'type' => 'checkbox',
            'label' => '表示する',
            'class' => 'form-check-input',
            'templates' => [
              'inputContainer' => '{{content}}',
              'checkboxWrapper' => '{{input}} {{label}}',
            ],
          ]) ?>
        </div>
      </div>

    </div>

    <hr class="my-4">

      <div class="d-grid gap-2 d-md-flex justify-content-md-end">
      <?= $this->Html->link(
        '一覧へ戻る',
        ['action' => 'index', $product->business_day_id],
        ['class' => 'btn btn-outline-secondary']
      ) ?>

      <?= $this->Form->button('更新する', ['class' => 'btn btn-primary']) ?>
      </div>
    </div>

    <?= $this->Form->end() ?>

  </div>
</div>
