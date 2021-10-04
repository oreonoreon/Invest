<div class="container">
    <h1 class="mt-4 mb-3">Регистрация</h1>
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="font-italic"><p>Если у вас уже есть аккаунт, вы можете войти <a href="/account/login">тут</a>.</p></div>
            <form action="/account/register" method="post">
                <div class="control-group form-group">
                    <div class="controls">
                        <label>E-mail:</label>                        
                         <input type="text" class="form-control" name="email" 
                            <?php                              
                             if(!stristr($_SERVER['HTTP_USER_AGENT'],'Android') and !stristr($_SERVER['HTTP_USER_AGENT'],'iPhone')): ?>
                                data-toggle="popover" data-trigger="hover" data-content="Укажите свой Email правильно, на него будет выслано письмо с потверждением!"
                            <?php endif; ?>>
                    </div>
                </div>
                <div class="control-group form-group">
                    <div class="controls">
                        <label>Логин:</label>
                        <input type="text" class="form-control" name="login"
                               <?php                              
                             if(!stristr($_SERVER['HTTP_USER_AGENT'],'Android') and !stristr($_SERVER['HTTP_USER_AGENT'],'iPhone')): ?>
                               data-toggle="popover" data-trigger="hover" data-content="Запомните ваш логин он понадобится для входа в ваш аккаунт! Разрешены только латинские буквы и цифры от 3 до 15 символов."
                                   <?php endif; ?>>
                    </div>
                </div>
                <?php if (isset($this->route['ref'])): ?>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>Пригласил:</label>
                            <input type="text" class="form-control" name="ref" value="<?php echo $this->route['ref']; ?>" readonly>
                        </div>
                    </div>
                <?php else: ?>
                    <input type="hidden" class="form-control" name="ref" value="none">
                <?php endif; ?>
                <!--<div class="control-group form-group">
                    <div class="controls">
                        <label>Номер кошелька:</label>
                        <input type="text" class="form-control" name="wallet" data-toggle="popover" data-trigger="hover" title="Кошелёк PerfectMoney" data-content="Укажите свой кошелёк PerfectMoney.">
                    </div> 
                </div>-->
                <div class="control-group form-group">
                    <div class="controls">
                        <label>Пароль</label>
                        <input type="password" id="myInput" class="form-control" name="password" 
                               <?php                              
                             if(!stristr($_SERVER['HTTP_USER_AGENT'],'Android') and !stristr($_SERVER['HTTP_USER_AGENT'],'iPhone')): ?>
                               data-toggle="popover" data-trigger="hover" data-content="Укажите пароль, разрешены только латинские буквы и цифры от 7 до 30 символов."
                                   <?php endif; ?>>
                        <input type="checkbox" onclick="myFunction()">Показать пароль
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Регистрация</button>
            </form>
        </div>
    </div>
</div>
