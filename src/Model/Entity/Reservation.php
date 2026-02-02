<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Reservation Entity
 *
 * @property int $id
 * @property int $business_day_id
 * @property string $source
 * @property string $status
 * @property string $customer_name
 * @property string $phone
 * @property string|null $email
 * @property int $total_price
 * @property string|null $note
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\BusinessDay $business_day
 * @property \App\Model\Entity\ReservationItem[] $reservation_items
 */
class Reservation extends Entity
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
        'source' => true,
        'status' => true,
        'customer_name' => true,
        'phone' => true,
        'email' => true,
        'total_price' => true,
        'note' => true,
        'created' => true,
        'modified' => true,
        'business_day' => true,
        'reservation_items' => true,
    ];
}
