<h1 class="h4 mb-3">営業日編集</h1>

<div class="card">
  <div class="card-body">

    <?= $this->Form->create($businessDay, ['novalidate' => true]) ?>

    <div class="row g-3">

      <div class="col-12 col-md-6">
        <?= $this->Form->control('business_date', [
          'label' => '営業日（必須）',
          'type' => 'date',
          'class' => 'form-control',
          'required' => true,
        ]) ?>
      </div>

      <div class="col-12 col-md-6">
        <?= $this->Form->control('order_deadline', [
          'label' => '予約締切日時（必須）',
          'type' => 'datetime',
          'class' => 'form-control',
          'required' => true,
        ]) ?>
        <div class="form-text">
          例：前日 21:00 など、任意の時刻に設定してください。
        </div>
      </div>

      <div class="col-12">
        <div class="form-check">
          <?= $this->Form->control('is_active', [
            'type' => 'checkbox',
            'label' => 'この営業日を表示する（チェックを外すと予約受付を停止します）',
            'class' => 'form-check-input',
            'templates' => [
              'inputContainer' => '{{content}}',
              'checkboxWrapper' => '{{input}}{{label}}',
            ],
          ]) ?>
        </div>
      </div>

    </div>

    <hr class="my-4">

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
      <?= $this->Html->link(
        '一覧へ戻る',
        ['action' => 'index'],
        ['class' => 'btn btn-outline-secondary']
      ) ?>

      <?= $this->Form->button('更新する', ['class' => 'btn btn-primary']) ?>
    </div>

    <?= $this->Form->end() ?>

  </div>
</div>
