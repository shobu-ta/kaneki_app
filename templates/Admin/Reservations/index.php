<p>
  <?= $this->Html->link('＋ Instagram予約を追加', ['action' => 'add']) ?>
</p>

<?php
$year = $year ?? null;
$month = $month ?? null;
$businessDayId = $businessDayId ?? null;
$businessDayOptions = $businessDayOptions ?? [];
?>


<?php
$currentYear = (int)date('Y');
$years = [];
for ($y = $currentYear - 1; $y <= $currentYear + 2; $y++) {
    $years[$y] = $y;
}
$months = [];
for ($m = 1; $m <= 12; $m++) {
    $months[$m] = $m;
}
?>

<?= $this->Form->create(null, ['type' => 'get']) ?>
  <?= $this->Form->control('year', [
      'label' => '年',
      'type' => 'select',
      'options' => $years,
      'empty' => '選択',
      'value' => $year ?: null,
  ]) ?>

  <?= $this->Form->control('month', [
      'label' => '月',
      'type' => 'select',
      'options' => $months,
      'empty' => '選択',
      'value' => $month ?: null,
  ]) ?>

  <?= $this->Form->control('business_day_id', [
      'label' => '営業日',
      'type' => 'select',
      'options' => $businessDayOptions,
      'empty' => '（年月で絞った営業日）',
      'value' => $businessDayId ?: null,
  ]) ?>

  <?= $this->Form->button('絞り込む') ?>
  <?= $this->Html->link('リセット', ['action' => 'index'], ['style' => 'margin-left:10px;']) ?>
<?= $this->Form->end() ?>

<h1>予約一覧</h1>

<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>ID</th>
            <th>営業日</th>
            <th>受付経路</th>
            <th>ステータス</th>
            <th>氏名</th>
            <th>電話</th>
            <th>メール</th>
            <th>備考コメント</th>
            <th>合計</th>
            <th>予約日時</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($reservations as $r): ?>
        <tr>
            <td><?= $r->id ?></td>
            <td>
                <?= $r->business_day ? h($r->business_day->business_date) : '-' ?>
            </td>
            <td><?= h($r->source) ?></td>
            <td><?= h($r->status) ?></td>
            <td><?= h($r->customer_name) ?></td>
            <td><?= h($r->phone) ?></td>
            <td><?= h($r->email ?? '-') ?></td>
            <td><?= trim((string)$r->note) !== '' ? '有' : '無' ?></td>
            <td><?= number_format($r->total_price) ?> 円</td>
            <td><?= h($r->created) ?></td>
            <td>
                <?= $this->Html->link('詳細', ['action' => 'view', $r->id]) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div style="margin-top:15px;">
  <?= $this->Paginator->prev('« 前へ') ?>
  <?= $this->Paginator->numbers() ?>
  <?= $this->Paginator->next('次へ »') ?>
</div>

<p>
<?= $this->Html->link(
    'ダッシュボードへ戻る',
    ['controller' => 'Dashboards', 'action' => 'index']
) ?>
</p>
