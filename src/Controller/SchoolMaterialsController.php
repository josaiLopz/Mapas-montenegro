<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

class SchoolMaterialsController extends AppController
{
    public function index($school_id)
    {
        $this->request->allowMethod(['get']);

        $Schools = TableRegistry::getTableLocator()->get('Schools');
        $Materials = TableRegistry::getTableLocator()->get('Materials');
        $SchoolsMaterials = TableRegistry::getTableLocator()->get('SchoolsMaterials');

        // 1️⃣ Escuela
        $school = $Schools->find()
            ->where(['Schools.id' => (int)$school_id])
            ->first();

        if (!$school) {
            throw new NotFoundException('Escuela no encontrada');
        }

        // 2️⃣ Nivel de la escuela  ⚠️ cambia "nivel" si tu campo se llama distinto
        $nivelEscuela = trim((string)($school->tipo ?? ''));
        $nivelEscuela = mb_strtolower($nivelEscuela);

        // 3️⃣ Materiales SOLO del nivel de la escuela
        $materialsQuery = $Materials->find()
            ->where(['Materials.activo' => true]);

        if ($nivelEscuela !== '') {
            $materialsQuery->where([
                'LOWER(Materials.nivel) =' => $nivelEscuela
            ]);
        }

        $materials = $materialsQuery
            ->orderAsc('Materials.nombre')
            ->all();

        // 4️⃣ Materiales ya existentes en la escuela
        $existing = $SchoolsMaterials->find()
            ->where(['school_id' => (int)$school_id])
            ->all()
            ->indexBy('material_id')
            ->toArray();

        // 5️⃣ Crear filas faltantes SOLO de ese nivel
        $toSave = [];
        foreach ($materials as $m) {
            if (!isset($existing[$m->id])) {
                $toSave[] = $SchoolsMaterials->newEntity([
                    'school_id' => (int)$school_id,
                    'material_id' => (int)$m->id,
                    'proyeccion_venta' => 0,
                    'cierre_2026' => 0,
                ]);
            }
        }

        if ($toSave) {
            $SchoolsMaterials->saveMany($toSave);
        }

        // 6️⃣ Rows finales SOLO del nivel de la escuela
        $rows = $SchoolsMaterials->find()
            ->contain(['Materials'])
            ->where([
                'SchoolsMaterials.school_id' => (int)$school_id,
                'LOWER(Materials.nivel) =' => $nivelEscuela
            ])
            ->orderAsc('Materials.nombre')
            ->all();

        $this->set(compact('school', 'rows'));
    }

    public function updateCell()
    {
        $this->request->allowMethod(['post', 'patch']);
        $this->autoRender = false;

        $SchoolsMaterials = TableRegistry::getTableLocator()->get('SchoolsMaterials');

        $id    = $this->request->getData('id');
        $field = $this->request->getData('field');
        $value = $this->request->getData('value');

        if (!in_array($field, ['proyeccion_venta', 'cierre_2026'], true)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['ok' => false, 'msg' => 'Campo inválido']));
        }

        $row = $SchoolsMaterials->get((int)$id);

        $value = trim((string)$value);
        $value = str_replace(',', '.', $value);

        if ($value === '') {
            $num = 0.00;
        } elseif (is_numeric($value)) {
            $num = (float)$value;
        } else {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['ok' => false, 'msg' => 'Valor inválido']));
        }

        $row->set($field, $num);

        if ($SchoolsMaterials->save($row)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['ok' => true]));
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['ok' => false, 'msg' => 'No se pudo guardar']));
    }
}
