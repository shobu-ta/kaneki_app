<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BusinessDays Model
 *
 * @property \App\Model\Table\ProductsTable&\Cake\ORM\Association\HasMany $Products
 * @property \App\Model\Table\ReservationsTable&\Cake\ORM\Association\HasMany $Reservations
 *
 * @method \App\Model\Entity\BusinessDay newEmptyEntity()
 * @method \App\Model\Entity\BusinessDay newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\BusinessDay> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BusinessDay get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\BusinessDay findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\BusinessDay patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\BusinessDay> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\BusinessDay|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\BusinessDay saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\BusinessDay>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BusinessDay>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BusinessDay>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BusinessDay> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BusinessDay>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BusinessDay>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BusinessDay>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BusinessDay> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BusinessDaysTable extends Table
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

        $this->setTable('business_days');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Products', [
            'foreignKey' => 'business_day_id',
            'dependent' => true,
        ]);
        $this->hasMany('Reservations', [
            'foreignKey' => 'business_day_id',
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
            ->date('business_date')
            ->requirePresence('business_date', 'create')
            ->notEmptyDate('business_date');

        $validator
            ->dateTime('order_deadline')
            ->requirePresence('order_deadline', 'create')
            ->notEmptyDateTime('order_deadline');

        $validator
            ->boolean('is_active')
            ->allowEmptyString('is_active');

        return $validator;
    }
}
