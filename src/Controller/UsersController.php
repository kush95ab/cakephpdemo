<?php
//src/Controller/UsersController


namespace App\Controller;

use App\Controller\AppController;
// use Cake\Database\Type\DateType;
use Cake\Event\Event;
// use PhpParser\Node\Stmt\Echo_;
use Cake\Core\Configure;


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

        $this->loadComponent('Paginator');
        $this->loadComponent('Flash'); // Include the FlashComponent

        // $this->autoRender=false;
        // $this->set('users', $this->Users->find('all'));
        $users = $this->Paginator->paginate($this->Users->find());
        $this->set(compact('users'));
        // $this->redirect('users/login');
    }

    public function view($id)
    {
        $user = $this->Users->get($id);
        $this->set(compact('user'));
    }

    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            // Prior to 3.4.0 $this->request->data() was used.
            $user = $this->Users->patchEntity($user, $this->request->getData());
            echo $user;
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add the user.'));
        }
        // $this->set('user', $user);
    }


    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify(); //return false of relevent user

            if ($user) {
                echo 'identified user ' . json_encode($user);
                $this->Auth->setUser($user);

                // if ($this->Session->write('username', json_encode($user["username"]))) {
                //     echo " success";
                // } else {
                //     echo 'uncuess';
                // }

                // $curuser = $this->Session->read('username');

                // if(!isset($_COOKIE['currentuser'])) {
                //     $curuser=$user["username"];
                //     setcookie('calculation', json_encode($curuser), time() + (8640000000 * 30), "../"); // Set the cookie for unlimited time
                //     }
                //     else{
                //     $curuser = json_decode($_COOKIE['username'],true);
                // }

                // echo json_encode($curuser);

                echo json_encode($user["username"]);


                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Flash->error(__('Invalid username or password, try again'));
            }
        }
    }
    public function delete($id)
    {
        $this->request->allowMethod(['post', 'delete']);

        $user = $this->Users->findById($id)->firstOrFail();
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The {0} account has been deleted.', $user->username));
            return $this->redirect(['action' => 'index']);
        }
        // return $this->redirect($this->Auth->redirectUrl());
    }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }
}
