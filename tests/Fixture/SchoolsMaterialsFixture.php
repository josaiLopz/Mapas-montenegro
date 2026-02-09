<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SchoolsMaterialsFixture
 */
class SchoolsMaterialsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'school_id' => 1,
                'material_id' => 1,
                'proyeccion_venta' => 1.5,
                'cierre_2026' => 1.5,
                'created' => 1769714472,
                'updated' => 1769714472,
            ],
        ];
        parent::init();
    }
}
