<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class SchoolMaterialsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('school_materials');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Schools',   ['foreignKey' => 'school_id']);
        $this->belongsTo('Materials', ['foreignKey' => 'material_id']);
    }
}