<?php
$year = $year ?? null;
$month = $month ?? null;
$businessDayId = $businessDayId ?? null;
$businessDayOptions = $businessDayOptions ?? [];

$currentYear = (int)date('Y');
$years = [];
for ($y = $currentYear - 1; $y <= $currentYear + 2; $y++) {
    $years[$y] = $y;
}
$months = [];
for ($m = 1; $m <= 12; $m++) {
    $months[$m] = $m;
}

// バッジ用
$statusBadge = function ($status) {
    $status = (string)$status;
    switch ($status) {
        case 'reserved':
            return 'text-bg-success';
        case 'canceled':
            return 'text-bg-secondary';
        default:
            return 'text-bg-info';
    }
};

$statusLabel = function ($status) {
    $status = (string)$status;
    switch ($status) {
        case 'reserved':
            return '予約完了';
        case 'canceled':
            return 'キャンセル';
        default:
            return $status;
    }
};

$sourceBadge = function ($source) {
    $source = strtolower((string)$source);
    switch ($source) {
        case 'instagram':
            return 'badge-instagram';
        case 'web':
            return 'text-bg-primary';
        default:
            return 'text-bg-dark';
    }
};
?>

<div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3">
  <h1 class="h4 mb-0">予約一覧</h1>

  <div class="d-flex gap-2">
    <?= $this->Html->link(
      '＋ Instagram予約を追加',
      ['action' => 'add'],
      ['class' => 'btn btn-primary']
    ) ?>
    <?= $this->Html->link(
      'ダッシュボードへ',
      ['controller' => 'Dashboards', 'action' => 'index'],
      ['class' => 'btn btn-outline-secondary']
    ) ?>
  </div>
</div>

<!-- 絞り込み -->
<div class="card mb-3">
  <div class="card-body">
    <?= $this->Form->create(null, ['type' => 'get']) ?>

    <div class="row g-3 align-items-end">
      <div class="col-6 col-md-2">
        <?= $this->Form->control('year', [
          'label' => '年',
          'type' => 'select',
          'options' => $years,
          'empty' => '選択',
          'value' => $year ?: null,
          'class' => 'form-select',
        ]) ?>
      </div>

      <div class="col-6 col-md-2">
        <?= $this->Form->control('month', [
          'label' => '月',
          'type' => 'select',
          'options' => $months,
          'empty' => '選択',
          'value' => $month ?: null,
          'class' => 'form-select',
        ]) ?>
      </div>

      <div class="col-12 col-md-5">
        <?= $this->Form->control('business_day_id', [
          'label' => '営業日',
          'type' => 'select',
          'options' => $businessDayOptions,
          'empty' => '（年月で絞った営業日）',
          'value' => $businessDayId ?: null,
          'class' => 'form-select',
        ]) ?>
      </div>

      <div class="col-12 col-md-3 d-grid d-md-flex gap-2">
        <?= $this->Form->button('絞り込む', ['class' => 'btn btn-success']) ?>
        <?= $this->Html->link('リセット', ['action' => 'index'], ['class' => 'btn btn-outline-secondary']) ?>
      </div>
    </div>

    <?= $this->Form->end() ?>
  </div>
</div>

<!-- 一覧テーブル -->
<div class="table-responsive">
  <table class="table table-striped table-bordered align-middle">
    <thead class="table-light">
      <tr>
        <th style="width:70px;">ID</th>
        <th style="min-width:110px;">営業日</th>
        <th style="width:110px;">受付経路</th>
        <th style="width:120px;">ステータス</th>
        <th style="min-width:130px;">氏名</th>
        <th style="min-width:130px;">電話</th>
        <th style="min-width:180px;">メール</th>
        <th style="width:90px;">備考</th>
        <th style="width:120px;">合計</th>
        <th style="min-width:160px;">予約日時</th>
        <th style="width:90px;">操作</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach ($reservations as $r): ?>
        <?php
          $hasNote = trim((string)$r->note) !== '';
        ?>
        <tr>
          <td><?= (int)$r->id ?></td>

          <td>
            <?= $r->business_day ? h($r->business_day->business_date->format('Y/m/d')) : '-' ?>
          </td>

          <td>
            <span class="badge <?= h($sourceBadge($r->source)) ?>">
              <?= h($r->source) ?>
            </span>
          </td>

          <td>
            <span class="badge <?= h($statusBadge($r->status)) ?>">
              <?= h($statusLabel($r->status)) ?>
            </span>
          </td>

          <td><?= h($r->customer_name) ?></td>
          <td><?= h($r->phone) ?></td>
          <td class="small"><?= h($r->email ?? '-') ?></td>

          <td>
            <?php if ($hasNote): ?>
              <span class="badge text-bg-danger">有</span>
            <?php else: ?>
              <span class="badge text-bg-secondary">無</span>
            <?php endif; ?>
          </td>

          <td><?= number_format((int)$r->total_price) ?> 円</td>

          <td class="small">
            <?= $r->created ? h($r->created->format('Y/m/d H:i')) : '-' ?>
          </td>

          <td>
            <?= $this->Html->link('詳細', ['action' => 'view', $r->id], ['class' => 'btn btn-sm btn-outline-primary']) ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- ページネーション -->
<nav class="mt-3">
  <ul class="pagination flex-wrap">
    <?= $this->Paginator->prev('« 前へ', ['class' => 'page-item'], ['class' => 'page-item disabled']) ?>
    <?= $this->Paginator->numbers(['class' => 'page-item', 'currentClass' => 'active']) ?>
    <?= $this->Paginator->next('次へ »', ['class' => 'page-item'], ['class' => 'page-item disabled']) ?>
  </ul>
</nav>
