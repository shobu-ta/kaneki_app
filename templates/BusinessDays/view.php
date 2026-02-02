<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\BusinessDay $businessDay
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Business Day'), ['action' => 'edit', $businessDay->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Business Day'), ['action' => 'delete', $businessDay->id], ['confirm' => __('Are you sure you want to delete # {0}?', $businessDay->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Business Days'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Business Day'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="businessDays view content">
            <h3><?= h($businessDay->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($businessDay->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Business Date') ?></th>
                    <td><?= h($businessDay->business_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Order Deadline') ?></th>
                    <td><?= h($businessDay->order_deadline) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($businessDay->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($businessDay->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Is Active') ?></th>
                    <td><?= $businessDay->is_active ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Products') ?></h4>
                <?php if (!empty($businessDay->products)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Price') ?></th>
                            <th><?= __('Max Quantity') ?></th>
                            <th><?= __('Is Active') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($businessDay->products as $product) : ?>
                        <tr>
                            <td><?= h($product->id) ?></td>
                            <td><?= h($product->name) ?></td>
                            <td><?= h($product->price) ?></td>
                            <td><?= h($product->max_quantity) ?></td>
                            <td><?= h($product->is_active) ?></td>
                            <td><?= h($product->created) ?></td>
                            <td><?= h($product->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Products', 'action' => 'view', $product->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Products', 'action' => 'edit', $product->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Products', 'action' => 'delete', $product->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $product->id),
                                    ]
                                ) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Reservations') ?></h4>
                <?php if (!empty($businessDay->reservations)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Source') ?></th>
                            <th><?= __('Status') ?></th>
                            <th><?= __('Customer Name') ?></th>
                            <th><?= __('Phone') ?></th>
                            <th><?= __('Email') ?></th>
                            <th><?= __('Total Price') ?></th>
                            <th><?= __('Note') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($businessDay->reservations as $reservation) : ?>
                        <tr>
                            <td><?= h($reservation->id) ?></td>
                            <td><?= h($reservation->source) ?></td>
                            <td><?= h($reservation->status) ?></td>
                            <td><?= h($reservation->customer_name) ?></td>
                            <td><?= h($reservation->phone) ?></td>
                            <td><?= h($reservation->email) ?></td>
                            <td><?= h($reservation->total_price) ?></td>
                            <td><?= h($reservation->note) ?></td>
                            <td><?= h($reservation->created) ?></td>
                            <td><?= h($reservation->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Reservations', 'action' => 'view', $reservation->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Reservations', 'action' => 'edit', $reservation->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Reservations', 'action' => 'delete', $reservation->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $reservation->id),
                                    ]
                                ) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>