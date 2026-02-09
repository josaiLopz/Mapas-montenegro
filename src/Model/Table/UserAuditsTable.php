<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UserAudits Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\UserAudit newEmptyEntity()
 * @method \App\Model\Entity\UserAudit newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\UserAudit> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UserAudit get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\UserAudit findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\UserAudit patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\UserAudit> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\UserAudit|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\UserAudit saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\UserAudit>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UserAudit>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UserAudit>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UserAudit> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UserAudit>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UserAudit>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UserAudit>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UserAudit> deleteManyOrFail(iterable $entities, array $options = [])
 */
class UserAuditsTable extends Table
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

        $this->setTable('user_audits');
        $this->setDisplayField('action');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
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
            ->integer('user_id')
            ->notEmptyString('user_id');

        $validator
            ->scalar('action')
            ->maxLength('action', 255)
            ->requirePresence('action', 'create')
            ->notEmptyString('action');

        $validator
            ->scalar('old_data')
            ->requirePresence('old_data', 'create')
            ->notEmptyString('old_data');

        $validator
            ->scalar('new_data')
            ->requirePresence('new_data', 'create')
            ->notEmptyString('new_data');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}
