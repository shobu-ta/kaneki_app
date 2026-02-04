<h1>営業日編集</h1>

<?= $this->Form->create($businessDay) ?>

<fieldset>
    <legend>営業日情報</legend>

    <?= $this->Form->control('business_date', [
        'label' => '営業日',
        'type' => 'date',
    ]) ?>

    <?= $this->Form->control('order_deadline', [
        'label' => '予約締切日時',
        'type' => 'datetime',
    ]) ?>

    <?= $this->Form->control('is_active', [
        'label' => '有効にする',
    ]) ?>
</fieldset>

<?= $this->Form->button('更新する') ?>
<?= $this->Form->end() ?>

<p>
    <?= $this->Html->link('一覧へ戻る', ['action' => 'index']) ?>
</p>
