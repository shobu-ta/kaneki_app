<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ReservationItem Entity
 *
 * @property int $id
 * @property int $reservation_id
 * @property int $product_id
 * @property string $product_name_at_order
 * @property int $price_at_order
 * @property int $quantity
 *
 * @property \App\Model\Entity\Reservation $reservation
 * @property \App\Model\Entity\Product $product
 */
class ReservationItem extends Entity
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
        'reservation_id' => true,
        'product_id' => true,
        'product_name_at_order' => true,
        'price_at_order' => true,
        'quantity' => true,
        'reservation' => true,
        'product' => true,
    ];
}
