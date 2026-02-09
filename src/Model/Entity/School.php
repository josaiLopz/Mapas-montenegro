<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * School Entity
 *
 * @property int $id
 * @property string $nombre
 * @property string $estado
 * @property string $municipio
 * @property int|null $distribuidor_id
 * @property string|null $tipo
 * @property string|null $sector
 * @property string|null $turno
 * @property int|null $num_alumnos
 * @property bool $estatus
 * @property bool $verificada
 * @property string|null $editorial_actual
 * @property bool $venta_montenegro
 * @property string|null $competencia
 * @property \Cake\I18n\Date|null $fecha_decision
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\User $distribuidor
 */
class School extends Entity
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
        '*' => true,
        'id' => false,
        'nombre' => true,
        'estado' => true,
        'municipio' => true,
        'distribuidor_id' => true,
        'tipo' => true,
        'sector' => true,
        'turno' => true,
        'num_alumnos' => true,
        'estatus' => true,
        'verificada' => true,
        'editorial_actual' => true,
        'venta_montenegro' => true,
        'competencia' => true,
        'fecha_decision' => true,
        'created' => true,
        'modified' => true,
        'distribuidor' => true,
    ];
}
