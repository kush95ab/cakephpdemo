<?php

// src/Controller/SessionsController.php

namespace App\Controller;

use App\Model\Entity\Session;
use cake\Cache\Cache;
// Session::uses('HttpSocket', 'Network/Http');
class SessionsController extends AppController


{
    // use \Crud\Controller\ControllerTrait;
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function index()
    {
        $this->autoRender = false;
        $this->loadComponent('Paginator');
        // $sessions = $this->Paginator->paginate($this->Sessions->find('all'));

        $sessions = $this->Sessions->find();
        // $this->set([
        //     'sessions' => $sessions,
        //     'serialize' => ['sessions']
        // ]);
        // $sessions= $this->Sessions->find();
        // $data = json_encode($sessions);
        // $response = $HttpSocket->post('http://localhost:8765/sessions/index', $data, $request);

        // coverting mac format int to mac
        // foreach ($sessions as $session => $s) {
        //     $tempcret = $this->int2macaddress($s->sourcemac);
        //     $tempmodi = $this->int2macaddress($s->destmac);
        //     $s->sourcemac = $tempcret;

        //     if ($tempmodi != null) {
        //         $s->destmac = $tempmodi;
        //     }
        // }

        echo $this->response->withType("application/json")->withStringBody(json_encode($sessions));
        $this->set('_serialize', ['sessions', 'comments']);
        $this->set(compact('sessions'));
    }

    public function view()
    {

        $this->request->trustProxy = true;
        $this->autoRender = false;

        $req = $this->request->getdata();
        $id = $req[0]["id"];

        $session = $this->Sessions->findById($id)->firstOrFail();

        echo $this->response->withType("application/json")->withStringBody(json_encode($session));
    }




    public function add()
    {

        $this->autoRender = false;
        $session = $this->Sessions->newEntity();

        if ($this->request->is('post')) {
            $req = $this->request->getdata();
            $session->sourcemac = $req[0]["sourcemac"];
            $session->destmac = $req[0]["destmac"];
            $session->ports = $req[0]["ports"];
            $session->slug = $req[0]["sourcemac"] . $req[0]["destmac"];;

            // echo json_encode($req);
            // echo $req["sourcemac"];// if req is an array
            // echo 'this 2 ' . $req[0]["sourcemac"]; //if req like =>[{}]


            // $session = $this->Sessions->patchEntity($session, $this->request->getData());
            $session->slug = $session->sourcemac . $session->destmac;

            if ($this->Sessions->save($session)) {

                // $this->Flash->success(__('Your session has been saved.'));
                // return $this->redirect(['action' => 'index']);
                $resultJ = json_encode(array('result' => 'success'));
                return $this->response->withType("application/json")->withStringBody(json_encode($resultJ));
            } else {
                $resultJ = json_encode(array('result' => 'error', 'errors' => $session->errors()));

                return $this->response->withType("application/json")->withStringBody(json_encode($resultJ));
            }
            $this->Flash->error(__('Unable to add your session.'));
        }

        $this->set('session', $session);
        $this->set('_serialize', ['sessions']);
        // return $this->redirect(['action' => 'index']);
    }



    public function update()
    {
        $this->autoRender = false;

        if ($this->request->is(['post', 'put'])) {
            $session = $this->Sessions->newEntity();

            //setting request details 
            $req = $this->request->getdata();
            $session->id = $req[0]["id"];
            $session->sourcemac = $req[0]["sourcemac"];
            $session->destmac = $req[0]["destmac"];
            $session->ports = $req[0]["ports"];
            $session->slug = $req[0]["sourcemac"] . $req[0]["destmac"];
            $saved = $this->Sessions->save($session);
            if ($saved) {

                $this->Flash->success(__('Your article has been updated.'));

                $resultJ = json_encode(array('result' => 'success'));
                return $this->response->withType("application/json")->withStringBody(json_encode($resultJ));
            } else {

                $this->Flash->error(__('Unable to update your session.'));
                $resultJ = json_encode(array('result' => 'error', 'errors' => $session->errors()));
                return $this->response->withType("application/json")->withStringBody(json_encode($resultJ));
            }
        }

        $this->set('session', $session);
        $this->set('_serialize', ['sessions']);
    }






    public function delete()
    {
        // $this->request->allowMethod(['post', 'delete']);
        $req = $this->request->getdata();
        $id = $req[0]["id"];



        $session = $this->Sessions->findById($id)->firstOrFail();

        $deleted = $this->Sessions->delete($session);
        if ($deleted) {
            // $this->Flash->success(__('The {0} session has been deleted.', $session->title));
            // return $this->redirect(['action' => 'index']);
            $resultJ = json_encode(array('result' => 'successfully deleted'));
            return $this->response->withType("application/json")->withStringBody(json_encode($resultJ));
        } else {
            $resultJ = json_encode(array('result' => 'error', 'errors' => $session->errors()));

            return $this->response->withType("application/json")->withStringBody(json_encode($resultJ));
        }
        // $this->set('session', $session);
        // $this->set('_serialize', ['sessions']);
    }



    public function filterbysourcemac()
    {
        // $this->request->allowMethod('get');
        $req = $this->request->getdata();
        $session = $req[0]['id'];
        echo '$req';
        echo $req;
        echo '$req[0]';
        echo $req[0];
        echo '$id';
        echo $session;

        $session = $req[0]["sourcemac"];
        // $C=CakeLog::write('debug', 'req'.print_r($req, true)); 
        echo  json_decode($session);
        // echo $req[0];

        $query = $this->Users->findAllBySourcemac($session);
        $data = $query->toArray();
        // echo $data;
        // $session->destmac = $req[0]["destmac"];
        // $session->ports = $req[0]["ports"];
        // $session->slug = $req[0]["sourcemac"] . $req[0]["destmac"];
    }


    //convert mac to big int
    public function mac2int($mac)
    {
        return base_convert($mac, 16, 10);
    }
    //covert db mac to humen readable mac
    public function int2macaddress($int)
    {
        $hex = base_convert($int, 10, 16);
        while (strlen($hex) < 12) {
            $hex = '0' . $hex;
        }

        return strtoupper(implode(':', str_split($hex, 2)));
    }
}
