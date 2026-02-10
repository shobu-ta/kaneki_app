<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css(['normalize.min']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <style>
    body { padding-top: 0; }
    body.is-admin { padding-top: 72px; } 
    .admin-top-menu {
        display: flex;
        gap: 12px;
        padding: 10px 16px;
        background: #1f2937;
    }
    .admin-top-menu a {
        color: #fff;
        text-decoration: none;
        padding: 6px 10px;
        border-radius: 4px;
    }
    .admin-top-menu a:hover {
        background: #374151;
    }
    </style>

</head>
<?php
$isAdminPrefix = $this->request->getParam('prefix') === 'Admin';
$isLoggedIn = (bool)$this->request->getAttribute('identity');
?>

<body class="<?= $isAdminPrefix ? 'is-admin' : '' ?>">
  <?php if ($isAdminPrefix && $isLoggedIn) : ?>
    <?= $this->element('admin_top_menu') ?>
  <?php endif; ?>

  <?php $containerClass = $isAdminPrefix ? 'container-fluid' : 'container'; ?>

  <main class="main py-3">
    <div class="<?= $containerClass ?>">
      <?= $this->Flash->render() ?>
      <?= $this->fetch('content') ?>
    </div>
  </main>

  <footer></footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
