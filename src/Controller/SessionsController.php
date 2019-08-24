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
    { echo json_encode($this->request);
        $this->autoRender = false;
        $session = $this->Sessions->findById($this->request->id)->firstOrFail();
        echo $this->request->getAttribute('params');
        if ($this->request->is('post')) {

            $req = $this->request->getdata();
            $id = $req[0]["id"];
            echo 'params', $req;
           
        }
        // $this->set('_serialize', ['sessions']);//for template uses -save json 
        // $this->set(compact('session'));//for template uses -save arrays
        // $jsonData = $this->request->input('json_decode');
// echo $jsonData;
        echo 'this is response view  ' . $this->response;
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
    }





    public function edit($slug)
    {
        $this->autoRender = false;

        $session = $this->Sessions->findBySlug($slug)->firstOrFail();
        // $session->sourcemac = $this->int2macaddress(($session->sourcemac));
        // $session->destmac = $this->int2macaddress(($session->destmac));

        if ($this->request->is(['post', 'put'])) {
            $this->Sessions->patchEntity($session, $this->request->getData());

            // $this->Sessions->patchEntity($session.id, $this->request->getData('Session.id'));

            // $this->Sessions->patchEntity($session.title, $this->request->getData('Session.title'));

            // $this->Sessions->patchEntity($session.sourcemac, $this->request->getData('Session.sourcemac'));

            // $this->Sessions->patchEntity($session.destmac, $this->request->getData('Session.destmac'));

            // $this->Sessions->patchEntity($session.created, $this->request->getData('Session.created'));

            // $this->Sessions->patchEntity($session.ports, $this->request->getData('Session.ports'));


            if ($this->Sessions->save($session)) {
                // $this->Flash->success(__('Your session has been updated.'));
                // return $this->redirect(['action' => 'index']);
                $resultJ = json_encode(array('result' => 'success'));
                return $this->response->withType("application/json")->withStringBody(json_encode($resultJ));
            } else {
                $resultJ = json_encode(array('result' => 'error', 'errors' => $session->errors()));

                return $this->response->withType("application/json")->withStringBody(json_encode($resultJ));
            }
            $this->Flash->error(__('Unable to update your session.'));
        }

        // echo $session;

        $this->set('session', $session);
        $this->set('_serialize', ['sessions']);
    }






    public function delete($slug)
    {
        $this->request->allowMethod(['post', 'delete']);

        $session = $this->Sessions->findBySlug($slug)->firstOrFail();
        if ($this->Sessions->delete($session)) {
            // $this->Flash->success(__('The {0} session has been deleted.', $session->title));
            // return $this->redirect(['action' => 'index']);
            $resultJ = json_encode(array('result' => 'success'));
            return $this->response->withType("application/json")->withStringBody(json_encode($resultJ));
        } else {
            $resultJ = json_encode(array('result' => 'error', 'errors' => $session->errors()));

            return $this->response->withType("application/json")->withStringBody(json_encode($resultJ));
        }
        $this->set('session', $session);
        $this->set('_serialize', ['sessions']);
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
