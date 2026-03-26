<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class PasswordResetTokensTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('password_reset_tokens'); // nombre real en BD
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp'); // usa created/modified si existen

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        return $validator
            ->integer('user_id')->requirePresence('user_id', 'create')->notEmptyString('user_id')
            ->scalar('token')->maxLength('token', 64)->requirePresence('token', 'create')->notEmptyString('token')
            ->dateTime('expires_at')->requirePresence('expires_at', 'create')->notEmptyDateTime('expires_at');
    }
}