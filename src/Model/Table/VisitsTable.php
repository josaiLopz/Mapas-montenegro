<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class VisitsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('visits');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Schools', [
            'foreignKey' => 'school_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('school_id')
            ->requirePresence('school_id', 'create')
            ->notEmptyString('school_id');

        $validator
            ->integer('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id');

        $validator
            ->dateTime('scheduled_at')
            ->requirePresence('scheduled_at', 'create')
            ->notEmptyDateTime('scheduled_at');

        $validator
            ->scalar('status')
            ->maxLength('status', 20)
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

        $validator
            ->numeric('start_lat')
            ->allowEmptyString('start_lat');

        $validator
            ->numeric('start_lng')
            ->allowEmptyString('start_lng');

        $validator
            ->dateTime('started_at')
            ->allowEmptyDateTime('started_at');

        $validator
            ->dateTime('completed_at')
            ->allowEmptyDateTime('completed_at');

        $validator
            ->scalar('evidence_file')
            ->maxLength('evidence_file', 255)
            ->allowEmptyString('evidence_file');

        $validator
            ->scalar('notes')
            ->maxLength('notes', 1000)
            ->allowEmptyString('notes');

        $validator
            ->scalar('completion_notes')
            ->maxLength('completion_notes', 1000)
            ->allowEmptyString('completion_notes');

        return $validator;
    }
}
