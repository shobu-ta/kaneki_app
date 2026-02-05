<h1>お客様情報入力</h1>
<?= $this->Flash->render() ?>

<?= $this->Form->create() ?>
<?= $this->Form->control('customer_name', ['label' => '氏名']) ?>
<?= $this->Form->control('phone', ['label' => '電話番号']) ?>
<?= $this->Form->control('email', ['label' => 'メールアドレス']) ?>
<?= $this->Form->control('note', ['label' => '備考', 'type' => 'textarea', 'required' => false]) ?>
<?= $this->Form->button('確認へ') ?>
<?= $this->Form->end() ?>
