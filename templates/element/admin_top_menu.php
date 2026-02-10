<nav class="admin-top-menu">
    <?= $this->Html->link('管理者ダッシュボード', [
        'prefix' => 'Admin', 'controller' => 'Dashboards', 'action' => 'index',
    ]) ?>
    <?= $this->Html->link('営業日管理➡出品管理', [
        'prefix' => 'Admin', 'controller' => 'BusinessDays', 'action' => 'index',
    ]) ?>
    <?= $this->Html->link('商品マスタ管理', [
        'prefix' => 'Admin', 'controller' => 'ProductMasters', 'action' => 'index',
    ]) ?>
    <?= $this->Html->link('予約管理', [
        'prefix' => 'Admin', 'controller' => 'Reservations', 'action' => 'index',
    ]) ?>
    <?= $this->Html->link('ログアウト', [
        'prefix' => 'Admin', 'controller' => 'Admins', 'action' => 'logout',
    ]) ?>
</nav>
