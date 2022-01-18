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
    <title>Kaydol</title>
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
            
            if(isset($_POST['Giris']))
            {
                header('location:giris.php');
            }

            if(isset($_POST['Kaydol']))
            {
                $ogr_no = $_POST['ogr_no'];
                $ad = $_POST['ad'];
                $tel_no = $_POST['tel_no'];
                $sifre = $_POST['sifre'];
                $eposta =$_POST['eposta'];
                
                $hata = 0;

                if(!(strlen($ogr_no)==8))//öğrenci numarası 8 haneli olmak zorunda
                {
                    $hata = 1;
                    $GLOBALS['hataMesaj'] .= "Öğrenci numaranız 8 haneli olmak zorunda <br>";
                }
                if(!(strlen($tel_no) == 11)) //telefon numarası 11 haneli olmak zorunda
                {
                    $hata = 1;
                    $GLOBALS['hataMesaj'] .= "Telefon numaranız başında 0 olarak yazılmalıdır. <br>";
                }
                if(!(strlen($sifre) >= 6)) //şifre güvenliği için 6 hane yaptım (8'de yapılabilir.)
                {
                    $hata = 1;
                    $GLOBALS['hataMesaj'] .= "Şifreniz minimun 6 haneli olmalıdır. <br>";
                }
                if(strlen($eposta)<=5)
                {
                    $hata = 1;
                    $GLOBALS['hataMesaj'] .= "Eposta geçersiz <br>";
                }

                
                if(!$hata)
                {
                    include('php/girisIslemleri.php');
                    $hataMesaji = kayitHatasi($sql,$ogr_no,$tel_no);

                    if($hataMesaji == "")
                    {
                        if(kaydol($sql,$ogr_no,$sifre,$ad,$tel_no,0,$eposta))
                        {
                            header('location:giris.php');
                        }
                    }
                    else
                    {
                        $GLOBALS['hataMesaj'] .= $hataMesaji;
                    }
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
                <form method="post" action="" class="box">
                    <h1>Kaydol</h1>
                    <p class="text-muted"> Okul numaranızı ve şifrenizi giriniz</p> 
                    <input type="text" name="ogr_no" placeholder="Okul Numaranız"> 
                    <input type="text" name="ad" placeholder="Ad Soyad"> 
                    <input type="text" name="tel_no" placeholder="Telefon Numaranız"> 
                    <input type="text" name="eposta" placeholder="E-posta"> 
                    <input type="password" name="sifre" placeholder="Şifreniz"> 
                    <input type="submit" name="Kaydol" value="Kaydol" href="#">
                    <input type="submit" name="Giris" value="Giriş yap" href="#" style="border: 2px solid #89e61f; padding: 5px 20px;">
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