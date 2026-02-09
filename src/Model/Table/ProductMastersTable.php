<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductMasters Model
 *
 * @property \App\Model\Table\ProductsTable&\Cake\ORM\Association\HasMany $Products
 *
 * @method \App\Model\Entity\ProductMaster newEmptyEntity()
 * @method \App\Model\Entity\ProductMaster newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\ProductMaster> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductMaster get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\ProductMaster findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\ProductMaster patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\ProductMaster> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductMaster|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\ProductMaster saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\ProductMaster>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProductMaster>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ProductMaster>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProductMaster> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ProductMaster>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProductMaster>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ProductMaster>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProductMaster> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProductMastersTable extends Table
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

        $this->setTable('product_masters');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Products', [
            'foreignKey' => 'product_master_id',
        ]);
    }

    public const GENRES = [
    '蒸しパン' => '蒸しパン',
    'シフォンケーキ' => 'シフォンケーキ',
    'パウンドケーキ' => 'パウンドケーキ',
    'そのほか' => 'そのほか',
    ];


    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->integer('base_price')
            ->requirePresence('base_price', 'create')
            ->notEmptyString('base_price')
            ->greaterThanOrEqual('base_price', 0);

        $validator
            ->boolean('is_active')
            ->notEmptyString('is_active');

        $validator
            ->scalar('genre')
            ->maxLength('genre', 50)
            ->requirePresence('genre', 'create')
            ->notEmptyString('genre')
            ->inList('genre', array_keys(self::GENRES), 'ジャンルが不正です。');

        return $validator;
    }
}
