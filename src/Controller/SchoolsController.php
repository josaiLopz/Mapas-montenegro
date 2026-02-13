<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;
class SchoolsController extends AppController
{
     public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

         if ($this->components()->has('Authentication')) {
       if ($this->components()->has('FormProtection')) {
            $this->FormProtection->setConfig('unlockedActions', [
                'guardarCoordenadas',
                'updateModal',
                
            ]);
        }
    }
    }
    public function index()
    {
        $schools = $this->paginate($this->Schools->find()->contain(['Users', 'Estados', 'Municipios']));
        $this->set(compact('schools'));
    }

    public function view($id = null)
    {
        $school = $this->Schools->get($id, ['contain' => ['Users', 'Estados', 'Municipios']]);
        $this->set(compact('school'));
    }

   public function add()
{
    $school = $this->Schools->newEmptyEntity();

    if ($this->request->is('post')) {

        $school = $this->Schools->patchEntity($school, $this->request->getData());
        if ($this->Schools->save($school)) {
            $this->Flash->success('Escuela creada correctamente');
            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error('Error al crear la escuela');
    }
//  debug($school->getErrors());
//     die('Stop here to see validation errors.');
    $users = $this->Schools->Users->find('list', [
        'keyField' => 'id',
        'valueField' => function ($u) {
            return $u->name . ' (' . $u->email . ')';
        }
    ])->order(['name' => 'ASC']);

    $estatus = [
        'noAtendida' => 'No atendida',
        'escuelaPromocion'  => 'Escuela en promoción',
        'ventaConfirmada'  => 'Venta confirmada',
        'prohibicion' => 'Prohibicion',
        'ventaMarcas' => 'Venta otras marcas'
    ];
    $estados = $this->Schools->Estados->find('list', [
        'keyField' => 'id',
        'valueField' => 'nombre'
    ])->order(['nombre' => 'ASC'])->toArray();

    $municipios = $this->Schools->Municipios->find('list', [
        'keyField' => 'id',
        'valueField' => 'nombre'
    ])->order(['nombre' => 'ASC'])->toArray();
    $this->set(compact('school', 'users', 'estatus', 'estados', 'municipios'));
}

    public function edit($id = null)
    {
        $school = $this->Schools->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $school = $this->Schools->patchEntity($school, $this->request->getData());
            if ($this->Schools->save($school)) {
                $this->Flash->success('Escuela actualizada correctamente');
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('Error al actualizar la escuela');
        }

        if ($this->request->getQuery('layout') === 'ajax') {
            $this->viewBuilder()->setLayout('ajax');
        }

         $users = $this->Schools->Users->find('list', [
        'keyField' => 'id',
        'valueField' => function ($u) {
            return $u->name . ' (' . $u->email . ')';
        }
    ])->order(['name' => 'ASC']);
        $estados = $this->Schools->Estados->find('list');

            $municipios = [];
            if ($school->estado_id) {
                $municipios = $this->Schools->Municipios
                    ->find('list')
                    ->where(['estado_id' => $school->estado_id]);
            }
        $this->set(compact('school','users', 'estados', 'municipios'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $school = $this->Schools->get($id);
        if ($this->Schools->delete($school)) {
            $this->Flash->success('Escuela eliminada correctamente');
        } else {
            $this->Flash->error('Error al eliminar la escuela');
        }
        return $this->redirect(['action' => 'index']);
    }

    public function import()
    {
        if ($this->request->is('post')) {
            $file = $this->request->getData('csv_file');
            if ($file && $file->getClientMediaType() === 'text/csv') {
                $this->processCsv($file->getStream()->getMetadata('uri'));
            } else {
                $this->Flash->error('Por favor sube un archivo csv válido');
            }
            }
    }
    
        protected function processCsv($uri)
        {
            $handle = fopen($uri, 'r');
            if (!$handle) {
                $this->Flash->error('No se pudo abrir el archivo');
                return;
            }

            $header = fgetcsv($handle);
            $saved = 0;
            $errors = [];

            while (($row = fgetcsv($handle)) !== false) {

                $data = array_combine($header, $row);

                if (!empty($data['estado'])) {
                    $estado = $this->Schools->Estados
                        ->find()
                        ->where(['nombre' => $data['estado']])
                        ->first();

                    if ($estado) {
                        $data['estado_id'] = $estado->id;
                    } else {
                        $errors[] = [
                            'row' => $data,
                            'errors' => ['estado' => 'Estado no encontrado']
                        ];
                        continue;
                    }
                }

                if (!empty($data['municipio'])) {
                    $municipio = $this->Schools->Municipios
                        ->find()
                        ->where([
                            'nombre' => $data['municipio'],
                            'estado_id' => $data['estado_id']
                        ])
                        ->first();

                    if ($municipio) {
                        $data['municipio_id'] = $municipio->id;
                    } else {
                        $errors[] = [
                            'row' => $data,
                            'errors' => ['municipio' => 'Municipio no encontrado para el estado']
                        ];
                        continue;
                    }
                }
                unset($data['estado'], $data['municipio']);
                $data['verificada'] = !empty($data['verificada']) ? 1 : 0;
                $data['venta_montenegro'] = !empty($data['venta_montenegro']) ? 1 : 0;

                $school = $this->Schools->newEntity($data);

                if ($school->hasErrors()) {
                    $errors[] = [
                        'row' => $data,
                        'errors' => $school->getErrors()
                    ];
                    continue;
                }

                if ($this->Schools->save($school)) {
                    $saved++;
                } else {
                    $errors[] = [
                        'row' => $data,
                        'errors' => ['save' => 'Error al guardar la escuela']
                    ];
                }
            }

            fclose($handle);

            $this->set(compact('saved', 'errors'));
            $this->render('import_result');
        }

        

        public function downloadTemplate()
        {
            $filename = 'plantilla_escuelas.csv';
            $header = [
                'nombre','cct','estado_id','municipio_id','user_id','tipo','sector','turno','num_alumnos','estatus','verificada','editorial_actual','venta_montenegro','competencia',
                'fecha_decision','lat','lng','grupos','nombre_contacto','telefono_contacto','correo_contacto','notas','presupuesto'
            ];


            $filePath = TMP . $filename;
            $handle = fopen($filePath, 'w');

            fputcsv($handle, $header);

            fputcsv($handle, [
                'Escuela Ejemplo','CCT123456','1','1','10','Vespertino','Publico','Matutino','500','No atendida','1','Montenegro','1','Santillana',
                '2026-01-22','','','','Juan Pérez','3312345678','contacto@escuela.com','Escuela con alta demanda','150000'
            ]);

            fclose($handle);

            $response = $this->response->withFile(
                $filePath,
                ['download' => true, 'name' => $filename]
            );

            return $response;
        }

        public function transfer()
        {
            if ($this->request->is('post')) {

                $data = $this->request->getData();
                $conditions = [
                    'user_id' => $data['user_origen']
                ];
                if (!empty($data['estado_id'])) {
                    $conditions['estado_id'] = $data['estado_id'];
                }
                if  (!empty($data['municipio_id'])){
                    $conditions['municipio_id'] = $data['municipio_id'];
                }
                if  (!empty($data['tipo'])) {
                    $conditions['tipo'] = $data['tipo'];
                    }
                if  (!empty($data['sector'])) {
                    $conditions['sector'] = $data['sector'];
                    }
                if  (!empty($data['turno'])) {
                    $conditions['turno'] = $data['turno'];
                }
                $updated = $this->Schools->updateAll(
                    ['user_id' => $data['user_destino']],
                    $conditions
                );

                $this->Flash->Success("Esculas transferidas correctamente");
                return $this->redirect(['action' => 'index']);
            }
            $users = $this->Schools->Users->find('list',[
                'keyField' => 'id',
                'valueField' => 'name'
            ])->order(['name' => 'ASC'])->toArray();
            $this->set(compact('users'));
        }

        public function estadosPorUsuario($userId)
        {
            $this->request->allowMethod(['get']);

            $estados = $this->Schools->find()
                ->select([
                    'Estados.id',
                    'Estados.nombre'
                ])
                ->distinct(['Estados.id'])
                ->contain(['Estados'])
                ->where(['Schools.user_id' => $userId])
                ->order(['Estados.nombre' => 'ASC'])
                ->all()
                ->combine('estado.id', 'estado.nombre')
                ->toArray();

            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'estados' => $estados
                ]));
        }




        public function municipiosPorEstado($estadoId)
        {
            $this->request->allowMethod(['get']);

            $municipios = $this->Schools->Municipios
                ->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'nombre'
                ])
                ->where(['estado_id' => $estadoId])
                ->order(['nombre' => 'ASC'])
                ->toArray();

            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'municipios' => $municipios
                ]));
                
        }

        public function estadosDisponibles()
        {
            $this->request->allowMethod(['get']);

            $estados = $this->Schools->Estados
                ->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'nombre'
                ])
                ->matching('Schools', function ($q) {
                    return $q->where(['Schools.user_id IS' => null]);
                })
                ->distinct(['Estados.id'])
                ->order(['Estados.nombre' => 'ASC'])
                ->toArray();

            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'estados' => $estados
                ]));
        }


       public function municipiosDisponibles($estadoId)
        {
            $this->request->allowMethod(['get']);

            $municipios = $this->Schools->Municipios
                ->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'nombre'
                ])
                ->matching('Schools', function ($q) use ($estadoId) {
                    return $q->where([
                        'Schools.estado_id' => $estadoId,
                        'Schools.user_id IS' => null
                    ]);
                })
                ->distinct(['Municipios.id'])
                ->order(['Municipios.nombre' => 'ASC'])
                ->toArray();

            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'municipios' => $municipios
                ]));
        }
        public function contarDisponibles()
        {
            $this->request->allowMethod(['post']);
            $data = $this->request->getData();

            $conditions = [
                'user_id IS' => null
            ];

            if (!empty($data['estado_id'])) {
                $conditions['estado_id'] = $data['estado_id'];
            }
            if (!empty($data['municipio_id'])) {
                $conditions['municipio_id'] = $data['municipio_id'];
            }
            if (!empty($data['tipo'])) {
                $conditions['tipo'] = $data['tipo'];
            }
            if (!empty($data['turno'])) {
                $conditions['turno'] = $data['turno'];
            }
            if (!empty($data['sector'])) {
                $conditions['sector'] = $data['sector'];
            }

            $total = $this->Schools->find()
                ->where($conditions)
                ->count();

            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'total' => $total
                ]));
        }

        public function asignar()
        {
            if ($this->request->is('post')) {

                $data = $this->request->getData();

                $conditions = [
                    'user_id IS' => null
                ];

                if (!empty($data['estado_id'])) {
                    $conditions['estado_id'] = $data['estado_id'];
                }
                if (!empty($data['municipio_id'])) {
                    $conditions['municipio_id'] = $data['municipio_id'];
                }
                if (!empty($data['tipo'])) {
                    $conditions['tipo'] = $data['tipo'];
                }
                if (!empty($data['turno'])) {
                    $conditions['turno'] = $data['turno'];
                }
                if (!empty($data['sector'])) {
                    $conditions['sector'] = $data['sector'];
                }

                $this->Schools->updateAll(
                    ['user_id' => $data['user_destino']],
                    $conditions
                );

                $this->Flash->success('Escuelas asignadas correctamente');
                return $this->redirect(['action' => 'index']);
            }

            $users = $this->Schools->Users->find('list')
                ->order(['name' => 'ASC'])
                ->toArray();

            $this->set(compact('users'));
        }

        public function filtros()
        {
            $mode = 'admin';
        $this->set(compact('mode'));
            $users = $this->Schools->Users->find('list', [
                'keyField' => 'id',
                'valueField' => function ($u) {
                    return $u->name . ' (' . $u->email . ')';
                }
            ])->order(['name' => 'ASC'])->toArray();

            $estados = $this->Schools->Estados->find('list', [
                'keyField' => 'id',
                'valueField' => 'nombre'
            ])->order(['nombre' => 'ASC'])->toArray();

            // Combos como en la UI (Cualquiera)
            $tipos = [
                '' => 'Cualquiera',
                'Preescolar' => 'Preescolar',
                'Primaria'   => 'Primaria',
                'Secundaria' => 'Secundaria',
            ];

            $sectores = [
                '' => 'Cualquiera',
                'Publico' => 'Publico',
                'Privado' => 'Privado',
            ];

            $turnos = [
                '' => 'Cualquiera',
                'Matutino'   => 'Matutino',
                'Vespertino' => 'Vespertino',
                'Nocturno'   => 'Nocturno',
            ];

            $estatus = [
                ''          => 'Cualquiera',
                'noAtendida' => 'No atendida',
                'escuelaPromocion'  => 'Escuela en promoción',
                'ventaConfirmada'  => 'Venta confirmada',
                'prohibicion' => 'Prohibicion',
                'ventaMarcas' => 'Venta otras marcas'
            ];

            $siNo = [
                ''  => 'Cualquiera',
                '1' => 'Sí',
                '0' => 'No'
            ];

            $this->set(compact('users', 'estados', 'tipos', 'sectores', 'turnos', 'estatus', 'siNo'));
        }


        private function aplicarFiltros($query, array $data)
        {
            if (!empty($data['nombre'])) {
                $query->where(['Schools.nombre LIKE' => '%' . $data['nombre'] . '%']);
            }

            if (!empty($data['estado_id'])) {
                $query->where(['Schools.estado_id' => (int)$data['estado_id']]);
            }

            if (!empty($data['municipio_id'])) {
                $query->where(['Schools.municipio_id' => (int)$data['municipio_id']]);
            }

            // Distribuidor = Users => user_id
            if (!empty($data['user_id'])) {
                $query->where(['Schools.user_id' => (int)$data['user_id']]);
            }

            if (!empty($data['tipo'])) {
                $query->where(['Schools.tipo' => $data['tipo']]);
            }

            if (!empty($data['turno'])) {
                $query->where(['Schools.turno' => $data['turno']]);
            }

            if (!empty($data['sector'])) {
                $query->where(['Schools.sector' => $data['sector']]);
            }

            if (!empty($data['cct'])) {
                $query->where(['Schools.cct LIKE' => '%' . $data['cct'] . '%']);
            }

            if (!empty($data['estatus'])) {
                $query->where(['Schools.estatus' => $data['estatus']]);
            }

            // IMPORTANTE: select Sí/No/Cualquiera (no usar empty)
            if (array_key_exists('verificada', $data) && $data['verificada'] !== '' && $data['verificada'] !== null) {
                $query->where(['Schools.verificada' => (int)$data['verificada']]);
            }

            if (!empty($data['editorial_actual'])) {
                $query->where(['Schools.editorial_actual LIKE' => '%' . $data['editorial_actual'] . '%']);
            }

            if (array_key_exists('venta_montenegro', $data) && $data['venta_montenegro'] !== '' && $data['venta_montenegro'] !== null) {
                $query->where(['Schools.venta_montenegro' => (int)$data['venta_montenegro']]);
            }

            if (!empty($data['competencia'])) {
                $query->where(['Schools.competencia LIKE' => '%' . $data['competencia'] . '%']);
            }

            if (!empty($data['fecha_decision'])) {
                $query->where(['Schools.fecha_decision' => $data['fecha_decision']]);
            }

            // NUEVO: alumnos como "0, 500, -1000, +2000"
            if (!empty($data['alumnos_rango'])) {
                $query = $this->aplicarFiltroAlumnos($query, $data['alumnos_rango']);
            }

            return $query;
        }


        private function aplicarFiltroAlumnos($query, ?string $rawInput)
        {
            $rawInput = trim((string)$rawInput);
            if ($rawInput === '') return $query;

            // ejemplo: "0, 500, -1000, +2000"
            $raw = str_replace(' ', '', $rawInput);
            $parts = array_filter(explode(',', $raw));

            $ors = [];
            foreach ($parts as $p) {
                if ($p === '') continue;

                if ($p[0] === '+') {
                    $n = (int)substr($p, 1);
                    if ($n > 0) $ors[] = ['Schools.num_alumnos >=' => $n];
                } elseif ($p[0] === '-') {
                    $n = (int)substr($p, 1);
                    if ($n > 0) $ors[] = ['Schools.num_alumnos <=' => $n];
                } else {
                    $n = (int)$p;
                    if ($n >= 0) $ors[] = ['Schools.num_alumnos' => $n];
                }
            }

            if ($ors) {
                $query->where(['OR' => $ors]);
            }

            return $query;
        }


    public function contarFiltrado()
    {
        $this->request->allowMethod(['post']);

        $data = $this->request->getData();
        $identity = $this->request->getAttribute('identity');
        $currentUserId = $identity ? (int)$identity->getIdentifier() : null;
            if (($data['mode'] ?? 'admin') === 'mis') {
        $data['user_id'] = $currentUserId;
        }

        $query = $this->Schools->find()->contain(['Users','Estados','Municipios']);
        $query = $this->aplicarFiltros($query, $data);

        // $data['user_id'] = $currentUserId;
        // $query = $this->Schools->find()
        //     ->contain(['Users','Estados','Municipios']);

        // $query = $this->aplicarFiltros($query, $data);

        $total = $query->count();

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode([
                'total' => $total
            ]));
    }

    public function filtrarSchools()
    {
        $this->request->allowMethod(['post']);

        $data = $this->request->getData();
         $identity = $this->request->getAttribute('identity');
        $currentUserId = $identity ? (int)$identity->getIdentifier() : null;
    if (($data['mode'] ?? 'admin') === 'mis') {
        $data['user_id'] = $currentUserId;
    }        $limit  = 1000;
        $offset = isset($data['offset']) ? (int)$data['offset'] : 0;

        $query = $this->Schools->find()
            ->contain(['Users','Estados','Municipios']);

        $query = $this->aplicarFiltros($query, $data);

        $total = $query->count();

        $rows = $query
            ->order(['Schools.nombre' => 'ASC'])
            ->limit($limit)
            ->offset($offset)
            ->all()
            ->map(function($school) {
                return [
                    'id' => $school->id,
                    'nombre' => $school->nombre,
                    'estado' => $school->estado->nombre ?? '',
                    'municipio' => $school->municipio->nombre ?? '',
                    'user' => $school->user->name ?? '',
                    'tipo' => $school->tipo,
                    'sector' => $school->sector,
                    'turno' => $school->turno,
                    'num_alumnos' => $school->num_alumnos,
                    'cct' => $school->cct,
                    'lat' => $school->lat,
                    'lng' => $school->lng,
                    'grupos' => $school->grupos,
                    'nombre_contacto' => $school->nombre_contacto,
                    'telefono_contacto' => $school->telefono_contacto,
                    'correo_contacto' => $school->correo_contacto,
                    'presupuesto' => $school->presupuesto,
                    'notas' => $school->notas,
                    'estatus' => $school->estatus,
                    'verificada' => $school->verificada ? 'Sí' : 'No',
                    'editorial_actual' => $school->editorial_actual,
                    'venta_montenegro' => $school->venta_montenegro ? 'Sí' : 'No',
                    'competencia' => $school->competencia,
                    'fecha_decision' => $school->fecha_decision ? $school->fecha_decision->format('Y-m-d') : ''
                ];
            })
            ->toArray();

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode([
                'total' => $total,
                'rows'  => $rows
            ]));
    }

    public function guardarCoordenadas()
        {
            $this->request->allowMethod(['post']);

            $data = $this->request->getData();

            $id  = (int)($data['id'] ?? 0);
            $lat = $data['lat'] ?? null;
            $lng = $data['lng'] ?? null;

            if (!$id || !is_numeric($lat) || !is_numeric($lng)) {
                return $this->response
                    ->withStatus(400)
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'ok' => false,
                        'message' => 'Datos inválidos (id/lat/lng).',
                        'data' => $data,
                    ]));
            }

            try {
                $school = $this->Schools->get($id);
            } catch (\Exception $e) {
                return $this->response
                    ->withStatus(404)
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'ok' => false,
                        'message' => 'Escuela no encontrada',
                    ]));
            }

            $school->lat = (float)$lat;
            $school->lng = (float)$lng;

            // Si hay validaciones que impiden guardar, aquí te va a salir
            if ($school->hasErrors()) {
                return $this->response
                    ->withStatus(422)
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'ok' => false,
                        'message' => 'Errores de validación',
                        'errors' => $school->getErrors(),
                    ]));
            }

            if (!$this->Schools->save($school)) {
                return $this->response
                    ->withStatus(422)
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'ok' => false,
                        'message' => 'No se pudo guardar',
                        'errors' => $school->getErrors(),
                    ]));
            }

            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'ok' => true,
                    'id' => $school->id,
                    'lat' => $school->lat,
                    'lng' => $school->lng,
                ]));
        }

    public function misFiltros()
        {
            $mode = 'mis';
        $this->set(compact('mode'));
            $identity = $this->request->getAttribute('identity');
            if (!$identity) {
                // si no está logueado, redirige (ajusta a tu auth)
                return $this->redirect(['controller' => 'Users', 'action' => 'login']);
            }

            $currentUserId = (int)$identity->getIdentifier();

            // Usuario (solo él)
            $u = $this->Schools->Users->get($currentUserId, ['fields' => ['id','name','email']]);
            $users = [
                $u->id => $u->name . ' (' . $u->email . ')'
            ];

            // Estados donde él tiene escuelas
            $estados = $this->Schools->find()
                ->select(['Estados.id', 'Estados.nombre'])
                ->distinct(['Estados.id'])
                ->contain(['Estados'])
                ->where(['Schools.user_id' => $currentUserId])
                ->order(['Estados.nombre' => 'ASC'])
                ->all()
                ->combine('estado.id', 'estado.nombre')
                ->toArray();

            // combos UI
            $tipos = [ ''=>'Cualquiera', 'Preescolar'=>'Preescolar', 'Primaria'=>'Primaria', 'Secundaria'=>'Secundaria' ];
            $sectores = [ ''=>'Cualquiera', 'Publico'=>'Publico', 'Privado'=>'Privado' ];
            $turnos = [ ''=>'Cualquiera', 'Matutino'=>'Matutino', 'Vespertino'=>'Vespertino', 'Nocturno'=>'Nocturno' ];
            $estatus = [ ''=>'Cualquiera', 'noAtendida'=>'No atendida', 'escuelaPromocion'=>'Escuela en promoción', 'ventaConfirmada'=>'Venta confirmada', 'prohibicion'=>'Prohibicion', 'ventaMarcas'=>'Venta otras marcas'];
            $siNo = [ ''=>'Cualquiera', '1'=>'Sí', '0'=>'No' ];

            // banderas para la vista
            $restrictedUser = true;

            $this->set(compact(
                'users','estados','tipos','sectores','turnos','estatus','siNo',
                'restrictedUser','currentUserId'
            ));

            // reusar la misma vista filtros.php
            $this->render('filtros');
        }

        public function editModal($id = null)
        {
            $school = $this->Schools->get($id);

            // carga combos igual que en edit()
            $users = $this->Schools->Users->find('list', [
                'keyField' => 'id',
                'valueField' => function ($u) {
                    return $u->name . ' (' . $u->email . ')';
                }
            ])->order(['name' => 'ASC']);

            $estados = $this->Schools->Estados->find('list');

            $municipios = [];
            if ($school->estado_id) {
                $municipios = $this->Schools->Municipios
                    ->find('list')
                    ->where(['estado_id' => $school->estado_id]);
            }

            // layout liviano para iframe
            $this->viewBuilder()->setLayout('ajax');

            $this->set(compact('school', 'users', 'estados', 'municipios'));
        }
        public function updateModal($id = null)
        {
            $this->request->allowMethod(['post', 'put', 'patch']);

            try {
                $school = $this->Schools->get((int)$id);
            } catch (\Exception $e) {
                return $this->response
                    ->withStatus(404)
                    ->withType('application/json')
                    ->withStringBody(json_encode(['ok' => false, 'message' => 'Escuela no encontrada']));
            }

            $school = $this->Schools->patchEntity($school, $this->request->getData());

            if ($school->hasErrors()) {
                return $this->response
                    ->withStatus(422)
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'ok' => false,
                        'message' => 'Errores de validación',
                        'errors' => $school->getErrors(),
                    ]));
            }

            if (!$this->Schools->save($school)) {
                return $this->response
                    ->withStatus(422)
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'ok' => false,
                        'message' => 'No se pudo actualizar',
                        'errors' => $school->getErrors(),
                    ]));
            }

            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'ok' => true,
                    'message' => 'Escuela actualizada',
                    'school' => [
                        'id' => $school->id,
                        'nombre' => $school->nombre,
                        'cct' => $school->cct,
                        'tipo' => $school->tipo,
                        'turno' => $school->turno,
                        'sector' => $school->sector,
                        'num_alumnos' => $school->num_alumnos,
                        'grupos' => $school->grupos,
                        'nombre_contacto' => $school->nombre_contacto,
                        'telefono_contacto' => $school->telefono_contacto,
                        'notas' => $school->notas,
                        'editorial_actual' => $school->editorial_actual,
                        'venta_montenegro' => $school->venta_montenegro ? 'Sí' : 'No',
                        'competencia' => $school->competencia,
                        'presupuesto' => $school->presupuesto,
                        'verificada' => $school->verificada ? 'Sí' : 'No',
                    ]
                ]));
        }
}
