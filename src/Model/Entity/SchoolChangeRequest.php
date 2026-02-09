<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SchoolChangeRequest Entity
 *
 * @property int $id
 * @property int $school_id
 * @property int $requested_by
 * @property int|null $approved_by
 * @property string $type
 * @property string $payload
 * @property string $status
 * @property string|null $comment
 * @property \Cake\I18n\DateTime|null $approved_at
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $updated
 *
 * @property \App\Model\Entity\School $school
 */
class SchoolChangeRequest extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'school_id' => true,
        'requested_by' => true,
        'approved_by' => true,
        'type' => true,
        'payload' => true,
        'status' => true,
        'comment' => true,
        'approved_at' => true,
        'created' => true,
        'updated' => true,
        'school' => true,
         'requester' => true,
        'approver' => true,
    ];
}
