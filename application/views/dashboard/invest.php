<div class="container">
    <h1 class="mt-4 mb-3"><?php echo $title; ?></h1>
    <div class="row">
        <div class="col-lg-12 col-mb-4">
            <form action="" method="post" target="_blank" id="no_ajax">				
		<div class="control-group form-group">
                    <div class="controls">
                        <label>Название тарифа:</label>
                        <input type="text" class="form-control" value="<?php echo $tariff['title']; ?>" disabled>
                    </div>
                </div>
                <div class="control-group form-group">
                    <div class="controls">
                        <label>Период инвестиции:</label>
                        <input type="text" class="form-control" value="<?php echo $tariff['hour']/24; ?> дней." disabled>
                    </div>
                </div>
                <div class="control-group form-group">
                    <div class="controls">
                        <label>Процентная ставка:</label>
                        <input type="text" class="form-control" value="<?php echo $tariff['percent']; ?> %" disabled>
                    </div>
                </div>
                            <!--
				<input type="hidden" name="PAYEE_ACCOUNT" value="U25821655">
				<input type="hidden" name="PAYEE_NAME" value="Оплата тарифа # <?php echo $this->route['id']; ?>">
                            -->    
				<div class="control-group form-group">
                    <div class="controls">
                        <label>Сумма:</label>
                        <input type="number" min="<?php echo $tariff['min']; ?>" max="<?php echo $tariff['max']; ?>" class="form-control" value="<?php echo $tariff['min']; ?>" name="PAYMENT_AMOUNT">
                    </div>
                </div>
			<!--    <input type="hidden" name="PAYMENT_UNITS" value="USD">
				<input type="hidden" name="PAYMENT_ID" value="<?php echo $this->route['id'].','.$_SESSION['account']['id']; ?>">
				<input type="hidden" name="STATUS_URL" value="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']; ?>/merchant/perfectmoney">
				<input type="hidden" name="PAYMENT_URL" value="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']; ?>/account/profile">
				<input type="hidden" name="PAYMENT_URL_METHOD" value="LINK">
				<input type="hidden" name="NOPAYMENT_URL" value="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']; ?>/account/profile">
				<input type="hidden" name="NOPAYMENT_URL_METHOD" value="LINK">
                        --> 
                        
			<?php //	<button type="sumbit" class="btn btn-primary col-lg-4 col-12" style="margin-bottom: 5px;" name='perfect'>Перейти к оплате через PerfectMoney</button>
                          ?>      
                                <button type="sumbit" class="btn btn-primary col-lg-4 col-12" style="margin-bottom: 5px;" name='freekassa'>Перейти к оплате через Freekassa</button>
                                
			</form>
            
                
                   <?php 
                        if(isset($_POST['payeer'])){
                            $summ = $_POST['PAYMENT_AMOUNT'];
                        
                            $m_shop   = '1198684109';   // id мерчанта
                            $m_orderid   = $this->route['id'].$_SESSION['account']['id']; //   номер счета в системе учета мерчанта
                            $m_amount   = number_format($summ,   2, '.', ''); // сумма счета с двумя знаками после точки
                            $m_curr   = 'USD';   // валюта счета
                            $m_desc   = base64_encode($this->route['id'].','.$_SESSION['account']['id']);   // описание счета, закодированное с помощью алгоритма base64
                            $m_key   = 'OCHoxxsRI4vMnUnF';
                            $arHash   = array($m_shop, $m_orderid, $m_amount, $m_curr, $m_desc);//   Добавляем доп. параметры, если вы их задали
                            if (isset($m_params)){
                                $arHash[]   = $m_params;
                            }
                            //   Добавляем   секретный ключ
                            $arHash[]   = $m_key;
                            //   Формируем подпись
                            $sign   = strtoupper(hash('sha256',   implode(":", $arHash)));
                            ?>


                            <form style="display:none" id="payeer_form_real" method="post"   action="https://payeer.com/merchant/">

                                <input   type="hidden"   name="m_shop" value="<?=$m_shop?>">

                                <input   type="hidden"   name="m_orderid"   value="<?=$m_orderid?>">

                                <input   type="hidden"   name="m_amount"   value="<?=$m_amount?>">

                                <input   type="hidden"   name="m_curr" value="<?=$m_curr?>">

                                <input   type="hidden"   name="m_desc" value="<?=$m_desc?>">

                                <input   type="hidden"   name="m_sign" value="<?=$sign?>">

                                <input   type="submit"   name="m_process"   value="send" />

                            </form>


                    <script type="text/javascript">

                    document.getElementById('payeer_form_real').submit();

                    </script>
                    <?php
                    }
                        if(isset($_POST['perfect'])){
                            ?>
                            <form style="display:none" id="perfectmoney" action="https://perfectmoney.is/api/step1.asp" method="post"  >	
                                <input type="hidden" name="PAYEE_ACCOUNT" value="U25821655">
				<input type="hidden" name="PAYEE_NAME" value="Оплата тарифа # <?php echo $this->route['id']; ?>">
                                
                                <input type="hidden" name="PAYMENT_AMOUNT" value="<?php echo $_POST['PAYMENT_AMOUNT']; ?>">
                                
                                <input type="hidden" name="PAYMENT_UNITS" value="USD">
				<input type="hidden" name="PAYMENT_ID" value="<?php echo $this->route['id'].','.$_SESSION['account']['id']; ?>">
				<input type="hidden" name="STATUS_URL" value="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']; ?>/merchant/perfectmoney">
				<input type="hidden" name="PAYMENT_URL" value="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']; ?>/account/profile">
				<input type="hidden" name="PAYMENT_URL_METHOD" value="LINK">
				<input type="hidden" name="NOPAYMENT_URL" value="<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']; ?>/account/profile">
				<input type="hidden" name="NOPAYMENT_URL_METHOD" value="LINK">
                                <input type="sumbit"  name="send"value="send" />                                
			</form>
                    <script type="text/javascript">

                    document.getElementById('perfectmoney').submit();

                    </script>
                    <?php
                        }
                    ?>  
                    
                    <?php
                    if(isset($_POST['freekassa'])){
                    $merchant_id = '234676';
                    $secret_word = 'dev0ueuj';
                    $order_id = $this->route['id'].','.$_SESSION['account']['id'];
                    $order_amount = $_POST['PAYMENT_AMOUNT'];
                    $sign = md5($merchant_id.':'.$order_amount.':'.$secret_word.':'.$order_id);

                    ?>

                        <form style="display:none" id="freekassa" method='get' action='https://www.free-kassa.ru/merchant/cash.php'>
                        <input type='hidden' name='m' value='<?php echo $merchant_id;?>'>
                        <input type='hidden' name='oa' value='<?php echo $order_amount;?>'>
                        <input type='hidden' name='o' value='<?php echo $order_id;?>'>
                        <input type='hidden' name='s' value='<?php echo $sign;?>'>
                        <input type='hidden' name='i' value=''> <!-- value='80'-->
                        <input type='hidden' name='lang' value='ru'>
                       <!-- <input type='hidden' name='us_login' value='<?php echo $user['login'];?>'> -->
                        <input type='submit' name='pay' value='Оплатить'>
                        </form>
                    <script type="text/javascript">

                    document.getElementById('freekassa').submit();

                    </script>
                    <?php
                        }
                    ?> 
                    
                    
                 
        </div>
    </div>
</div>