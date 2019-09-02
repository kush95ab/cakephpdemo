<?php
// src/Controller/ArticlesController.php

namespace App\Controller;

use PhpParser\Node\Stmt\Echo_;



class ArticlesController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Paginator');
        $this->loadComponent('Flash'); // Include the FlashComponent
    }

    public function index()

    {
        if ($this->request->is('get')) {
            # code...
            // $this->loadComponent('Paginator');
            $articles = $this->Articles->find();
            if ($articles) {
                return json_encode($articles);
            } else {
                return json_encode('404 not found');
            }
            //  $this->set(compact('articles'));
        } else {
            echo "405 (Method Not Allowed)";
            # code...
        }
    }


    public function view($id = null)
    {
        $article = $this->Articles->findById($id)->firstOrFail();
        $this->set(compact('article'));
    }

    public function add()
    {
        $article = $this->Articles->newEntity();
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());

            // Hardcoding the user_id is temporary, and will be removed later
            // when we build authentication out.
            $article->user_id = 1;

            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add your article.'));
        } else {
            $this->header("HTTP/1.0 405 Method Not Allowed");
           
        }
        $this->set('article', $article);
    }

    public function edit($id)
    {
        $article = $this->Articles->findById($id)->firstOrFail();
        if ($this->request->is(['post', 'put'])) {
            $this->Articles->patchEntity($article, $this->request->getData());
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update your article.'));
        }

        $this->set('article', $article);
    }

    public function delete($id)
    {
        $this->request->allowMethod(['post', 'delete']);

        $article = $this->Articles->findById($id)->firstOrFail();
        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('The {0} article has been deleted.', $article->title));
            return $this->redirect(['action' => 'index']);
        }
    }
}
