<div class="container">
    <h1 class="mt-4 mb-3"><?php echo $title; ?></h1>
    <div class="row">
        <div class="col-lg-8 mb-4">
            <form action="/account/profile" method="post">
                <div class="control-group form-group">
                    <div class="controls">
                        <label>Логин:</label>
                        <input type="text" class="form-control" value="<?php echo $_SESSION['account']['login']; ?>" disabled>
                        <p class="help-block"></p>
                    </div>
                </div>
                <div class="control-group form-group">
                    <div class="controls">
                        <label>E-mail:</label>
                        <input type="text" class="form-control"  value="<?php echo $_SESSION['account']['email']; ?>" name="email">
                    </div>
                </div>
                <div class="control-group form-group">
                    <div class="controls">
                        <label>Номер кошелька Free-Kassa:</label>
                        <input type="text" class="form-control"  value="<?php echo $_SESSION['account']['wallet']; ?>" name="wallet">
                    </div>
                </div>
                <div class="control-group form-group">
                    <div class="controls">
                        <label>Номер кошелька YandexMoney:</label>
                        <input type="text" class="form-control"  value="<?php echo $_SESSION['account']['walletYandexMoney']; ?>" name="walletYandexMoney">
                    </div>
                </div>
                <div class="control-group form-group">
                    <div class="controls">
                        <label>Новый пароль для входа:</label>
                        <input type="password" id="myInput" class="form-control" name="password">
                        <input type="checkbox" onclick="myFunction()">Показать пароль
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </form>
        </div>
    </div>
</div>