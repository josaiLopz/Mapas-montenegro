<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MunicipiosFixture
 */
class MunicipiosFixture extends TestFixture
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
                'estado_id' => 1,
                'nombre' => 'Lorem ipsum dolor sit amet',
                'created' => '2026-01-20 21:13:26',
                'modified' => '2026-01-20 21:13:26',
            ],
        ];
        parent::init();
    }
}
