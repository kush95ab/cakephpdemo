<?php
//src/Controller/UsersController


namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\Session;
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
    }

    public function isAuthorized($user)
    {
        return parent::isAuthorized($user);
    }


    public function login()
    {
        // $this->autoRender = false;
        // $this->session->create;

        if ($this->Auth->user() == null) {
            if ($this->request->is('post')) {
                $user = $this->Auth->identify(); //return false of relevent user

                if ($user) {
                    $resultjs = (array('massage' => 'Success : User successfully logged in', 'status' => '200'));
                    // echo 'identified user ' . json_encode(($user) ? true : false);
                    $this->Auth->setUser($user);
                } else {
                    $this->Flash->error(__('Invalid username or password, try again'));
                    $resultjs = (array('massage' => 'Error: Invalid username or password, try again', 'status' => '200'));
                    // $response = json_encode(array('result' => 'Error:Invalid username or password, try again', 'status' => '401 '));
                }
            } else {
                $resultjs = (array('massage' => 'Error : Method Not Allowed.', 'status' => '405'));
            }
        } else {
            $resultjs = (array('massage' => 'Error : Method Not Allowed.( You are alread logged in. Please logout befor login as another user.)', 'status' => '405'));
        }

        // return $this->redirect($this->Auth->redirectUrl());
        return $this->response->withType("application/json")->withStringBody(json_encode($resultjs));
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
        // $this->redirect('users/login');
    }

    public function view()
    {


        $this->autoRender = false;

        if ($this->request->is('post')) {


            $req = $this->request->getdata();
            $id = $req["id"];

            $user = $this->Users->findById($id)->firstOrFail();
            // if ($this->isAuthorized($this->Auth->user())) {

            if ($user) {
                $resultjs = (array('result' => $user, 'massage' => 'Success : User found', 'status' => '200'));
            } else {
                $resultjs = (array('result' => 'Error: User not found', 'status' => '404'));
            }
            // } else {
            //     $resultjs = (array('result' => 'Error : You don not have permission to access this component', 'status' => '405'));
            // }
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


    public function update()
    {
        $this->autoRender = false;
        if ($this->request->allowMethod(['post', 'update'])) {
            $req = $this->request->getData();
            $id = $req["id"];
            $user = $this->Users->findById($id)->firstOrFail();

            if ($user) {
                if ($this->Users->update($user)) {
                    $resultjs = (array('massage' => 'Success : User successfully update', 'status' => '200'));

                    // $this->Flash->success(__('The {0} account has been deleted.', $user->username));
                    // return $this->redirect(['action' => 'index']);
                } else {
                    $resultjs = (array('massage' => 'Error : Faild to update.', 'status' => '404'));
                }
            }else{
                $resultjs = (array('massage' => 'Error : Record not found in table', 'status' => '404'));
            }
        } else {
            $resultjs = (array('massage' => 'Error : Method Not Allowed.', 'status' => '405'));
        }
        return $this->response->withType("application/json")->withStringBody(json_encode($resultjs));
    }


    public function delete()
    {
        $this->autoRender = false;
        if ($this->request->allowMethod(['post', 'delete'])) {
            $req = $this->request->getData();
            $id = $req["id"];
            $user = $this->Users->findById($id)->firstOrFail();
            if ($this->Users->delete($user)) {
                $resultjs = (array('massage' => 'Success : User successfully deleted', 'status' => '200'));

                // $this->Flash->success(__('The {0} account has been deleted.', $user->username));
                // return $this->redirect(['action' => 'index']);
            } else {
                $resultjs = (array('massage' => 'Error : Faild to delete.Record not found in table', 'status' => '404'));
            }
        } else {
            $resultjs = (array('massage' => 'Error : Method Not Allowed.', 'status' => '405'));
        }
        return $this->response->withType("application/json")->withStringBody(json_encode($resultjs));
    }

    public function logout()
    {
        // if ($this->Auth->logout()) {
        //     $session = $this->request->session();
        //     if ($session) {
        //         if ($session->destroy()) {
        //             $resultjs = (array('massage' => 'Success : User successfully loggedout', 'status' => '200'));
        //         } else {
        //             $resultjs = (array('massage' => 'Error : User logging out faild', 'status' => '404'));
        //         }
        //         return $this->response->withType("application/json")->withStringBody(json_encode($resultjs));
        //         // return $this->redirect($this->Auth->logout());

        //     }
        // }

        $session = $this->request->session();


        if ($session) {
            echo 'this is se =' . json_encode($session) . '/end .....';
            if ($this->Auth->logout()) {
                // if ($session->destroy()) {
                $this->request->session()->destroy();
                $resultjs = (array('massage' => 'Success : User successfully loggedout', 'status' => '200'));
            } else {
                $resultjs = (array('massage' => 'Error : User logging out faild', 'status' => '404'));
            }
            return $this->response->withType("application/json")->withStringBody(json_encode($resultjs));
            // return $this->redirect($this->Auth->logout());

        }
        // }
    }
}
