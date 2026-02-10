<h1 class="h4 mb-3">お客様情報入力</h1>

<?= $this->Flash->render() ?>

<div class="card">
  <div class="card-body">
    <?= $this->Form->create(null, ['novalidate' => true]) ?>

    <div class="mb-3">
      <?= $this->Form->control('customer_name', [
        'label' => '氏名（必須）',
        'class' => 'form-control',
        'required' => true,
        'autocomplete' => 'name',
        'placeholder' => '例）山田 太郎',
      ]) ?>
    </div>

    <div class="mb-3">
      <?= $this->Form->control('phone', [
        'label' => '電話番号（必須）',
        'class' => 'form-control',
        'required' => true,
        'type' => 'tel',
        'inputmode' => 'tel',
        'autocomplete' => 'tel',
        'placeholder' => '例）09012345678',
        'help' => 'ハイフンなしで入力してください',
      ]) ?>
    </div>

    <div class="mb-3">
      <?= $this->Form->control('email', [
        'label' => 'メールアドレス（必須）',
        'class' => 'form-control',
        'required' => true,
        'type' => 'email',
        'inputmode' => 'email',
        'autocomplete' => 'email',
        'placeholder' => '例）example@example.com',
      ]) ?>
    </div>

    <div class="mb-3">
      <?= $this->Form->control('note', [
        'label' => '備考（任意）',
        'class' => 'form-control',
        'type' => 'textarea',
        'required' => false,
        'rows' => 3,
        'placeholder' => '受け取り時間の希望など',
      ]) ?>
    </div>

    <div class="d-grid gap-2">
      <?= $this->Form->button('確認へ', ['class' => 'btn btn-primary btn-lg']) ?>
      <?= $this->Html->link('戻る（商品選択へ）', ['controller' => 'BusinessDays', 'action' => 'index'], ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?= $this->Form->end() ?>
  </div>
</div>

<p class="text-muted small mt-3">
  入力内容は予約確認画面で確認できます。
</p>
