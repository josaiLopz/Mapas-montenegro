<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Materials Model
 *
 * @property \App\Model\Table\SchoolsTable&\Cake\ORM\Association\BelongsToMany $Schools
 *
 * @method \App\Model\Entity\Material newEmptyEntity()
 * @method \App\Model\Entity\Material newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Material> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Material get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Material findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Material patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Material> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Material|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Material saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Material>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Material>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Material>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Material> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Material>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Material>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Material>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Material> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MaterialsTable extends Table
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

        $this->setTable('materials');
        $this->setDisplayField('nivel');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Schools', [
            'foreignKey' => 'material_id',
            'targetForeignKey' => 'school_id',
            'joinTable' => 'schools_materials',
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
            ->scalar('nivel')
            ->maxLength('nivel', 80)
            ->notEmptyString('nivel');

        $validator
            ->scalar('nombre')
            ->maxLength('nombre', 255)
            ->requirePresence('nombre', 'create')
            ->notEmptyString('nombre');

        $validator
            ->boolean('activo')
            ->notEmptyString('activo');

        return $validator;
    }
}
