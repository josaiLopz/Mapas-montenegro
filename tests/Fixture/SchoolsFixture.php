<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SchoolsFixture
 */
class SchoolsFixture extends TestFixture
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
                'nombre' => 'Lorem ipsum dolor sit amet',
                'estado' => 'Lorem ipsum dolor sit amet',
                'municipio' => 'Lorem ipsum dolor sit amet',
                'distribuidor_id' => 1,
                'tipo' => 'Lorem ipsum dolor sit amet',
                'sector' => 'Lorem ipsum dolor sit amet',
                'turno' => 'Lorem ipsum dolor sit amet',
                'num_alumnos' => 1,
                'estatus' => 1,
                'verificada' => 1,
                'editorial_actual' => 'Lorem ipsum dolor sit amet',
                'venta_montenegro' => 1,
                'competencia' => 'Lorem ipsum dolor sit amet',
                'fecha_decision' => '2026-01-19',
                'created' => '2026-01-19 22:07:44',
                'modified' => '2026-01-19 22:07:44',
            ],
        ];
        parent::init();
    }
}
