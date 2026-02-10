<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class KbChunksTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('kb_chunks');
        $this->setPrimaryKey('id');

        $this->belongsTo('KbArticles', [
            'foreignKey' => 'article_id',
            'joinType' => 'INNER',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        return $validator
            ->integer('article_id')
            ->notEmptyString('chunk_text');
    }
}