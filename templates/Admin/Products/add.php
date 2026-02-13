<h1 class="h4 mb-3">出品商品追加</h1>

<?= $this->Flash->render() ?>

<div class="card">
  <div class="card-body">

    <?= $this->Form->create($product) ?>

    <div class="mb-3">
      <label for="product-master-select" class="form-label">商品（マスタ）</label>
      <select name="product_master_id" id="product-master-select" class="form-select" required>
        <option value="">商品を選択してください</option>
        <?php foreach ($productMasters as $pm) : ?>
          <option
            value="<?= (int)$pm->id ?>"
            data-base-price="<?= (int)$pm->base_price ?>"
          >
            <?= h($pm->name) ?>（基本 <?= number_format((int)$pm->base_price) ?>円）
          </option>
        <?php endforeach; ?>
      </select>
      <div class="form-text">選択すると販売価格に基本価格が自動入力されます。</div>
    </div>

    <div class="row g-3">
      <div class="col-12 col-md-6">
        <?= $this->Form->control('price', [
          'label' => '販売価格（円）',
          'type' => 'number',
          'id' => 'price-input',
          'class' => 'form-control',
          'min' => 0,
          'required' => true,
          'inputmode' => 'numeric',
          'placeholder' => '例）300',
        ]) ?>
      </div>

      <div class="col-12 col-md-6">
        <?= $this->Form->control('max_quantity', [
          'label' => '数量限定（空欄＝無制限）',
          'type' => 'number',
          'class' => 'form-control',
          'required' => false,
          'min' => 0,
          'inputmode' => 'numeric',
          'placeholder' => '例）15',
        ]) ?>
      </div>
    </div>

    <div class="form-check mt-3">
      <?= $this->Form->checkbox('is_active', [
        'class' => 'form-check-input',
        'id' => 'is-active',
        'checked' => true,
      ]) ?>
      <label class="form-check-label" for="is-active">表示する</label>
    </div>

    <div class="d-grid gap-2 mt-4">
      <?= $this->Form->button('登録する', ['class' => 'btn btn-primary btn-lg']) ?>
      <?= $this->Html->link('一覧へ戻る', ['action' => 'index', $businessDayId], ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?= $this->Form->end() ?>

  </div>
</div>

<script>
(function() {
  const select = document.getElementById('product-master-select');
  const priceInput = document.getElementById('price-input');

  if (!select || !priceInput) return;

  select.addEventListener('change', function() {
    const opt = select.options[select.selectedIndex];
    const basePrice = opt ? opt.dataset.basePrice : '';

    if (basePrice) {
      priceInput.value = basePrice;
    }
  });
})();
</script>
