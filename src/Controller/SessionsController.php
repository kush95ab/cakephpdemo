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
        // $this->autoRender = false;
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

        return $this->response->withType("application/json")->withStringBody(json_encode($sessions));
        $this->set('_serialize', ['sessions', 'comments']);
        $this->set(compact('sessions'));
    }

    public function view()
    {
        $this->request->trustProxy = true;
        // $this->autoRender = false;

        $req = $this->request->getdata();
        $id = $req[0]["id"];

        $sessions = $this->Sessions->findById($id)->firstOrFail();
        foreach ($sessions as $session) {
            return $this->response->withType("application/json")->withStringBody(json_encode($session)) ;
        echo $this->response->withType("application/json")->withStringBody(json_encode($session)) ;
        }
    }




    public function add()
    {

        // $this->autoRender = false;
        $session = $this->Sessions->newEntity();

        if ($this->request->is('post','put')) {
            $req = $this->request->getdata();
            echo json_encode($req);
            // $session->sourcemac = $req[0]["sourcemac"];
            // $session->destmac = $req[0]["destmac"];
            // $session->ports = $req[0]["ports"];
            // $session->slug = $req[0]["sourcemac"] . $req[0]["destmac"];;

            $session = $this->Sessions->patchEntity($session, $this->request->getData());
            $session->slug = $session->sourcemac . $session->destmac;

            if ($this->Sessions->save($session)) {
                $resultJ = json_encode(array('result' => 'Your session has been saved.'));
            } else {
                $resultJ = json_encode(array('result' => 'Unable to add your session.', 'errors' => $session->errors()));
            }

            return $this->response->withType("application/json")->withStringBody($resultJ);
        }
        // echo "not a post request";
    }



    public function update()
    {
        // $this->autoRender = false;

        if ($this->request->is(['post', 'put'])) {
            $session = $this->Sessions->newEntity();

            //setting request details 
            // $req = $this->request->getdata();
            // $session->id = $req[0]["id"];
            // $session->sourcemac = $req[0]["sourcemac"];
            // $session->destmac = $req[0]["destmac"];
            // $session->ports = $req[0]["ports"];
            // $session->slug = $req[0]["sourcemac"] . $req[0]["destmac"];
            $session = $this->Sessions->patchEntity($session, $this->request->getData());
            $session->slug = $session->sourcemac . $session->destmac;

            if ($this->Sessions->save($session)) {
                $resultJ = json_encode(array('result' => 'Your article has been updated.'));
            } else {
                // $this->Flash->error(__('Unable to update your session.'));
                $resultJ = json_encode(array('result' => 'Unable to update your session.', 'errors' => $session->errors()));
            }
        }
       echo $this->response->withType("application/json")->withStringBody(json_encode($resultJ));

        // $this->set('session', $session);
        // $this->set('_serialize', ['sessions']);
    }






    public function delete()
    {
        // $this->request->allowMethod(['post', 'delete']);
        $req = $this->request->getdata();
        $id = $req[0]["id"];

        $session = $this->Sessions->findById($id)->firstOrFail();

        if ($this->Sessions->delete($session)) {
            // $this->Flash->success(__('The {0} session has been deleted.', $session->title));
            // return $this->redirect(['action' => 'index']);
            $resultJ = json_encode(array('result' => 'successfully deleted'));
        } else {
            $resultJ = json_encode(array('result' => 'The session has NOT been deleted.', 'errors' => $session->errors()));
        }
        // $this->set('session', $session);
        // $this->set('_serialize', ['sessions']);

        return $this->response->withType("application/json")->withStringBody(json_encode($resultJ));
    }



    public function filterbysourcemac()
    {
        // $this->autoRender = false;
        $this->request->allowMethod('post');
        $req = $this->request->getdata();
        $session = $req[0]['sourcemac'];
        // echo  $session;

        $sessions = $this->Sessions->findAllBySourcemac($session);
        foreach ($sessions as $session) {
            echo $session . "\r";
        }
        $this->layout = false;
        $this->render('/Sessions/filterbysource');
        return $this->response->withType("application/json")->withStringBody(json_encode($this->response));
    }

    public function filterbydestmac()
    {
        // $this->autoRender = false;
        $this->request->allowMethod('post');
        $req = $this->request->getdata();
        $session = $req[0]['destmac'];
        // echo  $session;

        $sessions = $this->Sessions->findAllByDestmac($session);
        foreach ($sessions as $session) {
            // echo $session . "\r";
        }
        $this->layout = false;
        $this->render('/Sessions/filterbysource');
        return $this->response;
    }

    public function filterbycreated()
    {
        // $this->autoRender = false;
        $this->request->allowMethod('post');
        $req = $this->request->getdata();
        $session = $req[0]['created'];
        // echo  $session;

        $sessions = $this->Sessions->findAllByCreated($session);
        foreach ($sessions as $session) {
            // echo $session . "\r";
        }
        $this->layout = false;
        $this->render('/Sessions/filterbysource');
        return $this->response;
    }

    public function filterbymodified()
    {
        // $this->autoRender = false;
        $this->request->allowMethod('post');
        $req = $this->request->getdata();
        $session = $req[0]['modified'];
        // echo  $session;

        $sessions = $this->Sessions->findAllByModified($session);
        foreach ($sessions as $session) {
            // echo $session . "\r";
        }
        $this->layout = false;
        $this->render('/Sessions/filterbysource');
        return $this->response;
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

    protected function _setPassword($password)
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher)->hash($password);
        }
    }
}
