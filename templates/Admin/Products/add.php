<h1>出品商品追加</h1>

<?= $this->Form->create($product) ?>

<fieldset>
    <legend>出品情報</legend>

    <!-- 商品マスタ選択 -->
    <label for="product-master-select">商品</label>
    <select name="product_master_id" id="product-master-select">
        <option value="">商品を選択してください</option>
        <?php foreach ($productMasters as $pm) : ?>
            <option
                value="<?= (int)$pm->id ?>"
                data-base-price="<?= (int)$pm->base_price ?>"
            >
                <?= h($pm->name) ?>（基本 <?= number_format($pm->base_price) ?>円）
            </option>
        <?php endforeach; ?>
    </select>

    <!-- 価格 -->
    <?= $this->Form->control('price', [
        'label' => '販売価格（円）',
        'type' => 'number',
        'id' => 'price-input',
    ]) ?>

    <!-- 数量上限 -->
    <?= $this->Form->control('max_quantity', [
        'label' => '数量上限（空欄＝無制限）',
        'type' => 'number',
        'required' => false,
    ]) ?>

    <!-- 表示/非表示 -->
    <?= $this->Form->control('is_active', [
        'label' => '表示する',
        'type' => 'checkbox',
        'default' => true,
    ]) ?>

</fieldset>

<?= $this->Form->button('登録する') ?>
<?= $this->Form->end() ?>

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

<p>
<?= $this->Html->link(
    '一覧へ戻る',
    ['action' => 'index', $businessDayId]
) ?>
</p>
