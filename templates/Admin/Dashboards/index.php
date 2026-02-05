<h1>管理者ダッシュボード</h1>

<ul>
    <li>
        <?= $this->Html->link(
            '営業日管理',
            ['controller' => 'BusinessDays', 'action' => 'index']
        ) ?>
    </li>
    <li>
        <?= $this->Html->link(
            '商品管理',
            ['controller' => 'ProductMasters', 'action' => 'index']
        ) ?>
    </li>
    <li>
        <?= $this->Html->link(
            '予約管理',
            ['controller' => 'Reservations', 'action' => 'index']
        ) ?>
    </li>
    <li>
        <?= $this->Html->link(
            'ログアウト',
            ['controller' => 'Admins', 'action' => 'logout']
        ) ?>
    </li>
</ul>
