<h1 class="mb-4">管理者ダッシュボード</h1>

<div class="row g-3">

  <!-- 営業日管理 -->
  <div class="col-12 col-md-6 col-lg-4">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex flex-column">
        <h5 class="card-title">営業日・出品管理</h5>
        <p class="card-text text-muted small">
          営業日の登録・編集、出品商品の設定を行います。
        </p>

        <?= $this->Html->link(
          '営業日管理へ',
          ['controller' => 'BusinessDays', 'action' => 'index'],
          ['class' => 'btn btn-primary mt-auto']
        ) ?>
      </div>
    </div>
  </div>

  <!-- 商品マスタ管理 -->
  <div class="col-12 col-md-6 col-lg-4">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex flex-column">
        <h5 class="card-title">商品マスタ管理</h5>
        <p class="card-text text-muted small">
          蒸しパンの基本商品情報・価格を管理します。
        </p>

        <?= $this->Html->link(
          '商品マスタ一覧へ',
          ['controller' => 'ProductMasters', 'action' => 'index'],
          ['class' => 'btn btn-success mt-auto']
        ) ?>
      </div>
    </div>
  </div>

  <!-- 予約管理 -->
  <div class="col-12 col-md-6 col-lg-4">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex flex-column">
        <h5 class="card-title">予約管理</h5>
        <p class="card-text text-muted small">
          予約一覧の確認、Instagram予約の手動登録を行います。
        </p>

        <?= $this->Html->link(
          '予約一覧へ',
          ['controller' => 'Reservations', 'action' => 'index'],
          ['class' => 'btn btn-warning mt-auto']
        ) ?>
      </div>
    </div>
  </div>

</div>

