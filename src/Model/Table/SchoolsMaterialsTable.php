<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SchoolsMaterials Model
 *
 * @property \App\Model\Table\SchoolsTable&\Cake\ORM\Association\BelongsTo $Schools
 * @property \App\Model\Table\MaterialsTable&\Cake\ORM\Association\BelongsTo $Materials
 *
 * @method \App\Model\Entity\SchoolsMaterial newEmptyEntity()
 * @method \App\Model\Entity\SchoolsMaterial newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\SchoolsMaterial> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SchoolsMaterial get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\SchoolsMaterial findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\SchoolsMaterial patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\SchoolsMaterial> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\SchoolsMaterial|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\SchoolsMaterial saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\SchoolsMaterial>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\SchoolsMaterial>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\SchoolsMaterial>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\SchoolsMaterial> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\SchoolsMaterial>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\SchoolsMaterial>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\SchoolsMaterial>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\SchoolsMaterial> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SchoolsMaterialsTable extends Table
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

        $this->setTable('schools_materials');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Schools', [
            'foreignKey' => 'school_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Materials', [
            'foreignKey' => 'material_id',
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
            ->integer('school_id')
            ->notEmptyString('school_id');

        $validator
            ->integer('material_id')
            ->notEmptyString('material_id');

        $validator
            ->decimal('proyeccion_venta')
            ->notEmptyString('proyeccion_venta');

        $validator
            ->decimal('cierre_2026')
            ->notEmptyString('cierre_2026');

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
        $rules->add($rules->isUnique(['school_id', 'material_id']), ['errorField' => 'school_id']);
        $rules->add($rules->existsIn(['school_id'], 'Schools'), ['errorField' => 'school_id']);
        $rules->add($rules->existsIn(['material_id'], 'Materials'), ['errorField' => 'material_id']);

        return $rules;
    }
}
