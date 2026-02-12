<h1 class="mb-3">営業日・出品管理</h1>

<?php $this->Paginator->setTemplates([]); ?>

<div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
  <div>
    <?= $this->Html->link(
      '＋ 新規営業日追加',
      ['action' => 'add'],
      ['class' => 'btn btn-primary']
    ) ?>
  </div>

  <div class="text-muted small">
    表示件数：<?= (int)$this->Paginator->counter('{{count}}') ?>件
  </div>
</div>

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

<div class="card mb-3">
  <div class="card-body">
    <?= $this->Form->create(null, ['type' => 'get']) ?>

    <div class="row g-2 align-items-end">
      <div class="col-6 col-md-3">
        <?= $this->Form->control('year', [
          'label' => '年',
          'type' => 'select',
          'options' => $years,
          'empty' => '選択',
          'value' => $year ?: null,
          'class' => 'form-select',
        ]) ?>
      </div>

      <div class="col-6 col-md-3">
        <?= $this->Form->control('month', [
          'label' => '月',
          'type' => 'select',
          'options' => $months,
          'empty' => '選択',
          'value' => $month ?: null,
          'class' => 'form-select',
        ]) ?>
      </div>

      <div class="col-12 col-md-6 d-flex gap-2">
        <?= $this->Form->button('絞り込む', ['class' => 'btn btn-outline-primary']) ?>

        <?= $this->Html->link(
          'リセット',
          ['action' => 'index'],
          ['class' => 'btn btn-outline-secondary']
        ) ?>
      </div>
    </div>

    <?= $this->Form->end() ?>
  </div>
</div>

<div class="table-responsive">
  <table class="table table-striped table-bordered align-middle">
    <thead class="table-light">
      <tr>
        <th style="width: 80px;">ID</th>
        <th style="min-width: 100px;">営業日</th>
        <th style="min-width: 100px;">予約締切</th>
        <th style="width: 90px;">状態</th>
        <th style="width: 120px;">操作</th>
        <th style="width: 120px;">出品管理</th>
        <th style="width: 120px;">予約集計</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach ($businessDays as $day) : ?>
        <tr class="<?= $day->is_active ? '' : 'table-secondary' ?>">
          <td><?= (int)$day->id ?></td>

          <td>
            <strong><?= h($day->business_date->i18nFormat('yyyy/MM/dd')) ?></strong>
          </td>

          <td>
            <?= h($day->order_deadline->i18nFormat('yyyy/MM/dd HH:mm')) ?>
          </td>

          <td>
            <div class="d-flex align-items-center gap-2">

                <?php if ($day->is_active): ?>

                <span class="badge text-bg-success flex-shrink-0" style="width:60px;">
                    有効
                </span>

                <?= $this->Form->postLink(
                    '切替',
                    ['action' => 'toggle', $day->id],
                    [
                    'class' => 'btn btn-sm btn-outline-secondary flex-grow-1',
                    'confirm' => 'この営業日を無効にしますか？'
                    ]
                ) ?>

                <?php else: ?>

                <span class="badge text-bg-secondary flex-shrink-0" style="width:60px;">
                    無効
                </span>

                <?= $this->Form->postLink(
                    '切替',
                    ['action' => 'toggle', $day->id],
                    [
                    'class' => 'btn btn-sm btn-outline-success flex-grow-1',
                    'confirm' => 'この営業日を有効にしますか？'
                    ]
                ) ?>

                <?php endif; ?>

            </div>
          </td>



          <td>
            <?= $this->Html->link(
              '編集',
              ['action' => 'edit', $day->id],
              ['class' => 'btn btn-sm btn-outline-primary']
            ) ?>
          </td>

          <td>
            <?= $this->Html->link(
              '出品管理',
              ['controller' => 'Products', 'action' => 'index', $day->id],
              ['class' => 'btn btn-sm btn-outline-success']
            ) ?>
          </td>
          <td>
            <?= $this->Html->link(
              '予約集計',
              ['action' => 'view', $day->id],
              ['class' => 'btn btn-sm btn-outline-info']
            ) ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<nav class="mt-3">
  <ul class="pagination">
    <li class="page-item">
      <?= $this->Paginator->prev('« 前へ', ['class' => 'page-link'], null, ['class' => 'page-link disabled']) ?>
    </li>

    <?= $this->Paginator->numbers([
      'class' => 'page-item',
      'currentClass' => 'active',
      'currentTag' => 'span',
      'tag' => 'li',
      'templates' => [
        'number' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
        'current' => '<li class="page-item active"><span class="page-link">{{text}}</span></li>',
      ]
    ]) ?>

    <li class="page-item">
      <?= $this->Paginator->next('次へ »', ['class' => 'page-link'], null, ['class' => 'page-link disabled']) ?>
    </li>
  </ul>
</nav>
