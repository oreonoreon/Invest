<?php

namespace application\controllers;

use application\core\Controller;

class MerchantController extends Controller {

	public function perfectmoneyAction() {
		/*
		$_POST['PAYMENT_AMOUNT'] = 200;
		$_POST['PAYEE_ACCOUNT'] = '';
		$_POST['PAYMENT_BATCH_NUM'] = '';
		$_POST['PAYER_ACCOUNT'] = '';
		$_POST['TIMESTAMPGMT'] = '';
		$_POST['PAYMENT_UNITS'] = 'USD';
		$_POST['PAYMENT_ID'] = '1'.','.$_SESSION['account']['id'];
		*/
		if (empty($_POST)) {
			$this->view->errorCode(404);
		}
		$data = $this->model->validatePerfectMoney($_POST, $this->tariffs);
		if (!$data) {
			$this->view->errorCode(403);
		}
                //debug($data);
		$this->model->createTariff($data, $this->tariffs[$data['tid']]);
	}
        
        public function payeerAction() {
		/*
                $_POST['m_operation_id']=1200669604;
                $_POST['m_operation_ps']=2609;
                $_POST['m_operation_date']='07.11.2020 13:37:43';
                $_POST['m_operation_pay_date']='07.11.2020 13:37:57';
                $_POST['m_shop']=1198684109;
                $_POST['m_orderid']='114';
                $_POST['m_amount']='0.01';
                $_POST['m_curr']='RUB';
                $_POST['m_desc']='MSwxNA==';//1,14
                $_POST['m_status']='success';
                $_POST['m_sign']='FE1302B2F56ED429E65C21208FBC57837EE39D2465D52CB3AB1B8AACCA7EA1A3';
                */
		if (empty($_POST)) {
			$this->view->errorCode(404);
		}
		$data = $this->model->validatePayeer($_POST, $this->tariffs);
		if (!$data) {
			$this->view->errorCode(403);
		}
                //debug($data);
		$this->model->createTariff($data, $this->tariffs[$data['tid']]);
                exit($_POST['m_orderid'].'|success');
	}
        
        public function freekassaAction() {
		
		if (empty($_POST)) {
			$this->view->errorCode(404);
		}
		$data = $this->model->validatefreekassa($_REQUEST, $this->tariffs);
		if (!$data) {
			$this->view->errorCode(403);
		}
                //debug($data);
		$this->model->createTariff($data, $this->tariffs[$data['tid']]);
	}

}