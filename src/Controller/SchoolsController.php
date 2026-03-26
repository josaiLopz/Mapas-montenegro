<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;
class SchoolsController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
    
        // Desbloquear acciones de la protección de formularios (Seguridad/CSRF)
        if ($this->components()->has('FormProtection')) {
            $this->FormProtection->setConfig('unlockedActions', [
                'guardarCoordenadas',
                'updateModal',
                'geocodificarLote',
            ]);
        }
    
        // Si usas el componente de Autenticación, permite estas acciones
        if ($this->components()->has('Authentication')) {
            $this->Authentication->addUnauthenticatedActions(['geocodificarLote', 'guardarCoordenadas']);
        }
    }
    public function geocodificar()
    {
        // Lista de estados para el selector
        $estados = $this->Schools->Estados->find('list', [
            'keyField'   => 'id',
            'valueField' => 'nombre',
        ])->order(['nombre' => 'ASC'])->toArray();
    
        $estadoSeleccionado = null;
    
        $this->set(compact('estados', 'estadoSeleccionado'));
    }
public function geocodificarLote()
{
    $this->request->allowMethod(['post', 'ajax']);

    // REEMPLAZO DE input(): CakePHP 4/5 parsea el JSON automáticamente en getData()
    $estadoId = (int)$this->request->getData('estado_id');

    if (!$estadoId) {
        return $this->response
            ->withStatus(400)
            ->withType('application/json')
            ->withStringBody(json_encode([
                'ok' => false,
                'message' => 'estado_id requerido o formato JSON inválido',
            ]));
    }

    $schools = $this->Schools->find()
        ->select([
            'Schools.id',
            'Schools.nombre',
            'Schools.cct',
            'Schools.tipo',
            'Schools.lat',
            'Schools.lng',
            'municipio_nombre' => 'Municipios.nombre', // Alias para evitar colisiones
        ])
        ->contain(['Municipios'])
        ->where(['Schools.estado_id' => $estadoId])
        ->order(['Schools.nombre' => 'ASC'])
        ->all()
        ->map(function ($s) {
            return [
                'id'        => $s->id,
                'nombre'    => $s->nombre,
                'cct'       => $s->cct ?? '',
                'tipo'      => $s->tipo ?? '',
                'municipio' => $s->municipio_nombre ?? '',
                'lat'       => $s->lat,
                'lng'       => $s->lng,
            ];
        })
        ->toArray();

    return $this->response
        ->withType('application/json')
        ->withStringBody(json_encode([
            'ok' => true,
            'schools' => $schools
        ]));
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
    
        // Detectar y saltar BOM UTF-8 si existe
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }
    
        // Detectar separador (tab o coma)
        $firstLine = fgets($handle);
        rewind($handle);
        // Saltar BOM de nuevo si existía
        $bom2 = fread($handle, 3);
        if ($bom2 !== "\xEF\xBB\xBF") {
            rewind($handle);
        }
    
        $separator = (substr_count($firstLine, "\t") >= substr_count($firstLine, ',')) ? "\t" : ',';
    
        $header = fgetcsv($handle, 0, $separator);
    
        // Limpiar header (quitar espacios y BOM residual)
        $header = array_map(fn($h) => trim(str_replace("\xEF\xBB\xBF", '', $h)), $header);
    
        $saved  = 0;
        $errors = [];
    
        // Cache para no repetir queries
        $cacheEstados    = [];
        $cacheMunicipios = [];
        $cacheUsers      = [];
    
        // Pre-cargar usuarios para búsqueda por nombre
        $todosUsers = $this->Schools->Users
            ->find()
            ->select(['id', 'name', 'apellido_paterno', 'apellido_materno'])
            ->all()
            ->toArray();
    
        while (($row = fgetcsv($handle, 0, $separator)) !== false) {
    
            if (count($row) !== count($header)) {
                $errors[] = ['row' => $row, 'errors' => ['csv' => 'Columnas no coinciden con el encabezado']];
                continue;
            }
    
            $data = array_combine($header, $row);
    
            // ── Convertir encoding a UTF-8 ───────────────────────────────────────
            array_walk($data, function (&$value) {
                if (!is_string($value) || $value === '') return;
                if (!mb_check_encoding($value, 'UTF-8')) {
                    $value = mb_convert_encoding($value, 'UTF-8', 'Windows-1252');
                }
            });
    
            // ── ESTADO: acepta nombre o id numérico ──────────────────────────────
            if (isset($data['estado_id']) && trim($data['estado_id']) !== '') {
                $valorEstado = trim($data['estado_id']);
    
                if (!ctype_digit($valorEstado)) {
                    $key = mb_strtolower($valorEstado);
    
                    if (!isset($cacheEstados[$key])) {
                        // Búsqueda exacta primero
                        $estado = $this->Schools->Estados
                            ->find()
                            ->where(['nombre' => $valorEstado])
                            ->first();
    
                        // Fallback case-insensitive en PHP
                        if (!$estado) {
                            $todos = $this->Schools->Estados->find()->all();
                            foreach ($todos as $e) {
                                if (mb_strtolower($e->nombre) === $key) {
                                    $estado = $e;
                                    break;
                                }
                            }
                        }
    
                        $cacheEstados[$key] = $estado ? $estado->id : null;
                    }
    
                    if ($cacheEstados[$key]) {
                        $data['estado_id'] = $cacheEstados[$key];
                    } else {
                        $errors[] = [
                            'row'    => $data,
                            'errors' => ['estado_id' => "Estado no encontrado: '{$valorEstado}'"]
                        ];
                        continue;
                    }
                } else {
                    $data['estado_id'] = (int)$valorEstado;
                }
            }
    
            // ── COLUMNA LEGACY 'estado' ──────────────────────────────────────────
            if (!empty($data['estado'])) {
                $valorEstado = trim($data['estado']);
                $key = mb_strtolower($valorEstado);
    
                if (!isset($cacheEstados[$key])) {
                    $estado = $this->Schools->Estados
                        ->find()
                        ->where(['nombre' => $valorEstado])
                        ->first();
    
                    if (!$estado) {
                        $todos = $this->Schools->Estados->find()->all();
                        foreach ($todos as $e) {
                            if (mb_strtolower($e->nombre) === $key) {
                                $estado = $e;
                                break;
                            }
                        }
                    }
                    $cacheEstados[$key] = $estado ? $estado->id : null;
                }
    
                if ($cacheEstados[$key]) {
                    $data['estado_id'] = $cacheEstados[$key];
                } else {
                    $errors[] = [
                        'row'    => $data,
                        'errors' => ['estado' => "Estado no encontrado: '{$valorEstado}'"]
                    ];
                    continue;
                }
            }
    
            // ── MUNICIPIO: acepta nombre o id numérico ───────────────────────────
            if (isset($data['municipio_id']) && trim($data['municipio_id']) !== '') {
                $valorMunicipio = trim($data['municipio_id']);
    
                if (!ctype_digit($valorMunicipio)) {
                    $estadoIdResuelto = isset($data['estado_id']) ? (int)$data['estado_id'] : 0;
                    $key = mb_strtolower($valorMunicipio) . '_' . $estadoIdResuelto;
    
                    if (!isset($cacheMunicipios[$key])) {
                        $conditions = ['nombre' => $valorMunicipio];
                        if ($estadoIdResuelto > 0) {
                            $conditions['estado_id'] = $estadoIdResuelto;
                        }
    
                        $municipio = $this->Schools->Municipios
                            ->find()
                            ->where($conditions)
                            ->first();
    
                        // Fallback case-insensitive en PHP
                        if (!$municipio) {
                            $qMun = $this->Schools->Municipios->find();
                            if ($estadoIdResuelto > 0) {
                                $qMun->where(['estado_id' => $estadoIdResuelto]);
                            }
                            foreach ($qMun->all() as $m) {
                                if (mb_strtolower($m->nombre) === mb_strtolower($valorMunicipio)) {
                                    $municipio = $m;
                                    break;
                                }
                            }
                        }
    
                        $cacheMunicipios[$key] = $municipio ? $municipio->id : null;
                    }
    
                    if ($cacheMunicipios[$key]) {
                        $data['municipio_id'] = $cacheMunicipios[$key];
                    } else {
                        $errors[] = [
                            'row'    => $data,
                            'errors' => ['municipio_id' => "Municipio no encontrado: '{$valorMunicipio}'" . ($estadoIdResuelto ? " (estado_id={$estadoIdResuelto})" : '')]
                        ];
                        continue;
                    }
                } else {
                    $data['municipio_id'] = (int)$valorMunicipio;
                }
            }
    
            // ── COLUMNA LEGACY 'municipio' ───────────────────────────────────────
            if (!empty($data['municipio'])) {
                $valorMunicipio   = trim($data['municipio']);
                $estadoIdResuelto = isset($data['estado_id']) ? (int)$data['estado_id'] : 0;
                $key = mb_strtolower($valorMunicipio) . '_' . $estadoIdResuelto;
    
                if (!isset($cacheMunicipios[$key])) {
                    $conditions = ['nombre' => $valorMunicipio];
                    if ($estadoIdResuelto > 0) {
                        $conditions['estado_id'] = $estadoIdResuelto;
                    }
                    $municipio = $this->Schools->Municipios->find()->where($conditions)->first();
    
                    if (!$municipio) {
                        $qMun = $this->Schools->Municipios->find();
                        if ($estadoIdResuelto > 0) {
                            $qMun->where(['estado_id' => $estadoIdResuelto]);
                        }
                        foreach ($qMun->all() as $m) {
                            if (mb_strtolower($m->nombre) === mb_strtolower($valorMunicipio)) {
                                $municipio = $m;
                                break;
                            }
                        }
                    }
                    $cacheMunicipios[$key] = $municipio ? $municipio->id : null;
                }
    
                if ($cacheMunicipios[$key]) {
                    $data['municipio_id'] = $cacheMunicipios[$key];
                } else {
                    $errors[] = [
                        'row'    => $data,
                        'errors' => ['municipio' => "Municipio no encontrado: '{$valorMunicipio}'"]
                    ];
                    continue;
                }
            }
    
            // ── USER: acepta nombre completo o id numérico ───────────────────────
            if (isset($data['user_id']) && trim($data['user_id']) !== '') {
                $valorUser = trim($data['user_id']);
    
                if (!ctype_digit($valorUser)) {
                    $keyUser = mb_strtolower($valorUser);
    
                    if (!isset($cacheUsers[$keyUser])) {
                        $encontrado = null;
    
                        foreach ($todosUsers as $u) {
                            $fullName1  = mb_strtolower(trim(implode(' ', array_filter([
                                $u->name, $u->apellido_paterno, $u->apellido_materno
                            ]))));
                            $fullName2  = mb_strtolower(trim(implode(' ', array_filter([
                                $u->name, $u->apellido_materno, $u->apellido_paterno
                            ]))));
                            $dosPartes  = mb_strtolower(trim(($u->name ?? '') . ' ' . ($u->apellido_paterno ?? '')));
                            $soloNombre = mb_strtolower(trim($u->name ?? ''));
    
                            if (
                                $keyUser === $fullName1 ||
                                $keyUser === $fullName2 ||
                                $keyUser === $dosPartes  ||
                                $keyUser === $soloNombre
                            ) {
                                $encontrado = $u;
                                break;
                            }
                        }
    
                        // Fallback: cada palabra del input aparece en los campos del usuario
                        if (!$encontrado) {
                            $partes = array_filter(explode(' ', $keyUser));
                            foreach ($todosUsers as $u) {
                                $campos = mb_strtolower(trim(implode(' ', array_filter([
                                    $u->name, $u->apellido_paterno, $u->apellido_materno
                                ]))));
                                $coinciden = true;
                                foreach ($partes as $p) {
                                    if (strpos($campos, $p) === false) {
                                        $coinciden = false;
                                        break;
                                    }
                                }
                                if ($coinciden) {
                                    $encontrado = $u;
                                    break;
                                }
                            }
                        }
    
                        $cacheUsers[$keyUser] = $encontrado ? $encontrado->id : null;
                    }
    
                    if ($cacheUsers[$keyUser]) {
                        $data['user_id'] = $cacheUsers[$keyUser];
                    } else {
                        $errors[] = [
                            'row'    => $data,
                            'errors' => ['user_id' => "Usuario no encontrado: '{$valorUser}'"]
                        ];
                        continue;
                    }
                } else {
                    $data['user_id'] = (int)$valorUser;
                }
            }
    
            // ── Limpiar columnas legacy que no existen en la tabla ───────────────
            unset($data['estado'], $data['municipio']);
    
            // ── Normalizar booleanos ─────────────────────────────────────────────
            $data['verificada']       = !empty($data['verificada'])       ? 1 : 0;
            $data['venta_montenegro'] = !empty($data['venta_montenegro']) ? 1 : 0;
    
            // ── Limpiar campos vacíos para evitar errores de tipo ────────────────
            foreach (['lat', 'lng', 'num_alumnos', 'grupos', 'presupuesto', 'fecha_decision'] as $campo) {
                if (isset($data[$campo]) && trim((string)$data[$campo]) === '') {
                    $data[$campo] = null;
                }
            }
    
            $school = $this->Schools->newEntity($data);
    
            if ($school->hasErrors()) {
                $errors[] = ['row' => $data, 'errors' => $school->getErrors()];
                continue;
            }
    
            if ($this->Schools->save($school)) {
                $saved++;
            } else {
                $errors[] = ['row' => $data, 'errors' => ['save' => 'Error al guardar la escuela']];
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
                    return trim(implode(' ', array_filter([
                        (string)($u->name ?? ''),
                        (string)($u->apellido_paterno ?? ''),
                        (string)($u->apellido_materno ?? ''),
                    ])));
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

    private function buildUserDisplayName($user): string
    {
        if (!$user) {
            return 'Sin asignar';
        }

        $name = trim(implode(' ', array_filter([
            (string)($user->name ?? ''),
            (string)($user->apellido_paterno ?? ''),
            (string)($user->apellido_materno ?? ''),
        ])));

        return $name !== '' ? $name : 'Sin asignar';
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

    public function territoriosResumen()
    {
        $this->request->allowMethod(['post']);

        $data = $this->request->getData();
        $identity = $this->request->getAttribute('identity');
        $currentUserId = $identity ? (int)$identity->getIdentifier() : null;
        if (($data['mode'] ?? 'admin') === 'mis') {
            $data['user_id'] = $currentUserId;
        }

        $query = $this->Schools->find()
            ->select([
                'Schools.id',
                'Schools.estado_id',
                'Schools.user_id',
            ])
            ->contain([
                'Estados' => function ($q) {
                    return $q->select(['Estados.id', 'Estados.nombre']);
                },
                'Users' => function ($q) {
                    return $q->select(['Users.id', 'Users.name', 'Users.apellido_paterno', 'Users.apellido_materno']);
                },
            ]);

        $query = $this->aplicarFiltros($query, $data);

        $byState = [];
        foreach ($query->all() as $school) {
            $estado = $school->estado ?? null;
            if (!$estado || empty($estado->nombre)) {
                continue;
            }

            $stateKey = (string)$estado->id;
            if (!isset($byState[$stateKey])) {
                $byState[$stateKey] = [
                    'estado_id' => (int)$estado->id,
                    'estado' => (string)$estado->nombre,
                    'total_schools' => 0,
                    'users' => [],
                ];
            }

            $byState[$stateKey]['total_schools']++;

            $userId = (int)($school->user_id ?? 0);
            $userKey = $userId > 0 ? (string)$userId : '0';
            if (!isset($byState[$stateKey]['users'][$userKey])) {
                $byState[$stateKey]['users'][$userKey] = [
                    'user_id' => $userId > 0 ? $userId : null,
                    'user_name' => $this->buildUserDisplayName($school->user ?? null),
                    'count' => 0,
                ];
            }
            $byState[$stateKey]['users'][$userKey]['count']++;
        }

        $states = [];
        foreach ($byState as $state) {
            $users = array_values($state['users']);
            usort($users, function ($a, $b) {
                return (int)$b['count'] <=> (int)$a['count'];
            });

            $states[] = [
                'estado_id' => $state['estado_id'],
                'estado' => $state['estado'],
                'total_schools' => $state['total_schools'],
                'users_count' => count($users),
                'users' => $users,
            ];
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode([
                'ok' => true,
                'states' => $states,
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
            $u = $this->Schools->Users->get($currentUserId, ['fields' => ['id','name','apellido_paterno','apellido_materno']]);
            $users = [
                $u->id => trim(implode(' ', array_filter([
                    (string)($u->name ?? ''),
                    (string)($u->apellido_paterno ?? ''),
                    (string)($u->apellido_materno ?? ''),
                ])))
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
                    return trim(implode(' ', array_filter([
                        (string)($u->name ?? ''),
                        (string)($u->apellido_paterno ?? ''),
                        (string)($u->apellido_materno ?? ''),
                    ])));
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
       // ══════════════════════════════════════════════
// KPIs Sidebar — suma proyección y cierre
// ══════════════════════════════════════════════
public function kpisSidebar(): void
{
    $this->request->allowMethod(['get', 'post']);
    $this->viewBuilder()->disableAutoLayout();

    // Fuerza respuesta JSON aunque no venga Accept: application/json
    $this->response = $this->response->withType('application/json');

    try {
        $SchoolsMaterials = $this->fetchTable('SchoolsMaterials');
        $Metas           = $this->fetchTable('Metas');

        // ── Consolidar filtros (POST y GET) ──────────────────────────
        $data = array_merge(
            (array)$this->request->getQueryParams(),
            (array)$this->request->getData()
        );

        $identity = $this->request->getAttribute('identity');
        $currentUserId = $identity ? (int)$identity->getIdentifier() : null;

        // Forzar filtro por usuario si estamos en "Mis Filtros"
        if (($data['mode'] ?? 'admin') === 'mis' && $currentUserId) {
            $data['user_id'] = $currentUserId;
        }

        $schoolIds = null;
        $q = $this->Schools->find()->select(['Schools.id']);
        $q = $this->aplicarFiltros($q, $data);

        $hayFiltro =
            !empty($data['nombre']) ||
            !empty($data['estado_id']) ||
            !empty($data['municipio_id']) ||
            !empty($data['user_id']) ||
            !empty($data['tipo']) ||
            !empty($data['sector']) ||
            !empty($data['turno']) ||
            !empty($data['cct']) ||
            !empty($data['estatus']) ||
            (array_key_exists('verificada', $data) && $data['verificada'] !== '' && $data['verificada'] !== null) ||
            !empty($data['editorial_actual']) ||
            (array_key_exists('venta_montenegro', $data) && $data['venta_montenegro'] !== '' && $data['venta_montenegro'] !== null) ||
            !empty($data['competencia']) ||
            !empty($data['fecha_decision']) ||
            !empty($data['alumnos_rango']);

        if ($hayFiltro) {
            $schoolIds = $q->all()->extract('id')->toArray();
        }

        // ── Totales de school_materials ──────────────────────────────
        $smQuery = $SchoolsMaterials->find();

        if ($schoolIds !== null) {
            if (empty($schoolIds)) {
                // Filtro activo sin resultados
                echo json_encode(['ok' => true, 'meta' => 0, 'proyeccion' => 0, 'cierre' => 0, 'cobertura' => 0]);
                return;
            }
            $smQuery->where(['SchoolsMaterials.school_id IN' => $schoolIds]);
        }

        // Año de referencia para el ciclo actual (coincide con cierre_2026)
        $currentAnio = 2026; 

        $totals = $smQuery
            ->select([
                'total_proyeccion' => $smQuery->func()->sum('proyeccion_venta'),
                'total_cierre'     => $smQuery->func()->sum('cierre_2026'),
            ])
            ->enableHydration(false)
            ->first();

        $proyeccion = (float)($totals['total_proyeccion'] ?? 0);
        $cierre     = (float)($totals['total_cierre']     ?? 0);

       $anio = (int)$currentAnio;
$userId   = (isset($data['user_id']) && $data['user_id'] !== '' && $data['user_id'] !== null) ? (int)$data['user_id'] : null; 
$estadoId = (isset($data['estado_id']) && $data['estado_id'] !== '' && $data['estado_id'] !== null) ? (int)$data['estado_id'] : null; 
 
$metaRow = null; 
$metaSum = null;
$metaIsSum = false;

if ($userId && $estadoId) {
    $metaRow = $Metas->find()
        ->where(['anio' => $anio, 'user_id' => $userId, 'estado_id' => $estadoId])
        ->order(['id' => 'DESC'])
        ->first();
    if (!$metaRow) {
        $metaRow = $Metas->find()
            ->where(['anio' => $anio, 'user_id' => $userId, 'estado_id IS' => null])
            ->order(['id' => 'DESC'])
            ->first();
    }
    if (!$metaRow) {
        $metaRow = $Metas->find()
            ->where(['anio' => $anio, 'user_id IS' => null, 'estado_id' => $estadoId])
            ->order(['id' => 'DESC'])
            ->first();
    }
} elseif ($userId) {
    $metaRow = $Metas->find()
        ->where(['anio' => $anio, 'user_id' => $userId, 'estado_id IS' => null])
        ->order(['id' => 'DESC'])
        ->first();
} elseif ($estadoId) { 
    $sumQuery = $Metas->find();
    $sumRow = $sumQuery
        ->select(['total' => $sumQuery->func()->sum('valor')])
        ->where(['anio' => $anio, 'estado_id' => $estadoId, 'user_id IS NOT' => null])
        ->enableHydration(false)
        ->first();

    $sumMeta = (float)($sumRow['total'] ?? 0);
    if ($sumMeta > 0) {
        $metaSum = $sumMeta;
        $metaIsSum = true;
        $metaRow = null;
    } else {
        $metaRow = $Metas->find()
            ->where(['anio' => $anio, 'user_id IS' => null, 'estado_id' => $estadoId])
            ->order(['id' => 'DESC'])
            ->first();
    }
} 

if (!$metaRow) {
    $metaRow = $Metas->find()
        ->where(['anio' => $anio, 'user_id IS' => null, 'estado_id IS' => null])
        ->order(['id' => 'DESC'])
        ->first();
}

$meta      = $metaSum !== null ? (float)$metaSum : ($metaRow ? (float)$metaRow->valor : $proyeccion);
$cobertura = $meta > 0 ? round(($cierre / $meta) * 100, 1) : 0; 

$metaScope = 'global';
if ($userId && $estadoId) $metaScope = 'user_estado';
elseif ($userId)         $metaScope = 'user';
elseif ($estadoId)       $metaScope = $metaIsSum ? 'estado_sum' : 'estado';

$metaLabel = 'Meta global';
$estadoNombre = null;
$userNombre = null;

if ($estadoId) {
    $estado = $this->Schools->Estados->find()->select(['id', 'nombre'])->where(['id' => $estadoId])->first();
    $estadoNombre = $estado ? (string)$estado->nombre : null;
}
if ($userId) {
    $user = $this->Schools->Users->find()
        ->select(['id', 'name', 'apellido_paterno', 'apellido_materno'])
        ->where(['id' => $userId])
        ->first();
    $userNombre = $this->buildUserDisplayName($user);
}

if ($userId && $estadoId)      $metaLabel = 'Meta: ' . trim(implode(' · ', array_filter([$userNombre, $estadoNombre])));
elseif ($userId)              $metaLabel = 'Meta: ' . ($userNombre ?: 'Usuario');
elseif ($estadoId)            $metaLabel = 'Meta: ' . ($estadoNombre ?: 'Estado') . ($metaIsSum ? ' (suma usuarios)' : '');

echo json_encode([
    'ok'         => true,
    'meta'       => $meta,
    'proyeccion' => $proyeccion,
    'cierre'     => $cierre,
    'cobertura'  => $cobertura,
    'meta_scope' => $metaScope,
    'meta_label' => $metaLabel,
]);


    } catch (\Exception $e) {
        $this->response = $this->response->withStatus(200); // evita que el error llegue como HTML
        echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
    }

    // Detiene el render de la vista
    $this->viewBuilder()->disableAutoLayout();
    exit;
}

// ══════════════════════════════════════════════
// Guardar meta inline
// ══════════════════════════════════════════════
public function guardarMeta(): void
{
    $this->request->allowMethod(['post']);
    $this->viewBuilder()->disableAutoLayout();
    $this->response = $this->response->withType('application/json');

    try {
        $Metas = $this->fetchTable('Metas');
$data  = $this->request->getData();

$identity = $this->request->getAttribute('identity');
$currentUserId = $identity ? (int)$identity->getIdentifier() : null;
if (($data['mode'] ?? 'admin') === 'mis' && $currentUserId) {
    $data['user_id'] = $currentUserId;
}

$anio  = (int)($data['anio'] ?? date('Y'));
$valor = (float)($data['valor'] ?? 0);

$userId   = (isset($data['user_id']) && $data['user_id'] !== '' && $data['user_id'] !== null) ? (int)$data['user_id'] : null;
$estadoId = (isset($data['estado_id']) && $data['estado_id'] !== '' && $data['estado_id'] !== null) ? (int)$data['estado_id'] : null;

if ($valor < 0) {
    echo json_encode(['ok' => false, 'error' => 'Valor inválido']);
    exit;
}

$conditions = ['anio' => $anio];
if ($userId)  $conditions['user_id'] = $userId;
else          $conditions['user_id IS'] = null;

if ($estadoId) $conditions['estado_id'] = $estadoId;
else           $conditions['estado_id IS'] = null;

$existing = $Metas->find()->where($conditions)->order(['id' => 'DESC'])->first();

if ($existing) {
    $existing->valor       = $valor;
    $existing->descripcion = "Meta ventas {$anio}";
    $Metas->save($existing);
} else {
    $nueva = $Metas->newEntity([
        'anio'        => $anio,
        'descripcion' => "Meta ventas {$anio}",
        'valor'       => $valor,
        'user_id'     => $userId,
        'estado_id'   => $estadoId,
    ]);
    $Metas->save($nueva);
}

echo json_encode([
    'ok' => true,
    'valor' => $valor,
    'anio' => $anio,
    'user_id' => $userId,
    'estado_id' => $estadoId,
]);


    } catch (\Exception $e) {
        echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
    }

    exit;
}
}
