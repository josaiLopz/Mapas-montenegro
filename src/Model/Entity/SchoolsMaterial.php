<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SchoolsMaterial Entity
 *
 * @property int $id
 * @property int $school_id
 * @property int $material_id
 * @property string $proyeccion_venta
 * @property string $cierre_2026
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $updated
 *
 * @property \App\Model\Entity\School $school
 * @property \App\Model\Entity\Material $material
 */
class SchoolsMaterial extends Entity
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
        'material_id' => true,
        'proyeccion_venta' => true,
        'cierre_2026' => true,
        'created' => true,
        'updated' => true,
        'school' => true,
        'material' => true,
         '*' => true,
        'id' => false,
    ];
}
