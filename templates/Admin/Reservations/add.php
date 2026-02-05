<h1>Instagram予約 手動追加</h1>

<?= $this->Flash->render() ?>

<?= $this->Form->create($reservation) ?>

<fieldset>
  <legend>営業日</legend>

  <?= $this->Form->control('business_day_id', [
      'label' => '営業日',
      'type' => 'select',
      'options' => $businessDays,
      'empty' => '営業日を選択してください',
      'id' => 'business-day-select',
  ]) ?>
</fieldset>

<fieldset>
  <legend>商品と数量</legend>

  <div id="products-area">
    <p>営業日を選択すると出品商品が表示されます。</p>
  </div>
</fieldset>

<fieldset>
  <legend>お客様情報</legend>

  <?= $this->Form->control('customer_name', ['label' => '氏名（必須）']) ?>
  <?= $this->Form->control('phone', ['label' => '電話番号（必須）']) ?>
  <?= $this->Form->control('email', ['label' => 'メール（任意）', 'required' => false]) ?>
  <?= $this->Form->control('note', ['label' => '備考（任意）', 'type' => 'textarea', 'required' => false]) ?>
</fieldset>

<?= $this->Form->button('登録する') ?>
<?= $this->Form->end() ?>

<p><?= $this->Html->link('予約一覧へ戻る', ['action' => 'index']) ?></p>

<script>
(function() {
  const select = document.getElementById('business-day-select');
  const area = document.getElementById('products-area');
  if (!select || !area) return;

  const endpoint = '<?= $this->Url->build([
    'prefix' => 'Admin',
    'controller' => 'Reservations',
    'action' => 'productsForBusinessDay'
  ]) ?>';

  async function loadProducts(businessDayId) {
    if (!businessDayId) {
      area.innerHTML = '<p>営業日を選択すると出品商品が表示されます。</p>';
      return;
    }

    area.innerHTML = '<p>読み込み中...</p>';

    try {
      const res = await fetch(endpoint + '?business_day_id=' + encodeURIComponent(businessDayId));
      const json = await res.json();
      const products = json.products || [];

      if (!products.length) {
        area.innerHTML = '<p>この営業日に出品商品がありません。</p>';
        return;
      }

      let html = '<table border="1" cellpadding="5">';
      html += '<tr><th>商品</th><th>価格</th><th>数量</th></tr>';

      for (const p of products) {
        const max = (p.max_quantity !== null) ? '（上限 ' + p.max_quantity + '）' : '';
        html += '<tr>';
        html += '<td>' + escapeHtml(p.name) + max + '</td>';
        html += '<td>' + Number(p.price).toLocaleString() + '円</td>';
        html += '<td><input type="number" name="qty[' + p.id + ']" min="0" value="0"></td>';
        html += '</tr>';
      }

      html += '</table>';
      area.innerHTML = html;

    } catch (e) {
      area.innerHTML = '<p>商品一覧の取得に失敗しました。</p>';
    }
  }

  function escapeHtml(str) {
    return String(str)
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }

  select.addEventListener('change', () => loadProducts(select.value));

  // 画面再表示（POST失敗など）時に復元
  if (select.value) loadProducts(select.value);
})();
</script>
