<?php ob_start(); ?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link href="css/giris.css" rel="stylesheet">
        <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <!--link rel="icon" href="icon.png"-->
    <title>Giriş</title>
    </head>

    <body>

    <?php
    //ini_set('display_errors', 1);
        if(!isset($_SESSION)) 
        { 
          session_start(); 
        } 
        include('php/sqlBaglanti.php');
        $GLOBALS['hataMesaj'] = " ";

        //giriş yapılmışsa ne gerek var tekrar giriş yapmaya :D
        if(isset($_SESSION))
        {
            if(isset($_SESSION['ogr_no']) && isset($_SESSION['sifre']))
            {
                include('php/girisIslemleri.php');
                if(giris($sql,$_SESSION['ogr_no'],$_SESSION['sifre']))
                {
                    header("location:index.php");
                }
                else
                {
                    header("location:php/cikisYap.php");
                }

            }
            
            if(isset($_POST['Kaydol']))
            {
                header('location:kaydol.php');
            }

            if(isset($_POST['Giris']))
            {
                $ogr_no = $_POST['ogr_no'];
                $sifre = $_POST['sifre'];
                
                include('php/girisIslemleri.php');
                
                if(giris($sql,$ogr_no,$sifre))
                {
                    header('location:index.php');
                }
                else{
                    $GLOBALS['hataMesaj'] .= "Giriş bilgileriniz geçersiz";
                }
            }
            
        }

        $sql->close();
    ?>

    <div class="container">
    <div class="row" style="    -ms-flex-align: center;
    -ms-flex-pack: center;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;">
        <div class="col-md-6">
            <div class="card" style="background:none; border:none;">
                <form method="post" action="" class="form box">
                    <h1>Giriş</h1>
                    <p class="text-muted"> Okul numaranızı ve şifrenizi giriniz</p> 
                    <input type="text" name="ogr_no" placeholder="Okul Numaranız"> 
                    <input type="password" name="sifre" placeholder="Şifreniz"> 
                    <a class="forgot text-muted" href="sifremiUnuttum.php">Şifremi Unuttum</a> 
                    <input type="submit" name="Giris" value="Giriş yap" href="#">
                    <input type="submit" name="Kaydol" value="Kaydol" href="#" style="border: 2px solid #89e61f; padding: 5px 20px;">
                    <div class="col-md-12">

                    <p class="text-muted"><?php echo $GLOBALS['hataMesaj']; ?>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    </body>
</html>
<?php ob_end_flush(); ?>