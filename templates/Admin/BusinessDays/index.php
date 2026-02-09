<h1>営業日管理➡出品管理</h1>

<?php $this->Paginator->setTemplates([]); ?>

<p>
    <?= $this->Html->link(
        '＋ 新規営業日追加',
        ['action' => 'add']
    ) ?>
</p>

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

  <?= $this->Form->button('絞り込む') ?>

  <?= $this->Html->link('リセット', ['action' => 'index'], ['style' => 'margin-left:10px;']) ?>
<?= $this->Form->end() ?>


<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>ID</th>
            <th>営業日</th>
            <th>予約締切</th>
            <th>状態</th>
            <th>操作</th>
            <th>出品管理</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($businessDays as $day) : ?>
        <tr>
            <td><?= $day->id ?></td>
            <td><?= h($day->business_date->i18nFormat('yyyy/MM/dd')) ?></td>
            <td><?= h($day->order_deadline->i18nFormat('yyyy/MM/dd HH:mm')) ?></td>
            <td>
                <?= $day->is_active ? '有効' : '無効' ?>
            </td>
            <td>
                <?= $this->Html->link('編集', ['action' => 'edit', $day->id]) ?>
                
            </td>
            <td><?= $this->Html->link('出品管理',['controller' => 'Products', 'action' => 'index', $day->id])?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div style="margin-top:15px;">
  <?= $this->Paginator->prev('« 前へ') ?>
  <?= $this->Paginator->numbers() ?>
  <?= $this->Paginator->next('次へ »') ?>
</div>