<h1>営業日追加</h1>

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
        'type' => 'checkbox',
        'default' => true,
    ]) ?>
</fieldset>

<?= $this->Form->button('登録する') ?>
<?= $this->Form->end() ?>

<p>
    <?= $this->Html->link('一覧へ戻る', ['action' => 'index']) ?>
</p>
