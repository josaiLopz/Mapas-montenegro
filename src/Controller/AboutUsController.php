<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * AboutUs Controller
 *
 * @property \App\Model\Table\AboutUsTable $AboutUs
 */
class AboutUsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->AboutUs->find();
        $aboutUs = $this->paginate($query);

        $this->set(compact('aboutUs'));
    }

    public function publicView()
        {
            $aboutU = $this->AboutUs
                ->find()
                ->where(['active' => true])
                ->order(['id' => 'DESC'])
                ->first();

            $this->set(compact('aboutU'));
        }

    /**
     * View method
     *
     * @param string|null $id About U id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $aboutU = $this->AboutUs->get($id, contain: []);
        $this->set(compact('aboutU'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
public function add()
{
    $aboutU = $this->AboutUs->newEmptyEntity();

    if ($this->request->is('post')) {

        // 👉 AQUÍ VA TU CÓDIGO
        $data = $this->request->getData();
        $file = $data['image_file'] ?? null;
        unset($data['image_file']);

        $aboutU = $this->AboutUs->patchEntity($aboutU, $data);

        if ($file && $file->getError() === UPLOAD_ERR_OK) {
            $filename = uniqid() . '-' . $file->getClientFilename();
            $file->moveTo(WWW_ROOT . 'img/about/' . $filename);
            $aboutU->image = $filename;
        }

        if ($this->AboutUs->save($aboutU)) {
            $this->Flash->success('Información guardada correctamente');
            return $this->redirect(['action' => 'index']);
        }

        $this->Flash->error('No se pudo guardar la información');
    }

    $this->set(compact('aboutU'));
}


    /**
     * Edit method
     *
     * @param string|null $id About U id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
{
    $aboutU = $this->AboutUs->get($id);

    if ($this->request->is(['patch', 'post', 'put'])) {

        // 👉 MISMO BLOQUE AQUÍ
        $data = $this->request->getData();
        $file = $data['image_file'] ?? null;
        unset($data['image_file']);

        $aboutU = $this->AboutUs->patchEntity($aboutU, $data);

        if ($file && $file->getError() === UPLOAD_ERR_OK) {
            $filename = uniqid() . '-' . $file->getClientFilename();
            $file->moveTo(WWW_ROOT . 'img/about/' . $filename);
            $aboutU->image = $filename;
        }

        if ($this->AboutUs->save($aboutU)) {
            $this->Flash->success('Información actualizada correctamente');
            return $this->redirect(['action' => 'index']);
        }

        $this->Flash->error('No se pudo actualizar la información');
    }

    $this->set(compact('aboutU'));
}


    /**
     * Delete method
     *
     * @param string|null $id About U id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $aboutU = $this->AboutUs->get($id);
        if ($this->AboutUs->delete($aboutU)) {
            $this->Flash->success(__('The about u has been deleted.'));
        } else {
            $this->Flash->error(__('The about u could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
