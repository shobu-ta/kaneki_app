<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
  <div class="container-fluid">

    <!-- 左：タイトル -->
    <?= $this->Html->link(
      '管理画面',
      ['prefix' => 'Admin', 'controller' => 'Dashboards', 'action' => 'index'],
      ['class' => 'navbar-brand']
    ) ?>

    <!-- スマホハンバーガー -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="adminNav">

      <!-- 左側メニュー -->
      <ul class="navbar-nav me-auto">

        <li class="nav-item">
          <?= $this->Html->link(
            'ダッシュボード',
            ['prefix' => 'Admin', 'controller' => 'Dashboards', 'action' => 'index'],
            ['class' => 'nav-link']
          ) ?>
        </li>

        <li class="nav-item">
          <?= $this->Html->link(
            '営業日・出品管理',
            ['prefix' => 'Admin', 'controller' => 'BusinessDays', 'action' => 'index'],
            ['class' => 'nav-link']
          ) ?>
        </li>

        <li class="nav-item">
          <?= $this->Html->link(
            '商品マスタ管理',
            ['prefix' => 'Admin', 'controller' => 'ProductMasters', 'action' => 'index'],
            ['class' => 'nav-link']
          ) ?>
        </li>

        <li class="nav-item">
          <?= $this->Html->link(
            '予約管理',
            ['prefix' => 'Admin', 'controller' => 'Reservations', 'action' => 'index'],
            ['class' => 'nav-link']
          ) ?>
        </li>

      </ul>

      <!-- 右側：ログアウト -->
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <?= $this->Html->link(
            'ログアウト',
            ['prefix' => 'Admin', 'controller' => 'Admins', 'action' => 'logout'],
            ['class' => 'nav-link text-warning']
          ) ?>
        </li>
      </ul>

    </div>
  </div>
</nav>
