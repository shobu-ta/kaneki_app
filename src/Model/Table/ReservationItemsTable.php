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
     * 指定された商品ID群について、予約済み数量の合計を取得する
     *
     * @param array<int> $productIds 商品IDの配列
     * @return array<int, int> キーが商品ID、値が予約済み数量合計の連想配列
     */
    public function sumReservedQuantityByProductIds(array $productIds): array
    {
        if (empty($productIds)) return []; // 商品ID配列が空なら、何も集計できないので空配列を返す

        $rows = $this->find() // ReservationItems テーブルから検索を開始
            ->select([
                'product_id' => 'ReservationItems.product_id', // ReservationItems.product_id を product_id という名前で取得
                'qty_sum' => $this->find()->func()->sum('ReservationItems.quantity'),  // quantity の合計値を qty_sum という名前で取得
            ])
            ->contain([]) // contain([]) は「関連テーブルの自動取得をしない」指定
            ->innerJoinWith('Reservations', function ($q) { // ReservationItems に紐づく Reservations テーブルを INNER JOIN つまり「予約テーブルと結合」する
                return $q->where(['Reservations.status' => 'reserved']); // 結合した Reservations の中からstatus = 'reserved' のものだけを対象にする
            })
            ->where(['ReservationItems.product_id IN' => $productIds])   // 指定された product_id の予約アイテムだけ対象にする
            ->groupBy(['ReservationItems.product_id']) // 商品ごとに集計するため
            ->all();
         // ここから結果を「使いやすい連想配列」に変換する
        $map = [];
        foreach ($rows as $r) {
            $map[(int)$r->product_id] = (int)$r->qty_sum; //配列のキーを product_id にして値に qty_sum（合計数量）を入れる 例： [3 => 12]  ← product_id=3 は合計12個予約済み
        }
        return $map;
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
