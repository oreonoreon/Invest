<div class="container">
    <h1 class="mt-4 mb-3">Вход</h1>
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="font-italic"><p>Если у вас ещё нет аккаунта, вы можете зарегистрироваться <a href="/account/register">тут</a>.</div>
            <form action="/account/login" method="post">
                <div class="control-group form-group">
                    <div class="controls">
                        <label>Логин:</label>
                        <input type="text" class="form-control" name="login">
                        <p class="help-block"></p>
                    </div>
                </div>
                <div class="control-group form-group">
                    <div class="controls">
                        <label>Пароль</label>
                        <input type="password" id="myInput" class="form-control" name="password"> 
                        <input type="checkbox" onclick="myFunction()">Показать пароль                        
                    </div>
                </div>                                                
                <button type="submit" class="btn btn-primary">Вход</button>
                <a href="/account/recovery"><p align="right">Забыли пароль?</p></a>
            </form>            
        </div>
    </div>
</div>