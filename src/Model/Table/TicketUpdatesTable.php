<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class TicketUpdatesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('ticket_updates');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Tickets', [
            'foreignKey' => 'ticket_id',
        ]);

        $this->belongsTo('Creators', [
            'className' => 'Users',
            'foreignKey' => 'created_by',
        ]);

        $this->hasMany('TicketAttachments', [
            'foreignKey' => 'ticket_update_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('ticket_id')
            ->requirePresence('ticket_id', 'create')
            ->notEmptyString('ticket_id');

        $validator
            ->integer('created_by')
            ->requirePresence('created_by', 'create')
            ->notEmptyString('created_by');

        $validator
            ->scalar('update_type')
            ->maxLength('update_type', 30)
            ->requirePresence('update_type', 'create')
            ->notEmptyString('update_type');

        $validator
            ->scalar('status_from')
            ->maxLength('status_from', 30)
            ->allowEmptyString('status_from');

        $validator
            ->scalar('status_to')
            ->maxLength('status_to', 30)
            ->allowEmptyString('status_to');

        $validator
            ->scalar('message')
            ->allowEmptyString('message');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['ticket_id'], 'Tickets'), ['errorField' => 'ticket_id']);
        $rules->add($rules->existsIn(['created_by'], 'Creators'), ['errorField' => 'created_by']);

        return $rules;
    }
}
