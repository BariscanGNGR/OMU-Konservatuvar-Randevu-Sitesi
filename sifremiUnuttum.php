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
    <title>Şifremi unuttum</title>
    </head>

    <body>

    <?php
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

            function RandomString()
            {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $randstring = '';
                for ($i = 0; $i < 20; $i++) {
                    $randstring .= $characters[rand(0, strlen($characters))];
                }
                return $randstring;
            }
            
            if(isset($_POST['gonder']))
            {
               if(isset($_POST['eposta']) && isset($_POST['ogr_no']))
                {   include('php/mail.php');
                    include('php/veriIslemler.php');
                    include('php/girisIslemleri.php');
                    if(epostaKontrol($sql,$_POST['eposta'],$_POST['ogr_no']))
                    {
                        $rand =RandomString();
                        yeniSifreTalep($sql,$_POST['eposta'],$rand);
                        epostaGonder($_POST['eposta'],
                        "Ondokuz Mayıs Üniversitesi","Güzel Sanatlar randevu sistemi şifre sıfırlama <a href='localhost/yeniSifre.php?id=".ogrno_to_id($sql,$_POST['ogr_no'])."&sk=".$rand."'>linkiniz</a>");
                        $GLOBALS['hataMesaj'] = "Sıfırlama bağlantısı E-posta Adresinize gönderilmiştir";
                        //echo RandomString();
                    }
                    else
                    $GLOBALS['hataMesaj'] = "e-posta veya öğrenci no bulunamadı";
                }
                else
                $GLOBALS['hataMesaj'] = "form boş olamaz";
            }

            if(isset($_POST['giris']))
            {
                header('location:giris.php');
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
                    <h1>Şifremi unuttum</h1>
                    <p class="text-muted"> Okul numaranızı ve şifrenizi giriniz</p> 
                    <input type="text" name="ogr_no" placeholder="Öğrenci numaranız"> 
                    <input type="text" name="eposta" placeholder="E-Posta adresiniz"> 
                    <input type="submit" name="gonder" value="E-Postama mesaj gönder" href="#">
                    <input type="submit" name="giris" value="Giriş" href="#" style="border: 2px solid #89e61f; padding: 5px 20px;">
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