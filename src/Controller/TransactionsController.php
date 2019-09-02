<?php

// src/Controller/TransactionsController.php

namespace App\Controller;

use App\Model\Entity\Transaction;
use cake\Cache\Cache;
use Cake\Event\Event;

// Transaction::uses('HttpSocket', 'Network/Http');
class TransactionsController extends AppController


{
    // use \Crud\Controller\ControllerTrait;
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        // Allow users to register and logout.
        // You should not add the "login" action to allow list. Doing so would
        // cause problems with normal functioning of AuthComponent.
        $role=$this->Auth->user()['role'];
        if ($role=='admin'||$role=='author') {
            $this->Auth->allow();
        }else{
            $this->Auth->allow(['login','add']);
        }
    }

    public function index()
    {
        // $this->autoRender = false;
        // $this->loadComponent('Paginator');
        // $transactions = $this->Paginator->paginate($this->Transactions->find('all'));
        if ($this->request->is('get')) {
            $transactions = $this->Transactions->find();
            if ($transactions) {
                $resultjs = (array('result' => $transactions, 'massage' => 'Success : User successfully ', 'status' => '200'));
            } else {
                $resultjs = (array('result' => 'Error: User is not inseeted', 'status' => '404'));
            }
        } else {
            $resultjs = (array('result' => 'Error : Method Not Allowed.', 'status' => '405'));
        }

        return $this->response->withType("application/json")->withStringBody(json_encode($resultjs));
       
        // $this->set([ 
        //     'transactions' => $transactions,
        //     'serialize' => ['transactions']
        // ]);
        // $transactions= $this->Transactions->find();
        // $data = json_encode($transactions);
        // $response = $HttpSocket->post('http://localhost:8765/transactions/index', $data, $request);

        // coverting mac format int to mac
        // foreach ($transactions as $transaction => $s) {
        //     $tempcret = $this->int2macaddress($s->sourcemac);
        //     $tempmodi = $this->int2macaddress($s->destmac);
        //     $s->sourcemac = $tempcret;

        //     if ($tempmodi != null) {
        //         $s->destmac = $tempmodi;
        //     }
        // }

        // return $this->response->withType("application/json")->withStringBody(json_encode($resultjs));
        // $this->set('_serialize', ['transactions', 'comments']);
        // $this->set(compact('transactions'));
    }

    public function view()
    {
        $this->request->trustProxy = true;
        $this->autoRender = false;

        $req = $this->request->getdata();
        $id = $req[0]["id"];

        $transactions = $this->Transactions->findById($id)->firstOrFail();
        foreach ($transactions as $transaction) {
            return $this->response->withType("application/json")->withStringBody(json_encode($transaction));
            echo $this->response->withType("application/json")->withStringBody(json_encode($transaction));
        }
    }




    public function add()
    {

        // $this->autoRender = false;
        $transaction = $this->Transactions->newEntity();

        if ($this->request->is('post', 'put')) {
            $req = $this->request->getdata();
            echo json_encode($req);
            // $transaction->sourcemac = $req[0]["sourcemac"];
            // $transaction->destmac = $req[0]["destmac"];
            // $transaction->ports = $req[0]["ports"];
            // $transaction->slug = $req[0]["sourcemac"] . $req[0]["destmac"];;

            $transaction = $this->Transactions->patchEntity($transaction, $this->request->getData());
            $transaction->slug = $transaction->sourcemac . $transaction->destmac;

            if ($this->Transactions->save($transaction)) {
                $resultJ = json_encode(array('massage' => 'Your transaction has been saved.'));
            } else {
                $resultJ = json_encode(array('massage' => 'Unable to add your transaction.', 'errors' => $transaction->errors()));
            }

            return $this->response->withType("application/json")->withStringBody($resultJ);
        }
        // echo "not a post request";
    }



    public function update()
    {
        // $this->autoRender = false;

        if ($this->request->is(['post', 'put'])) {
            $transaction = $this->Transactions->newEntity();

            //setting request details 
            // $req = $this->request->getdata();
            // $transaction->id = $req[0]["id"];
            // $transaction->sourcemac = $req[0]["sourcemac"];
            // $transaction->destmac = $req[0]["destmac"];
            // $transaction->ports = $req[0]["ports"];
            // $transaction->slug = $req[0]["sourcemac"] . $req[0]["destmac"];
            $transaction = $this->Transactions->patchEntity($transaction, $this->request->getData());
            $transaction->slug = $transaction->sourcemac . $transaction->destmac;

            if ($this->Transactions->save($transaction)) {
                $resultJ = json_encode(array('result' => 'Your article has been updated.'));
            } else {
                // $this->Flash->error(__('Unable to update your transaction.'));
                $resultJ = json_encode(array('result' => 'Unable to update your transaction.', 'errors' => $transaction->errors()));
            }
        }
        echo $this->response->withType("application/json")->withStringBody(json_encode($resultJ));

        // $this->set('transaction', $transaction);
        // $this->set('_serialize', ['transactions']);
    }






    public function delete()
    {
        // $this->request->allowMethod(['post', 'delete']);
        $req = $this->request->getdata();
        $id = $req[0]["id"];

        $transaction = $this->Transactions->findById($id)->firstOrFail();

        if ($this->Transactions->delete($transaction)) {
            // $this->Flash->success(__('The {0} transaction has been deleted.', $transaction->title));
            // return $this->redirect(['action' => 'index']);
            $resultJ = json_encode(array('result' => 'successfully deleted'));
        } else {
            $resultJ = json_encode(array('result' => 'The transaction has NOT been deleted.', 'errors' => $transaction->errors()));
        }
        // $this->set('transaction', $transaction);
        // $this->set('_serialize', ['transactions']);

        return $this->response->withType("application/json")->withStringBody(json_encode($resultJ));
    }



    public function filterbysourcemac()
    {
        // $this->autoRender = false;
        $this->request->allowMethod('post');
        $req = $this->request->getdata();
        $transaction = $req[0]['sourcemac'];
        // echo  $transaction;

        $transactions = $this->Transactions->findAllBySourcemac($transaction);
        foreach ($transactions as $transaction) {
            echo $transaction . "\r";
        }
        $this->layout = false;
        $this->render('/Transactions/filterbysource');
        return $this->response->withType("application/json")->withStringBody(json_encode($this->response));
    }

    public function filterbydestmac()
    {
        // $this->autoRender = false;
        $this->request->allowMethod('post');
        $req = $this->request->getdata();
        $transaction = $req[0]['destmac'];
        // echo  $transaction;

        $transactions = $this->Transactions->findAllByDestmac($transaction);
        foreach ($transactions as $transaction) {
            // echo $transaction . "\r";
        }
        $this->layout = false;
        $this->render('/Transactions/filterbysource');
        return $this->response;
    }

    public function filterbycreated()
    {
        // $this->autoRender = false;
        $this->request->allowMethod('post');
        $req = $this->request->getdata();
        $transaction = $req[0]['created'];
        // echo  $transaction;

        $transactions = $this->Transactions->findAllByCreated($transaction);
        foreach ($transactions as $transaction) {
            // echo $transaction . "\r";
        }
        $this->layout = false;
        $this->render('/Transactions/filterbysource');
        return $this->response;
    }

    public function filterbymodified()
    {
        // $this->autoRender = false;
        $this->request->allowMethod('post');
        $req = $this->request->getdata();
        $transaction = $req[0]['modified'];
        // echo  $transaction;

        $transactions = $this->Transactions->findAllByModified($transaction);
        foreach ($transactions as $transaction) {
            // echo $transaction . "\r";
        }
        $this->layout = false;
        $this->render('/Transactions/filterbysource');
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
