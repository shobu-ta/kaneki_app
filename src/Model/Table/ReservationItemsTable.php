<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReservationItems Model
 *
 * @property \App\Model\Table\ReservationsTable&\Cake\ORM\Association\BelongsTo $Reservations
 * @property \App\Model\Table\ProductsTable&\Cake\ORM\Association\BelongsTo $Products
 *
 * @method \App\Model\Entity\ReservationItem newEmptyEntity()
 * @method \App\Model\Entity\ReservationItem newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\ReservationItem> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReservationItem get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\ReservationItem findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\ReservationItem patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\ReservationItem> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReservationItem|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\ReservationItem saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\ReservationItem>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ReservationItem>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ReservationItem>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ReservationItem> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ReservationItem>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ReservationItem>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ReservationItem>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ReservationItem> deleteManyOrFail(iterable $entities, array $options = [])
 */
class ReservationItemsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('reservation_items');
        $this->setDisplayField('product_name_at_order');
        $this->setPrimaryKey('id');

        $this->belongsTo('Reservations', [
            'foreignKey' => 'reservation_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('reservation_id')
            ->notEmptyString('reservation_id');

        $validator
            ->integer('product_id')
            ->notEmptyString('product_id');

        $validator
            ->scalar('product_name_at_order')
            ->maxLength('product_name_at_order', 255)
            ->requirePresence('product_name_at_order', 'create')
            ->notEmptyString('product_name_at_order');

        $validator
            ->integer('price_at_order')
            ->requirePresence('price_at_order', 'create')
            ->notEmptyString('price_at_order');

        $validator
            ->integer('quantity')
            ->requirePresence('quantity', 'create')
            ->notEmptyString('quantity');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['reservation_id'], 'Reservations'), ['errorField' => 'reservation_id']);
        $rules->add($rules->existsIn(['product_id'], 'Products'), ['errorField' => 'product_id']);

        return $rules;
    }
}
