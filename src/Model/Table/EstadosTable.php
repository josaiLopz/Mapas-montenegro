<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Estados Model
 *
 * @property \App\Model\Table\MunicipiosTable&\Cake\ORM\Association\HasMany $Municipios
 * @property \App\Model\Table\SchoolsTable&\Cake\ORM\Association\HasMany $Schools
 *
 * @method \App\Model\Entity\Estado newEmptyEntity()
 * @method \App\Model\Entity\Estado newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Estado> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Estado get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Estado findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Estado patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Estado> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Estado|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Estado saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Estado>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Estado>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Estado>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Estado> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Estado>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Estado>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Estado>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Estado> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EstadosTable extends Table
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

        $this->setTable('estados');
        $this->setDisplayField('nombre');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Municipios', [
            'foreignKey' => 'estado_id',
        ]);
        $this->hasMany('Schools', [
            'foreignKey' => 'estado_id',
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
            ->scalar('nombre')
            ->maxLength('nombre', 100)
            ->requirePresence('nombre', 'create')
            ->notEmptyString('nombre');

        return $validator;
    }
}
