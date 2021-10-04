<?php

namespace application\models;

use application\core\Model;

class Merchant extends Model {
	
	public function validatePerfectMoney($post, $tariff) {
		$params = 
			$post['PAYMENT_ID'].':'.
			$post['PAYEE_ACCOUNT'].':'.
			$post['PAYMENT_AMOUNT'].':'.
			$post['PAYMENT_UNITS'].':'.
			$post['PAYMENT_BATCH_NUM'].':'.
			$post['PAYER_ACCOUNT'].':'.
			strtoupper(md5('TP3855m9IBQUuZMDWtwqgtFTT')).':'. //изменить secret на свой секретный ключь так же на странице с формой вписать номер моего кошелька
			$post['TIMESTAMPGMT'];
		
		list($tid, $uid) = explode(',', $post['PAYMENT_ID']);
		$tid += 0;
		$uid += 0;
		$amount = $post['PAYMENT_AMOUNT'] + 0;
		if (strtoupper(md5($params)) != $post['V2_HASH']) {
			return false;
		}
		if ($post['PAYMENT_UNITS'] != 'USD') {
			return false;
		}
		elseif (!isset($tariff[$tid])) {
			return false;
		}
		elseif ($amount > $tariff[$tid]['max'] or $amount < $tariff[$tid]['min']) {
			return false;
		}
		return [
			'tid' => $tid,
			'uid' => $uid,
			'amount' => $amount,
		];
	}
        public function validatePayeer($post, $tariff) {
            //if (!in_array($_SERVER['REMOTE_ADDR'], array('185.71.65.92', '185.71.65.189', '149.202.17.210'))) return;

            if (isset($post['m_operation_id']) && isset($post['m_sign']))
            {
                    $m_key = 'OCHoxxsRI4vMnUnF';

                    $arHash = array(
                            $post['m_operation_id'],
                            $post['m_operation_ps'],
                            $post['m_operation_date'],
                            $post['m_operation_pay_date'],
                            $post['m_shop'],
                            $post['m_orderid'],
                            $post['m_amount'],
                            $post['m_curr'],
                            $post['m_desc'],
                            $post['m_status']
                    );
                    
                    list($tid, $uid) = explode(',',base64_decode($post['m_desc']));
                    $tid += 0;
                    $uid += 0;
                    $amount = $post['m_amount'] + 0;
                    $data=[
			'tid' => $tid,
			'uid' => $uid,
			'amount' => $amount,
                        ];
                    if (isset($post['m_params']))
                    {
                            $arHash[] = $post['m_params'];
                    }

                    $arHash[] = $m_key;

                    $sign_hash = strtoupper(hash('sha256', implode(':', $arHash)));

                    if ($post['m_sign'] == $sign_hash && $post['m_status'] == 'success')
                    {
                        if ($post['m_curr'] === 'RUB') {
                          if (isset($tariff[$tid])) {
                            if ($amount <= $tariff[$tid]['max'] and $amount >= $tariff[$tid]['min']){
                                //ob_end_clean(); 
                                  //$this->createTariff($data, $tariff[$data['tid']]);                          
                                  //exit($post['m_orderid'].'|success');

                                  return $data;  
                                }                            
                            }
                        }                            
                    }

                    //ob_end_clean(); 
                    exit($post['m_orderid'].'|error');                
            }
        }
        
        public function validatefreekassa($post, $tariff) {
                $merchant_id = '234676';
                $merchant_secret = 'djv8pg5e';
                
                

                /*function getIP() {
                if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
                return $_SERVER['REMOTE_ADDR'];
                }
                if (!in_array(getIP(), array('136.243.38.147', '136.243.38.149', '136.243.38.150', '136.243.38.151', '136.243.38.189', '136.243.38.108'))) {
                die("hacking attempt!");
                }*/

                $sign = md5($merchant_id.':'.$post['AMOUNT'].':'.$merchant_secret.':'.$post['MERCHANT_ORDER_ID']); // заменил $_REQUEST на $post
                
                list($tid, $uid) = explode(',', $post['MERCHANT_ORDER_ID']);
		$tid += 0;
		$uid += 0;
		$amount = $post['AMOUNT'] + 0;

                if ($sign != $post['SIGN']) {               // заменил $_REQUEST на $post    
                        return false;
                }
                
                if (!in_array($post['CUR_ID'],array('133','134','179','128', '45', '162','100','160','187','169','122','114','115','64','69','79','113','84','83','118','80','155','63','175','153','94','124','150','192','180','121','189','190','110','82','132','99','117'))) {
			return false;
		}
		elseif (!isset($tariff[$tid])) {
			return false;
		}
		elseif ($amount > $tariff[$tid]['max'] or $amount < $tariff[$tid]['min']) {
			return false;
		}
		return [
			'tid' => $tid,
			'uid' => $uid,
			'amount' => $amount,
		];

                //Так же, рекомендуется добавить проверку на сумму платежа и не была ли эта заявка уже оплачена или отменена
                //Оплата прошла успешно, можно проводить операцию.

                  
        }

	public function createTariff($data, $tarif) {
		$dataRef = $this->db->column('SELECT ref FROM accounts WHERE id = :id', ['id' => $data['uid']]);
                //debug($dataRef);
		if ($dataRef === false) {
			return false;
		}
		if ($dataRef != 0) {
			$refSum = round((($data['amount'] * 5) / 100), 2);
			$params = [
				'sum' => $refSum,
				'id' => $dataRef,
			];
			$this->db->query('UPDATE accounts SET refBalance = refBalance + :sum WHERE id = :id', $params);
			$params = [
				'id' => '',
				'uid' => $dataRef,
				'unixTime' => time(),
				'description' => 'Реферальное вознаграждение, сумма '.$refSum.' RUB',
			];
			$this->db->query('INSERT INTO history VALUES (:id, :uid, :unixTime, :description)', $params);
		}
		$params = [
			'id' => '',
			'uid' => $data['uid'],
			'sumIn' => round($data['amount'], 2),
			'sumOut' => round($data['amount'] + (($data['amount'] * $tarif['percent']) / 100), 2),
			'percent' => $tarif['percent'],
			'unixTimeStart' => time(),
			'unixTimeFinish' => strtotime('+ '.$tarif['hour'].' hours'),
		];
		$this->db->query('INSERT INTO tariffs VALUES (:id, :uid, :sumIn, :sumOut, :percent, :unixTimeStart, :unixTimeFinish)', $params);
		
		$params = [
			'id' => '',
			'uid' => $data['uid'],
			'unixTime' => time(),
			'description' => 'Инвестиция, номер вклада # '.$this->db->lastInsertId(),
                        'descriptionfordull' => 'Инвестиция в тариф «'.$tarif['title'].'»',
		];
		$this->db->query('INSERT INTO history VALUES (:id, :uid, :unixTime, :description, :descriptionfordull)', $params);
                //exit('ok');
	}

}