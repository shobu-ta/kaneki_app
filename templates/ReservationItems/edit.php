<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ReservationItem $reservationItem
 * @var string[]|\Cake\Collection\CollectionInterface $reservations
 * @var string[]|\Cake\Collection\CollectionInterface $products
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $reservationItem->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $reservationItem->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Reservation Items'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="reservationItems form content">
            <?= $this->Form->create($reservationItem) ?>
            <fieldset>
                <legend><?= __('Edit Reservation Item') ?></legend>
                <?php
                    echo $this->Form->control('reservation_id', ['options' => $reservations]);
                    echo $this->Form->control('product_id', ['options' => $products]);
                    echo $this->Form->control('product_name_at_order');
                    echo $this->Form->control('price_at_order');
                    echo $this->Form->control('quantity');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
