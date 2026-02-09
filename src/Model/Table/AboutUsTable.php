<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AboutUs Model
 *
 * @method \App\Model\Entity\AboutU newEmptyEntity()
 * @method \App\Model\Entity\AboutU newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\AboutU> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AboutU get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\AboutU findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\AboutU patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\AboutU> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\AboutU|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\AboutU saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\AboutU>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\AboutU>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\AboutU>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\AboutU> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\AboutU>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\AboutU>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\AboutU>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\AboutU> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AboutUsTable extends Table
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

        $this->setTable('about_us');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('content')
            ->requirePresence('content', 'create')
            ->notEmptyString('content');

        $validator
            ->scalar('image')
            ->maxLength('image', 255)
            ->requirePresence('image', 'create')
            ->notEmptyFile('image');

        $validator
            ->boolean('active')
            ->notEmptyString('active');

        return $validator;
    }
}
