<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SchoolChangeRequests Model
 *
 * @property \App\Model\Table\SchoolsTable&\Cake\ORM\Association\BelongsTo $Schools
 *
 * @method \App\Model\Entity\SchoolChangeRequest newEmptyEntity()
 * @method \App\Model\Entity\SchoolChangeRequest newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\SchoolChangeRequest> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SchoolChangeRequest get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\SchoolChangeRequest findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\SchoolChangeRequest patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\SchoolChangeRequest> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\SchoolChangeRequest|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\SchoolChangeRequest saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\SchoolChangeRequest>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\SchoolChangeRequest>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\SchoolChangeRequest>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\SchoolChangeRequest> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\SchoolChangeRequest>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\SchoolChangeRequest>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\SchoolChangeRequest>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\SchoolChangeRequest> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SchoolChangeRequestsTable extends Table
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

        $this->setTable('school_change_requests');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Schools', [
            'foreignKey' => 'school_id',
        ]);

        $this->belongsTo('Requesters', [
            'className' => 'Users',
            'foreignKey' => 'requested_by',
        ]);

        $this->belongsTo('Approvers', [
            'className' => 'Users',
            'foreignKey' => 'approved_by',
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
            ->integer('school_id')->notEmptyString('school_id')
            ->integer('requested_by')->notEmptyString('requested_by')
            ->allowEmptyString('approved_by')
            ->scalar('type')->maxLength('type', 20)->notEmptyString('type')
            ->scalar('status')->maxLength('status', 20)->notEmptyString('status')
            ->allowEmptyString('comment')
            ->allowEmptyDateTime('approved_at');

        // payload es JSON: no scalar/maxLength
        $validator->allowEmptyArray('payload'); // o ->notEmptyArray('payload') si quieres obligatorio

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
        $rules->add($rules->existsIn(['school_id'], 'Schools'), ['errorField' => 'school_id']);

        return $rules;
    }
}
