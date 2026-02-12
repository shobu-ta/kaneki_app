<h1 class="h4 mb-3">Instagram予約 手動追加</h1>

<?= $this->Flash->render() ?>

<div class="card">
  <div class="card-body">

    <?= $this->Form->create($reservation) ?>

    <!-- 営業日 -->
    <div class="mb-4">
      <h2 class="h6 mb-2">営業日</h2>

      <?= $this->Form->control('business_day_id', [
        'label' => '営業日（必須）',
        'type' => 'select',
        'options' => $businessDays,
        'empty' => '営業日を選択してください',
        'id' => 'business-day-select',
        'class' => 'form-select',
        'required' => true,
      ]) ?>
      <div class="form-text">営業日を選ぶと、その日の出品商品が下に表示されます。</div>
    </div>

    <!-- 商品と数量 -->
    <div class="mb-4">
      <h2 class="h6 mb-2">商品と数量</h2>

      <div id="products-area" class="border rounded p-3 bg-light">
        <p class="text-muted mb-0">営業日を選択すると出品商品が表示されます。</p>
      </div>
      <div class="form-text mt-2">数量が0のものは登録されない想定です（コントローラ側で0行は除外）。</div>
    </div>

    <!-- お客様情報 -->
    <div class="mb-4">
      <h2 class="h6 mb-2">お客様情報</h2>

      <div class="row g-3">
        <div class="col-12 col-md-6">
          <?= $this->Form->control('customer_name', [
            'label' => '氏名（必須）',
            'class' => 'form-control',
            'required' => true,
            'autocomplete' => 'name',
            'placeholder' => '例）山田 太郎',
          ]) ?>
        </div>

        <div class="col-12 col-md-6">
          <?= $this->Form->control('phone', [
            'label' => '電話番号（必須）',
            'class' => 'form-control',
            'required' => true,
            'type' => 'tel',
            'inputmode' => 'tel',
            'autocomplete' => 'tel',
            'placeholder' => '例）09012345678',
          ]) ?>
        </div>

        <div class="col-12 col-md-6">
          <?= $this->Form->control('email', [
            'label' => 'メール（任意）',
            'class' => 'form-control',
            'required' => false,
            'type' => 'email',
            'inputmode' => 'email',
            'autocomplete' => 'email',
            'placeholder' => '例）example@example.com',
          ]) ?>
        </div>

        <div class="col-12">
          <?= $this->Form->control('note', [
            'label' => '備考（任意）',
            'type' => 'textarea',
            'class' => 'form-control',
            'required' => false,
            'rows' => 3,
            'placeholder' => '受け取り希望時間など',
          ]) ?>
        </div>
      </div>
    </div>

    <div class="d-grid gap-2">
      <?= $this->Form->button('登録する', ['class' => 'btn btn-primary btn-lg']) ?>
      <?= $this->Html->link('予約一覧へ戻る', ['action' => 'index'], ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?= $this->Form->end() ?>

  </div>
</div>

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
      area.innerHTML = '<p class="text-muted mb-0">営業日を選択すると出品商品が表示されます。</p>';
      return;
    }

    area.innerHTML = '<div class="text-muted">読み込み中...</div>';

    try {
      const res = await fetch(endpoint + '?business_day_id=' + encodeURIComponent(businessDayId), {
        headers: { 'Accept': 'application/json' }
      });

      if (!res.ok) {
        throw new Error('HTTP ' + res.status);
      }

      const json = await res.json();
      const products = json.products || [];

      if (!products.length) {
        area.innerHTML = '<div class="text-muted">この営業日に出品商品がありません。</div>';
        return;
      }

      let html = '';
      html += '<div class="table-responsive">';
      html += '<table class="table table-striped table-bordered align-middle mb-0">';
      html += '<thead class="table-light"><tr><th>商品</th><th style="width:120px;">価格</th><th style="width:180px;">数量</th></tr></thead><tbody>';

      for (const p of products) {
        const maxLabel = (p.max_quantity !== null) ? ('<span class="text-muted small ms-1">(上限 ' + Number(p.max_quantity) + ')</span>') : '';
        const maxAttr = (p.max_quantity !== null) ? (' max="' + Number(p.max_quantity) + '"') : '';

        html += '<tr>';
        html += '<td><span class="fw-semibold">' + escapeHtml(p.name) + '</span>' + maxLabel + '</td>';
        html += '<td>' + Number(p.price).toLocaleString() + '円</td>';
        html += '<td>';
        html +=   '<div class="input-group" style="max-width: 220px;">';
        html +=     '<span class="input-group-text">数量</span>';
        html +=     '<input class="form-control text-center" type="number" name="qty[' + Number(p.id) + ']" min="0"' + maxAttr + ' value="0" inputmode="numeric">';
        html +=   '</div>';
        html += '</td>';
        html += '</tr>';
      }

      html += '</tbody></table></div>';

      area.innerHTML = html;

    } catch (e) {
      area.innerHTML = '<div class="alert alert-warning mb-0">商品一覧の取得に失敗しました。</div>';
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
