<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Schools Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Distribuidors
 *
 * @method \App\Model\Entity\School newEmptyEntity()
 * @method \App\Model\Entity\School newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\School> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\School get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\School findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\School patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\School> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\School|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\School saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\School>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\School>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\School>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\School> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\School>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\School>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\School>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\School> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SchoolsTable extends Table
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

        $this->setTable('schools');
        $this->setDisplayField('nombre');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Estados', [
            'foreignKey' => 'estado_id',
            'joinType' => 'INNER',
            'propertyName' => 'estado' // esto crea $school->estado
        ]);

        $this->belongsTo('Municipios', [
            'foreignKey' => 'municipio_id',
            'joinType' => 'INNER',
            'propertyName' => 'municipio' // esto crea $school->municipio
        ]);
        $this->hasMany('SchoolsMaterials', [
            'foreignKey' => 'school_id',
            'dependent' => true,
        ]);
        $this->hasMany('Visits', [
            'foreignKey' => 'school_id',
            'dependent' => true,
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
            ->maxLength('nombre', 255)
            ->requirePresence('nombre', 'create')
            ->notEmptyString('nombre');

        $validator
            ->integer('estado_id')
            ->requirePresence('estado_id', 'create')
            ->notEmptyString('estado_id', 'Debes seleccionar un estado');

        $validator
            ->integer('municipio_id')
            ->requirePresence('municipio_id', 'create')
            ->notEmptyString('municipio_id', 'Debes seleccionar un municipio');

        $validator
            ->scalar('tipo')
            ->maxLength('tipo', 50)
            ->allowEmptyString('tipo');

        $validator
            ->scalar('sector')
            ->maxLength('sector', 50)
            ->allowEmptyString('sector');

        $validator
            ->scalar('turno')
            ->maxLength('turno', 50)
            ->allowEmptyString('turno');

        $validator
            ->integer('num_alumnos')
            ->allowEmptyString('num_alumnos');

        $validator
            ->scalar('estatus')
            ->maxLength('estatus', 30)
            ->requirePresence('estatus', 'create')
            ->notEmptyString('estatus', 'Selecciona un estatus');

        $validator
            ->boolean('verificada')
            ->notEmptyString('verificada');

        $validator
            ->scalar('editorial_actual')
            ->maxLength('editorial_actual', 100)
            ->allowEmptyString('editorial_actual');

        $validator
            ->boolean('venta_montenegro')
            ->notEmptyString('venta_montenegro');

        $validator
            ->scalar('competencia')
            ->maxLength('competencia', 100)
            ->allowEmptyString('competencia');

        $validator
            ->date('fecha_decision')
            ->allowEmptyDate('fecha_decision');

        $validator
            ->integer('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id', 'El distribuidor es obligatorio');
        $validator
            ->scalar('cct')
            ->maxLength('cct', 20)
            ->requirePresence('cct', 'create')
            ->notEmptyString('cct', 'El CCT es obligatorio');

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
         $rules->add($rules->existsIn(['user_id'], 'Users'), [
        'errorField' => 'user_id',
        'message' => 'El usuario seleccionado no existe'
    ]);

        return $rules;
    }
}
