<?php
namespace application\controllers;

use application\core\Controller;

class MainController extends Controller {
    
    public function indexAction() {  
        $result= $this->model->getNews();
        $result1= $this->model->getNews1();
        $result2= $this->model->getNews2();
        $result3= $this->model->getNews3();
        $vars= array(
          'tariffs'=> $this->tariffs,
          'result'=> $result,
          'result1'=> $result1,
          'result2'=> $result2,
          'result3'=> $result3,  
        );
        $this->view->render('Главная страница',$vars);        
    }
    
    public function faqAction() {
        $this->view->render('FAQ'); 
    }
}


