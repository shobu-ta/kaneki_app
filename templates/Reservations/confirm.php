<h1 class="h4 mb-3">ご予約内容の確認</h1>

<!-- 商品一覧 -->
<div class="card mb-3">
  <div class="card-header fw-bold">
    ご注文商品
  </div>

  <div class="card-body">

    <div class="table-responsive">
      <table class="table table-striped table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>商品</th>
            <th style="width:120px;">単価</th>
            <th style="width:90px;">数量</th>
            <th style="width:130px;">小計</th>
          </tr>
        </thead>

        <tbody>
          <?php foreach ($lines as $l): ?>
            <tr>
              <td><?= h($l['name']) ?></td>
              <td><?= number_format($l['price']) ?>円</td>
              <td><?= (int)$l['quantity'] ?></td>
              <td><?= number_format($l['line_total']) ?>円</td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="text-end mt-3">
      <h5 class="mb-0">
        合計金額（税込）：
        <span class="fw-bold text-dark">
          <?= number_format($total) ?>円
        </span>
      </h5>
    </div>

  </div>
</div>

<!-- お客様情報 -->
<div class="card mb-3">
  <div class="card-header fw-bold">
    お客様情報
  </div>

  <div class="card-body">
    <dl class="row mb-0">
      <dt class="col-sm-3">氏名</dt>
      <dd class="col-sm-9"><?= h($customer['customer_name']) ?></dd>

      <dt class="col-sm-3">電話番号</dt>
      <dd class="col-sm-9"><?= h($customer['phone']) ?></dd>

      <dt class="col-sm-3">メール</dt>
      <dd class="col-sm-9"><?= h($customer['email']) ?></dd>

      <?php if (!empty($customer['note'])): ?>
        <dt class="col-sm-3">備考</dt>
        <dd class="col-sm-9"><?= nl2br(h($customer['note'])) ?></dd>
      <?php endif; ?>
    </dl>
  </div>
</div>

<!-- 操作ボタン -->
<div class="d-grid gap-2">
  <?= $this->Form->create(null, ['url' => ['action' => 'complete']]) ?>
  <?= $this->Form->button('予約を確定する', [
      'class' => 'btn btn-primary btn-lg'
  ]) ?>
  <?= $this->Form->end() ?>

  <?= $this->Html->link(
      '入力内容を修正する',
      ['action' => 'add'],
      ['class' => 'btn btn-outline-secondary']
  ) ?>
</div>

<p class="text-muted small mt-3">
  「予約を確定する」を押すと予約が完了します。
</p>
