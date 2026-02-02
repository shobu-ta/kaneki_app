<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BusinessDay Entity
 *
 * @property int $id
 * @property \Cake\I18n\Date $business_date
 * @property \Cake\I18n\DateTime $order_deadline
 * @property bool|null $is_active
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Product[] $products
 * @property \App\Model\Entity\Reservation[] $reservations
 */
class BusinessDay extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'business_date' => true,
        'order_deadline' => true,
        'is_active' => true,
        'created' => true,
        'modified' => true,
        'products' => true,
        'reservations' => true,
    ];
}
