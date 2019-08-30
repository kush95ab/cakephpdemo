<?php
App::uses('AppController','Controller');
class ProductsController extends AppController{
    public function add(){
        $this->layout = false;
        $response = array('status'=>'failed', 'message'=>'HTTP method not allowed');if($this->request->is('post')){
            
            //get data from request object
            $data = $this->request->input('json_decode', true);
            if(empty($data)){
                $data = $this->request->data;
            }
            
            //response if post data or form data was not passed
            $response = array('status'=>'failed', 'message'=>'Please provide form data');
                
            if(!empty($data)){
                //call the model's save function
                if($this->Product->save($data)){
                    //return success
                    $response = array('status'=>'success','message'=>'Product successfully created');
                } else{
                    $response = array('status'=>'failed', 'message'=>'Failed to save data');
                }
            }
        }
    }
}
?>