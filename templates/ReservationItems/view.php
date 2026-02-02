<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ReservationItem $reservationItem
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Reservation Item'), ['action' => 'edit', $reservationItem->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Reservation Item'), ['action' => 'delete', $reservationItem->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reservationItem->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Reservation Items'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Reservation Item'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="reservationItems view content">
            <h3><?= h($reservationItem->product_name_at_order) ?></h3>
            <table>
                <tr>
                    <th><?= __('Reservation') ?></th>
                    <td><?= $reservationItem->hasValue('reservation') ? $this->Html->link($reservationItem->reservation->source, ['controller' => 'Reservations', 'action' => 'view', $reservationItem->reservation->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Product') ?></th>
                    <td><?= $reservationItem->hasValue('product') ? $this->Html->link($reservationItem->product->name, ['controller' => 'Products', 'action' => 'view', $reservationItem->product->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Product Name At Order') ?></th>
                    <td><?= h($reservationItem->product_name_at_order) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($reservationItem->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Price At Order') ?></th>
                    <td><?= $this->Number->format($reservationItem->price_at_order) ?></td>
                </tr>
                <tr>
                    <th><?= __('Quantity') ?></th>
                    <td><?= $this->Number->format($reservationItem->quantity) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>