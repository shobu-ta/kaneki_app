<?php
/**
 * @var \App\Model\Entity\Reservation $reservation
 */
$status = (string)$reservation->status;
$isCanceled = ($status === 'canceled');

$statusLabel = $isCanceled ? 'キャンセル' : '予約確定';
$statusBadge = $isCanceled ? 'text-bg-secondary' : 'text-bg-success';

$sourceLabel = match ((string)$reservation->source) {
    'web' => 'Web',
    'instagram' => 'Instagram',
    default => h((string)$reservation->source),
};

$businessDate = $reservation->business_day
    ? $reservation->business_day->business_date->i18nFormat('yyyy/MM/dd')
    : '-';
?>
<h1 class="h4 mb-3">予約詳細 #<?= (int)$reservation->id ?></h1>

<?= $this->Flash->render() ?>

<div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
  <div class="d-flex align-items-center gap-2">
    <span class="badge <?= $statusBadge ?>"><?= h($statusLabel) ?></span>
    <span class="text-muted small">受付経路：<?= $sourceLabel ?></span>
  </div>

  <div class="d-flex gap-2">
    <?= $this->Html->link('予約一覧に戻る', ['action' => 'index'], ['class' => 'btn btn-outline-secondary']) ?>
  </div>
</div>

<div class="row g-3">
  <!-- 左：予約情報 -->
  <div class="col-12 col-lg-5">
    <div class="card">
      <div class="card-header bg-white">
        <div class="fw-semibold">予約情報</div>
      </div>
      <div class="card-body">
        <dl class="row mb-0">
          <dt class="col-4 text-muted">ID</dt>
          <dd class="col-8"><?= (int)$reservation->id ?></dd>

          <dt class="col-4 text-muted">営業日</dt>
          <dd class="col-8"><?= h($businessDate) ?></dd>

          <dt class="col-4 text-muted">ステータス</dt>
          <dd class="col-8">
            <span class="badge <?= $statusBadge ?>"><?= h($statusLabel) ?></span>
          </dd>

          <dt class="col-4 text-muted">氏名</dt>
          <dd class="col-8"><?= h((string)$reservation->customer_name) ?></dd>

          <dt class="col-4 text-muted">電話</dt>
          <dd class="col-8"><span class="font-monospace"><?= h((string)$reservation->phone) ?></span></dd>

          <dt class="col-4 text-muted">メール</dt>
          <dd class="col-8"><?= h((string)($reservation->email ?? '-')) ?></dd>

          <dt class="col-4 text-muted">備考</dt>
          <dd class="col-8">
            <?php if (trim((string)$reservation->note) !== ''): ?>
              <div class="border rounded p-2 bg-light">
                <?= nl2br(h((string)$reservation->note)) ?>
              </div>
            <?php else: ?>
              -
            <?php endif; ?>
          </dd>

          <dt class="col-4 text-muted">合計</dt>
          <dd class="col-8">
            <span class="fw-semibold"><?= number_format((int)$reservation->total_price) ?> 円</span>
          </dd>

          <dt class="col-4 text-muted">予約日時</dt>
          <dd class="col-8"><?= h((string)$reservation->created) ?></dd>
        </dl>
      </div>
      <div class="card-footer bg-white d-flex flex-wrap gap-2 justify-content-end">
        <?= $this->Form->postLink(
          $isCanceled ? '予約を復活' : 'キャンセルに変更',
          ['action' => 'toggleStatus', $reservation->id],
          [
            'confirm' => 'ステータスを変更します。よろしいですか？',
            'class' => $isCanceled ? 'btn btn-outline-success' : 'btn btn-outline-danger',
          ]
        ) ?>
      </div>
    </div>
  </div>

  <!-- 右：明細編集 -->
  <div class="col-12 col-lg-7">
    <div class="card">
      <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="fw-semibold">商品内訳</div>
        <div class="text-muted small">数量変更・削除ができます</div>
      </div>

      <?= $this->Form->create(null, [
        'url' => ['action' => 'updateItems', $reservation->id],
      ]) ?>

      <div class="card-body">
        <?php if (empty($reservation->reservation_items)): ?>
          <div class="text-muted">明細がありません。</div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>商品名</th>
                  <th style="width:120px;">単価</th>
                  <th style="width:170px;">数量</th>
                  <th style="width:120px;">小計</th>
                  <th style="width:90px;" class="text-center">削除</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($reservation->reservation_items as $item): ?>
                  <tr>
                    <td>
                      <div class="fw-semibold"><?= h((string)$item->product_name_at_order) ?></div>
                      <div class="text-muted small">明細ID: <?= (int)$item->id ?></div>
                    </td>

                    <td class="text-end"><?= number_format((int)$item->price_at_order) ?> 円</td>

                    <td>
                      <div class="input-group" style="max-width: 220px;">
                        <span class="input-group-text">数量</span>
                        <input
                          class="form-control text-center"
                          type="number"
                          name="qty[<?= (int)$item->id ?>]"
                          min="0"
                          value="<?= (int)$item->quantity ?>"
                          inputmode="numeric"
                        >
                      </div>
                      <div class="form-text">0にすると削除扱い</div>
                    </td>

                    <td class="text-end">
                      <?= number_format((int)$item->price_at_order * (int)$item->quantity) ?> 円
                    </td>

                    <td class="text-center">
                      <input
                        class="form-check-input"
                        type="checkbox"
                        name="delete[<?= (int)$item->id ?>]"
                        value="1"
                        aria-label="削除"
                      >
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">現在の合計</div>
            <div class="fs-5 fw-semibold"><?= number_format((int)$reservation->total_price) ?> 円</div>
          </div>
        <?php endif; ?>
      </div>

      <div class="card-footer bg-white d-flex flex-wrap gap-2 justify-content-end">
        <?= $this->Form->button('明細を更新する', [
          'class' => 'btn btn-primary',
          'confirm' => '数量変更／削除を反映します。よろしいですか？',
        ]) ?>
      </div>

      <?= $this->Form->end() ?>
    </div>
  </div>
</div>
