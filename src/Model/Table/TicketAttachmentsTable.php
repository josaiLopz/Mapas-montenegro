<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class TicketAttachmentsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('ticket_attachments');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Tickets', [
            'foreignKey' => 'ticket_id',
        ]);

        $this->belongsTo('TicketUpdates', [
            'foreignKey' => 'ticket_update_id',
        ]);

        $this->belongsTo('Creators', [
            'className' => 'Users',
            'foreignKey' => 'created_by',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('ticket_id')
            ->requirePresence('ticket_id', 'create')
            ->notEmptyString('ticket_id');

        $validator
            ->integer('ticket_update_id')
            ->allowEmptyString('ticket_update_id');

        $validator
            ->integer('created_by')
            ->requirePresence('created_by', 'create')
            ->notEmptyString('created_by');

        $validator
            ->scalar('original_name')
            ->maxLength('original_name', 255)
            ->requirePresence('original_name', 'create')
            ->notEmptyString('original_name');

        $validator
            ->scalar('stored_name')
            ->maxLength('stored_name', 255)
            ->requirePresence('stored_name', 'create')
            ->notEmptyString('stored_name');

        $validator
            ->scalar('relative_path')
            ->maxLength('relative_path', 255)
            ->requirePresence('relative_path', 'create')
            ->notEmptyString('relative_path');

        $validator
            ->scalar('mime_type')
            ->maxLength('mime_type', 120)
            ->allowEmptyString('mime_type');

        $validator
            ->integer('file_size')
            ->allowEmptyString('file_size');

        $validator
            ->scalar('extension')
            ->maxLength('extension', 15)
            ->allowEmptyString('extension');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['ticket_id'], 'Tickets'), ['errorField' => 'ticket_id']);
        $rules->add($rules->existsIn(['created_by'], 'Creators'), ['errorField' => 'created_by']);

        return $rules;
    }
}
