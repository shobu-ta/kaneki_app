<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\ReservationItem> $reservationItems
 */
?>
<div class="reservationItems index content">
    <?= $this->Html->link(__('New Reservation Item'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Reservation Items') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('reservation_id') ?></th>
                    <th><?= $this->Paginator->sort('product_id') ?></th>
                    <th><?= $this->Paginator->sort('product_name_at_order') ?></th>
                    <th><?= $this->Paginator->sort('price_at_order') ?></th>
                    <th><?= $this->Paginator->sort('quantity') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservationItems as $reservationItem): ?>
                <tr>
                    <td><?= $this->Number->format($reservationItem->id) ?></td>
                    <td><?= $reservationItem->hasValue('reservation') ? $this->Html->link($reservationItem->reservation->source, ['controller' => 'Reservations', 'action' => 'view', $reservationItem->reservation->id]) : '' ?></td>
                    <td><?= $reservationItem->hasValue('product') ? $this->Html->link($reservationItem->product->name, ['controller' => 'Products', 'action' => 'view', $reservationItem->product->id]) : '' ?></td>
                    <td><?= h($reservationItem->product_name_at_order) ?></td>
                    <td><?= $this->Number->format($reservationItem->price_at_order) ?></td>
                    <td><?= $this->Number->format($reservationItem->quantity) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $reservationItem->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $reservationItem->id]) ?>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $reservationItem->id],
                            [
                                'method' => 'delete',
                                'confirm' => __('Are you sure you want to delete # {0}?', $reservationItem->id),
                            ]
                        ) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>