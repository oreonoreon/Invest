<?php

namespace application\models;

use \application\core\Model;
use \PHPMailer\PHPMailer\PHPMailer;

class Account extends Model {  
    public function validate($input,$post) {
        $rules = array(
			'email' => array(
				'pattern' => '#^([A-z0-9_.-]{1,20}+)@([a-z0-9_.-]+)\.([a-z\.]{2,10})$#',
				'message' => 'E-mail адрес указан неверно',
			),
			'login' => array(
				'pattern' => '#^[a-zA-Z0-9]{3,15}$#',
				'message' => 'Логин указан неверно (разрешены только латинские буквы и цифры от 3 до 15 символов',
			),
			'ref' => array(
				'pattern' => '#^[a-zA-Z0-9]{3,15}$#',
				'message' => 'Логин пригласившего указен неверно',
			),
			'wallet' => array(
				'pattern' => '#^[A-z]{1}[0-9]{1,15}$#', //'#^[A-z0-9]{0,15}$#' старая регулярка в которой могли не указать первую букву
				'message' => 'Кошелёк Perfect Money указан неверно',
			),
                        'walletYandexMoney' => array(                           // Добавил 'walletYandexMoney' для валидации яндекс кошелька, должен использоваться только в в классе save  при сохранении в profile
				'pattern' => '#^[0-9]{15}$#',
				'message' => 'Кошелёк YandexMoney указан неверно',
			),
			'password' => array(
				'pattern' => '#^[a-zA-Z0-9]{7,30}$#',
				'message' => 'Пароль указан неверно (разрешены только латинские буквы и цифры от 7 до 30 символов',

			),
		);
                foreach ($input as $val) {
			if (!isset($post[$val]) or !preg_match($rules[$val]['pattern'], $post[$val])) {
				$this->error = $rules[$val]['message'];
				return false;
			}
		}
		if (isset($post['ref'])) {
			if ($post['login'] == $post['ref']) {
				$this->error = 'Регистрация невозможна';
				return false;
			}
		}
		return true; 
    }
    
    public function checkEmailExists($email) {
		$params = array(
			'email' => $email,
		);
		return $this->db->column('SELECT id FROM accounts WHERE email = :email', $params);
                /*if ($this->db->column('SELECT id FROM accounts WHERE email = :email', $params)) {
			$this->error = 'Этот email уже используется.';
			return false;	
                } 
                return true;*/
        }

    public function checkLoginExists($login) {
		$params = array(
			'login' => $login,
		);
		if ($this->db->column('SELECT id FROM accounts WHERE login = :login', $params)) {
			$this->error = 'Этот login уже используется.';
			return false;
		}
		return true;
	}
        
    public function checkTokenExists($token) {
		$params = [
			'token' => $token,
		];
		return $this->db->column('SELECT id FROM accounts WHERE token = :token', $params);
	}
        
    public function activate($token) {
		$params = [
			'token' => $token,
		];
		$this->db->query('UPDATE accounts SET status = 1, token = "" WHERE token = :token', $params);
	} 
        
    public function checkRefExists($login) {
		$params = [
			'login' => $login,
		];
		return $this->db->column('SELECT id FROM accounts WHERE login = :login', $params);
	}    
    
    public function register($post) {
                $token = $this->createToken();
		if ($post['ref'] == 'none') {
			$ref = 0;
		}
		else {
			$ref = $this->checkRefExists($post['ref']);
			if (!$ref) {
				$ref = 0;
			}
		}
                $params = [
                  'id' =>'',
                  'email'=>$post['email'],
                  'login'=>$post['login'],
                  'wallet'=>'',                                    //если регистрация с кошельком то 'wallet'=>$post['wallet'], 
                  'walletYandexMoney' => '', // удалить если регистрация только с PerfectMoney  кошельком
                  'password'=> password_hash($post['password'],PASSWORD_BCRYPT),
                  'ref'=>$ref,
                  'refBalance'=>0,
                  'token'=>$token,
                  'status'=>0,
                ];
                $this->db->query('INSERT INTO accounts VALUES(:id, :email, :login, :wallet, :walletYandexMoney, :password, :ref, :refBalance, :token, :status)',$params); // :walletYandexMoney, удалить если в базе не будет яндекс кошелька
                mail($post['email'], 'Register', 'Confirm: '.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/account/confirm/'.$token);
                /*
                require_once 'PHPMailer/PHPMailer/PHPMailer.php';
 
				$mail = new PHPMailer;
				$mail->CharSet = 'UTF-8';
				 
				// Настройки SMTP
				$mail->isSMTP();
				$mail->SMTPAuth = true;
				$mail->SMTPDebug = 0;
				 
				$mail->Host = 'ssl://smtp.timeweb.ru';          //$mail->Host = 'ssl://smtp.yandex.ru';
				$mail->Port = 465;                              //$mail->Port = 465;
				$mail->Username = 'report@investfotnite.ru';    //$mail->Username = 'arinochkamalina@yandex.ru';
				$mail->Password = 'Defender3005';               //$mail->Password = '=-0987654321';
				 
				
				// От кого
				$mail->setFrom('report@investfotnite.ru', 'investfotnite.ru');	
				//$mail->setFrom('arinochkamalina@yandex.ru', 'investfotnite.ru');
				 
				// Кому
				$mail->addAddress($post['email'], $post['login']);
				 
				// Тема письма
				$subject ='Регистрация на сайте ivestfotnite.ru';
				$mail->Subject = $subject;
				 
				// Тело письма
				$body = 'Для завершения регистрации подтвердите свой Email: '.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/account/confirm/'.$token;
				$mail->msgHTML($body);
				 
				// Приложение
				//$mail->addAttachment(__DIR__ . '/image.jpg');
				 
				$mail->send(); */
                            
                           
        }
        
    public function checkData($login, $password) {
		$params = [
			'login' => $login,
		];
		$hash = $this->db->column('SELECT password FROM accounts WHERE login = :login', $params);
		if (!$hash or !password_verify($password, $hash)) {
			return false;
		}
		return true;
	}
        
    public function checkStatus($type, $data) {
		$params = [
			$type => $data,
		];
		$status = $this->db->column('SELECT status FROM accounts WHERE '.$type.' = :'.$type, $params);
		if ($status != 1) {
			$this->error = 'Аккаунт ожидает подтверждения по Email.';
			return false;
		}
		return true;
	}
        
    public function login($login) {
		$params = [
			'login' => $login,
		];
		$data = $this->db->row('SELECT * FROM accounts WHERE login = :login', $params);
		$_SESSION['account'] = $data[0];
	} 
        
    public function recovery($post) {
		$token = $this->createToken();
		$params = [
			'email' => $post['email'],
			'token' => $token,
		];
		$this->db->query('UPDATE accounts SET token = :token WHERE email = :email', $params);
		//mail($post['email'], 'Recovery', 'Confirm: '.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/account/reset/'.$token);
                
                require_once 'PHPMailer/PHPMailer/PHPMailer.php';
 
				$mail = new PHPMailer;
				$mail->CharSet = 'UTF-8';
				 
				// Настройки SMTP
				$mail->isSMTP();
				$mail->SMTPAuth = true;
				$mail->SMTPDebug = 0;
				 
				$mail->Host = 'ssl://smtp.timeweb.ru';
				$mail->Port = 465;
				$mail->Username = 'report@investfotnite.ru';
				$mail->Password = 'Defender3005';
				 
				/* 
				$mail->Host = 'ssl://smtp.yandex.ru';
				$mail->Port = 465;
				$mail->Username = 'arinochkamalina@yandex.ru';
				$mail->Password = '*********';
				*/
				// От кого
				$mail->setFrom('report@investfotnite.ru', 'investfotnite.ru');	
				//$mail->setFrom('arinochkamalina@yandex.ru', 'investfotnite.ru');
				 
				// Кому
				$mail->addAddress($post['email']);
				 
				// Тема письма
				$subject ='Востановление пароля на сайте ivestfotnite.ru';
				$mail->Subject = $subject;
				 
				// Тело письма
				$body = 'Подтверждение для сброса пароля: '.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/account/reset/'.$token;
				$mail->msgHTML($body);
				 
				// Приложение
				//$mail->addAttachment(__DIR__ . '/image.jpg');
				 
				$mail->send();
                                
	}
        
    public function reset($token) {
		$new_password = $this->createToken();
		$params = [
			'token' => $token,
			'password' => password_hash($new_password, PASSWORD_BCRYPT),
		];
		$this->db->query('UPDATE accounts SET status = 1, token = "", password = :password WHERE token = :token', $params);
		return $new_password;
	}
        
    public function save($post) {
                $params = [
                        'id' => $_SESSION['account']['id'],
                        'email' => $post['email'],
                        'wallet' => $post['wallet'],
                        'walletYandexMoney' => $post['walletYandexMoney'],      //Добавляем 'walletYandexMoney' для сохранения в базу
                ];
                if (!empty($post['password'])) {
                        $params['password'] = password_hash($post['password'], PASSWORD_BCRYPT);
                        $sql = ',password = :password';
                }
                else {
                        $sql = '';
                }
                foreach ($params as $key => $val) {
                        $_SESSION['account'][$key] = $val;
                }
                $this->db->query('UPDATE accounts SET email = :email, wallet = :wallet, walletYandexMoney = :walletYandexMoney'.$sql.' WHERE id = :id', $params); // добавил , walletYandexMoney = :walletYandexMoney для записи в базу
        }
              
    public function createToken() {
	return substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyz', 30)), 0, 30);
	}           
}
