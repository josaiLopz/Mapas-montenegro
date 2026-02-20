<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class TicketsTable extends Table
{
    public const STATUS_NEW = 'nuevo';
    public const STATUS_IN_PROGRESS = 'en_proceso';
    public const STATUS_WAITING_USER = 'esperando_usuario';
    public const STATUS_RESOLVED = 'resuelto';
    public const STATUS_CLOSED = 'cerrado';

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('tickets');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Requesters', [
            'className' => 'Users',
            'foreignKey' => 'requested_by',
        ]);

        $this->belongsTo('Assignees', [
            'className' => 'Users',
            'foreignKey' => 'assigned_to',
        ]);

        $this->hasMany('TicketUpdates', [
            'foreignKey' => 'ticket_id',
            'dependent' => true,
        ]);

        $this->hasMany('TicketAttachments', [
            'foreignKey' => 'ticket_id',
            'dependent' => true,
        ]);

        $this->hasMany('TicketNotifications', [
            'foreignKey' => 'ticket_id',
            'dependent' => true,
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('folio')
            ->maxLength('folio', 30)
            ->allowEmptyString('folio');

        $validator
            ->scalar('title')
            ->maxLength('title', 180)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('description')
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        $validator
            ->scalar('type')
            ->maxLength('type', 30)
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->scalar('priority')
            ->maxLength('priority', 20)
            ->requirePresence('priority', 'create')
            ->notEmptyString('priority');

        $validator
            ->scalar('status')
            ->maxLength('status', 30)
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

        $validator
            ->integer('requested_by')
            ->requirePresence('requested_by', 'create')
            ->notEmptyString('requested_by');

        $validator
            ->integer('assigned_to')
            ->allowEmptyString('assigned_to');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['requested_by'], 'Requesters'), ['errorField' => 'requested_by']);
        $rules->add($rules->existsIn(['assigned_to'], 'Assignees'), ['errorField' => 'assigned_to']);

        return $rules;
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_NEW => 'Nuevo',
            self::STATUS_IN_PROGRESS => 'En proceso',
            self::STATUS_WAITING_USER => 'Esperando usuario',
            self::STATUS_RESOLVED => 'Resuelto',
            self::STATUS_CLOSED => 'Cerrado',
        ];
    }

    public static function typeLabels(): array
    {
        return [
            'error' => 'Error del sistema',
            'mejora' => 'Solicitud de mejora',
            'soporte' => 'Soporte funcional',
        ];
    }

    public static function priorityLabels(): array
    {
        return [
            'baja' => 'Baja',
            'media' => 'Media',
            'alta' => 'Alta',
            'critica' => 'Critica',
        ];
    }
}
