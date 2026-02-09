<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AboutUsFixture
 */
class AboutUsFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'about_us';
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
                'title' => 'Lorem ipsum dolor sit amet',
                'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'image' => 'Lorem ipsum dolor sit amet',
                'active' => 1,
                'created' => '2026-01-19 16:36:24',
                'updated' => '2026-01-19 16:36:24',
            ],
        ];
        parent::init();
    }
}
