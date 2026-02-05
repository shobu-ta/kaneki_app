<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Product Entity
 *
 * @property int $id
 * @property int $business_day_id
 * @property string $name
 * @property int $price
 * @property int|null $max_quantity
 * @property bool|null $is_active
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\BusinessDay $business_day
 * @property \App\Model\Entity\ReservationItem[] $reservation_items
 */
class Product extends Entity
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
        'business_day_id' => true,
        'name' => true,
        'price' => true,
        'max_quantity' => true,
        'is_active' => true,
        'created' => true,
        'modified' => true,
        'business_day' => true,
        'reservation_items' => true,
        'product_master_id' => true,
    ];
}
