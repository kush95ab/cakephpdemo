<?php
//src/Controller/UsersController


namespace App\Controller;

use App\Controller\AppController;
// use Cake\Database\Type\DateType;
use Cake\Event\Event;
// use PhpParser\Node\Stmt\Echo_;
use Cake\Core\Configure;
use PHPUnit\TextUI\ResultPrinter;
use Zend\Diactoros\Response;

class UsersController extends AppController
{

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        // Allow users to register and logout.
        // You should not add the "login" action to allow list. Doing so would
        // cause problems with normal functioning of AuthComponent.
        $this->Auth->allow(['add', 'logout']);
    }

    public function index()

    {
        $this->autoRender = false;


        if ($this->request->is('get')) {
            $users = $this->Users->find();
            if ($users) {
                $resultjs = (array('result' => $users, 'massage' => 'Success', 'status' => '200'));
            } else {
                $resultjs = (array('result' => 'Error: User is not in', 'status' => '404'));
            }
        } else {
            $resultjs = (array('result' => 'Error : Method Not Allowed.', 'status' => '405'));
        }

        // $this->set('users', $this->Users->find('all'));
        // $users = $this->Paginator->paginate($this->Users->find());

        return $this->response->withType("application/json")->withStringBody(json_encode($resultjs));
        // $this->set(compact('users'));
        // $this->redirect('users/login');-
    }

    public function view()
    {
        $this->autoRender = false;

        if ($this->request->is('post')) {


            $req = $this->request->getdata();
            $id = $req["id"];
            $user = $this->Users->findById($id)->firstOrFail();

            if ($user) {
                $resultjs = (array('result' => $user, 'massage' => 'Success : User found', 'status' => '200'));
            } else {
                $resultjs = (array('result' => 'Error: User not found', 'status' => '404'));
            }
        } else {
            $resultjs = (array('result' => 'Error : Method Not Allowed.', 'status' => '405'));
        }

        return $this->response->withType("application/json")->withStringBody(json_encode($resultjs));
    }

    public function add()
    {
        $this->autoRender = false;
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            // echo $data['role'];
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $user->role = $data['role'];
            echo $user;
            if ($this->Users->save($user)) {
                $resultjs = (array('massage' => 'Success : User successfully Inserted', 'status' => '200'));

                // $this->Flash->success(__('The user has been saved.'));
                // return $this->redirect(['action' => 'index']);

            } else {
                // $this->Flash->error(__('Invalid username or password, try again'));
                $resultjs = (array('massage' => 'Error: User is not inseeted', 'status' => '404'));
                // $this->Flash->error(__('Unable to add the user.'));
            }
        } else {
            $resultjs = (array('massage' => 'Error : Method Not Allowed.', 'status' => '405'));
        }
    }


    public function login()
    {
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $user = $this->Auth->identify(); //return false of relevent user

            if ($user) {
                $resultjs = (array('massage' => 'Success : User successfully logged in', 'status' => '200'));
                // echo 'identified user ' . json_encode($user);
                // $this->Auth->setUser($user[0]);

            } else {
                $this->Flash->error(__('Invalid username or password, try again'));
                $resultjs = (array('massage' => 'Error: Invalid username or password, try again', 'status' => '200'));
                // $response = json_encode(array('result' => 'Error:Invalid username or password, try again', 'status' => '401 '));
            }
        } else {
            $resultjs = (array('massage' => 'Error : Method Not Allowed.', 'status' => '405'));
        }
        // echo $resultjs;
        return $this->response->withType("application/json")->withStringBody(json_encode($resultjs));
    }



    public function delete()
    {
        $this->autoRender=false;
        if ($this->request->allowMethod(['post', 'delete'])) {
            $req = $this->request->getData();
            $id = $req["id"];
            $user = $this->Users->findById($id)->firstOrFail();
            if ($this->Users->delete($user)) {
                $resultjs = (array('massage' => 'Success : User successfully deleted', 'status' => '200'));

                // $this->Flash->success(__('The {0} account has been deleted.', $user->username));
                // return $this->redirect(['action' => 'index']);
            } else {
                $resultjs = (array('massage' => 'Error : Faild to delete.', 'status' => '404'));
            }
        } else {
            $resultjs = (array('massage' => 'Error : Method Not Allowed.', 'status' => '405'));
        }
        return $this->response->withType("application/json")->withStringBody(json_encode($resultjs));
    }

    public function logout()
    {
        if ($this->Auth->logout()) {
            $resultjs = (array('massage' => 'Success : User successfully loggedout', 'status' => '200'));

            return $this->response->withType("application/json")->withStringBody(json_encode($resultjs));
        }
        // return $this->redirect($this->Auth->logout());
    }
}
