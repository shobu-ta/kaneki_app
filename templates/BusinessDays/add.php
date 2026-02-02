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
            <?= $this->Html->link(__('List Business Days'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="businessDays form content">
            <?= $this->Form->create($businessDay) ?>
            <fieldset>
                <legend><?= __('Add Business Day') ?></legend>
                <?php
                    echo $this->Form->control('business_date');
                    echo $this->Form->control('order_deadline');
                    echo $this->Form->control('is_active');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
