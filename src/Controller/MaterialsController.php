<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Materials Controller
 *
 * @property \App\Model\Table\MaterialsTable $Materials
 */
class MaterialsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Materials->find();
        $materials = $this->paginate($query);

        $this->set(compact('materials'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $material = $this->Materials->newEmptyEntity();
        if ($this->request->is('post')) {
            $material = $this->Materials->patchEntity($material, $this->request->getData());
            if ($this->Materials->save($material)) {
                $this->Flash->success('El material ha sido guardado.');

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('El material no pudo ser guardado. Por favor, inténtalo de nuevo.');
        }
        $niveles = ['Preescolar' => 'Preescolar', 'Primaria' => 'Primaria', 'Secundaria' => 'Secundaria'];
        $this->set(compact('material', 'niveles'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Material id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $material = $this->Materials->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $material = $this->Materials->patchEntity($material, $this->request->getData());
            if ($this->Materials->save($material)) {
                $this->Flash->success('El material ha sido guardado.');

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('El material no pudo ser guardado. Por favor, inténtalo de nuevo.');
        }
        $niveles = ['Preescolar' => 'Preescolar', 'Primaria' => 'Primaria', 'Secundaria' => 'Secundaria'];
        $this->set(compact('material', 'niveles'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Material id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $material = $this->Materials->get($id);
        if ($this->Materials->delete($material)) {
            $this->Flash->success('El material ha sido eliminado.');
        } else {
            $this->Flash->error('El material no pudo ser eliminado. Por favor, inténtalo de nuevo.');
        }

        return $this->redirect(['action' => 'index']);
    }
}
