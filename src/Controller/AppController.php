<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use WyriHaximus\TwigView\Lib\Twig\Extension\View;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */

    use \Crud\Controller\ControllerTrait;

    public $components = [
        'RequestHandler',
        'Crud.Crud' => [
            'actions' => [
                'Crud.Index',
                'Crud.View',
                'Crud.Add',
                'Crud.Edit',
                'Crud.Delete'
            ],
            'listeners' => [
                'Crud.Api',
                'Crud.ApiPagination',
                'Crud.ApiQueryLog'
            ]
        ]
    ];

    public function initialize()
    {

        parent::initialize();
        // echo '1'.json_encode($this->request->getParam('action'));
        // echo 'here '.json_encode($this->request->getParam('controller'));

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);

        $this->loadComponent('Flash');

        $this->loadComponent('Auth', [
            // 'authorize' => ['Controller'], //to run isAuthorized method
            'loginRedirect' => [
                'controller' => 'Articles',
                'action' => 'index'
            ],
            'logoutRedirect' => [
                'controller' => 'Pages',
                'action' => 'display',
                'home'
            ]
        ]);
        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        $this->loadComponent('Security');

    }
    public function isAuthorized($user)
    {
      
        //if user logged
        if (isset($user['role'])) {
            //Logged any user can access index
            if ($this->request->getParam('action') === 'index' ||
            $this->request->getParam('action')==='add') {

                return true;
            }

            // Admin can access every action
            //only admin can alter user table
            if ($user['role'] === 'admin') {
                return true;
            }

            //Moderator accesses
            //Allow all actions in every table but not alter user table
            if ($user['role'] === 'mod') {
                //Allow all actions in every table exept user table            
                if (
                    $this->request->getParam('controller') === 'transaction' ||
                    $this->request->getParam('controller') === 'articles' ||
                    $this->request->getParam('controller') === 'sessions'
                ) {

                    return true;
                }

                // Allow view users 
                if (
                    $this->request->getParam('controller') === 'users' && ($this->request->getParam('users') === 'view' || 'add')
                ) {

                    return true;
                }
            }

            //Normal User actions
            //Allow view any table row exept user table
            if ($user['role'] === 'user') {
                if (($this->request->getParam('action') === 'view' || 'add')) {
                    if (
                        $this->request->getParam('controller') === 'transaction' ||
                        $this->request->getParam('controller') === 'articles' ||
                        $this->request->getParam('controller') === 'sessions'
                    ) {
                        return true;
                    }
                }
            }

      
        }

      
        // Default deny
        return false;
    }


    public function beforeFilter(Event $event)
    {
        //get request contoller
        // or $controller = $this->request->params['controller'];


        if ($this->Auth->user() != null) {

            $username = $this->Auth->user()['username'];
            $userrole = $this->Auth->user()['role'];
            $this->isAuthorized($this->Auth->user());
            echo json_encode(array('state' => 'logged in as ' . $username . '(' . $userrole . ')'));
            
        } else {
            if (
                $this->request->getParam('action') != 'login' || (
                    $this->request->getParam('controller') === 'users' &&
                    $this->request->getParam('action') === 'add')
            ) {
                  $this->Auth->allow(['login', 'add']);
            }   
            echo json_encode(array('state' => 'logged out'));
            // $this->redirect('users/login');
        }
    }
}