<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $title; ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="/public/styles/bootstrap.css" rel="stylesheet"> 
        <link href="/public/styles/blyat.css" rel="stylesheet"> 
        <link href="/public/styles/main.css" rel="stylesheet"> 
        <link href="/public/styles/sticky-footer.css" rel="stylesheet"> 
        <script src="/public/scripts/jquery.js"></script> 
        <script src="/public/scripts/form.js"></script>
        <script src="/public/scripts/popper.js"></script>
        <script src="/public/scripts/bootstrap.js"></script>
        
        <script src="/public/scripts/showpassword.js"></script>
        
        <script>
            $(document).ready(function(){
              $('[data-toggle="popover"]').popover();   
            });
        </script>
        
        <link rel="stylesheet" href="/public/styles/animate.css">
        <script src="/public/scripts/wow.min.js"></script>
        <script type="text/javascript">
            new WOW().init();
        </script>
        
        
        
        <style>
            
            
            
            
    body {
     
    background: none;
    /*font: 400 16px Lato, sans-serif;
    line-height: 1.8;
    color: black;*/
    }
html {
  background:/*linear-gradient(to left, rgba(89,106,114, 0.6), rgba(206,220,23, 0.4)),*/ url(/public/image/rabstol_net_gold_05.jpg) no-repeat center center fixed;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}

.jumbotron {
    background-color: transparent !important;
    
    padding: 100px 25px;
    /*font-family: Montserrat, sans-serif;*/
  }
 .form-group label {color: black;}
 .form-group .form-control  {background-image: linear-gradient(#9c27b0,#9c27b0),linear-gradient(#232323,#D2D2D2);}

  /*.container {
      background: rgba(255,255,255,0.7);
  }*/
  @media screen and (max-width: 600px) {
  #hide {
    visibility: hidden;
    display: none;
  }
}
  
</style>
        
    </head>
    <body>
        <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container" style="background:none;">
                <a class="navbar-brand" href="/" style="color: white;">Investfotnite</a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <?php if (isset($_SESSION['account']['id'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/faq">FAQ</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/dashboard/tariffs">Инвестиции</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/dashboard/referrals">Рефералы</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/dashboard/history">История</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/account/profile">Профиль</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/account/logout">Выход</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/faq">FAQ</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/account/register">Регистрация</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/account/login">Вход</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
        
        <div class="container" style="background: rgba(255,255,255,0.7); /* padding: 0px;*/ "> 
            
        <?php echo $content; ?> 
        </div> 
        <footer class="footer" >     <!-- style="background-color: transparent !important;" -->       
            <div class="container"> 
                <div class="row">
                    <div class="col-lg-6 col-sm-6">
                        
                            <span>Мы принимаем к оплате</span>
                            <div class="banner-f">
                                <a href="//free-kassa.ru/"><img src="//www.free-kassa.ru/img/fk_btn/17.png" title="Приём оплаты на сайте картами"></a>
                            </div>
                          
                    </div>
                    
                    <div class="col-lg-6 col-sm-6">
                    
                        <span style="color: white;">Наши контакты:</span>
                        <p style="color: white;"> report@investfotnite.ru</p>
                    
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div> 
        </footer> 
            
       
            
    </body>     
</html>