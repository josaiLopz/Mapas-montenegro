<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class MetasTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('metas');
        $this->addBehavior('Timestamp');
    }
}