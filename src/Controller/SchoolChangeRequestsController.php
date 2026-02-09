<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * SchoolChangeRequests Controller
 *
 * @property \App\Model\Table\SchoolChangeRequestsTable $SchoolChangeRequests
 */
class SchoolChangeRequestsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
        private function isBoss($identity): bool
    {
        if (!$identity) return false;
        $role = $identity->get('role') ?? null;
        return in_array($role, ['admin', 'supervisor'], true);
    }


    public function index()
    {
        $identity = $this->request->getAttribute('identity');
        if (!$this->isBoss($identity)) {
            $this->Flash->error('No autorizado');
            return $this->redirect(['controller' => 'Schools', 'action' => 'filtros']);
        }

        $status = $this->request->getQuery('status') ?: 'pending';

        $q = $this->SchoolChangeRequests->find()
            ->contain(['Schools', 'Requesters', 'Approvers'])
            ->order(['SchoolChangeRequests.created' => 'DESC']);

        if ($status !== 'all') {
            $q->where(['SchoolChangeRequests.status' => $status]);
        }

        $requests = $this->paginate($q, ['limit' => 30]);
        $this->set(compact('requests', 'status'));
    }

    /**
     * View method
     *
     * @param string|null $id School Change Request id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
   public function view($id = null)
    {
        $identity = $this->request->getAttribute('identity');
        if (!$this->isBoss($identity)) {
            $this->Flash->error('No autorizado');
            return $this->redirect(['controller' => 'Schools', 'action' => 'filtros']);
        }

        $request = $this->SchoolChangeRequests->get((int)$id, [
            'contain' => ['Schools', 'Requesters', 'Approvers']
        ]);

        $this->set(compact('request'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $schoolChangeRequest = $this->SchoolChangeRequests->newEmptyEntity();
        if ($this->request->is('post')) {
            $schoolChangeRequest = $this->SchoolChangeRequests->patchEntity($schoolChangeRequest, $this->request->getData());
            if ($this->SchoolChangeRequests->save($schoolChangeRequest)) {
                $this->Flash->success(__('The school change request has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The school change request could not be saved. Please, try again.'));
        }
        $schools = $this->SchoolChangeRequests->Schools->find('list', limit: 200)->all();
        $requesters = $this->SchoolChangeRequests->Requesters->find('list', limit: 200)->all();
        $approvers = $this->SchoolChangeRequests->Approvers->find('list', limit: 200)->all();
        $this->set(compact('schoolChangeRequest', 'schools', 'requesters', 'approvers'));
    }

    /**
     * Edit method
     *
     * @param string|null $id School Change Request id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $schoolChangeRequest = $this->SchoolChangeRequests->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $schoolChangeRequest = $this->SchoolChangeRequests->patchEntity($schoolChangeRequest, $this->request->getData());
            if ($this->SchoolChangeRequests->save($schoolChangeRequest)) {
                $this->Flash->success(__('The school change request has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The school change request could not be saved. Please, try again.'));
        }
        $schools = $this->SchoolChangeRequests->Schools->find('list', limit: 200)->all();
        $requesters = $this->SchoolChangeRequests->Requesters->find('list', limit: 200)->all();
        $approvers = $this->SchoolChangeRequests->Approvers->find('list', limit: 200)->all();
        $this->set(compact('schoolChangeRequest', 'schools', 'requesters', 'approvers'));
    }

    /**
     * Delete method
     *
     * @param string|null $id School Change Request id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $schoolChangeRequest = $this->SchoolChangeRequests->get($id);
        if ($this->SchoolChangeRequests->delete($schoolChangeRequest)) {
            $this->Flash->success(__('The school change request has been deleted.'));
        } else {
            $this->Flash->error(__('The school change request could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
