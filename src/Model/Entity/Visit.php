<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Visit Entity
 *
 * @property int $id
 * @property int $school_id
 * @property int $user_id
 * @property \Cake\I18n\FrozenTime $scheduled_at
 * @property string $status
 * @property float|null $start_lat
 * @property float|null $start_lng
 * @property \Cake\I18n\FrozenTime|null $started_at
 * @property \Cake\I18n\FrozenTime|null $completed_at
 * @property string|null $evidence_file
 * @property string|null $notes
 * @property string|null $completion_notes
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Visit extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
