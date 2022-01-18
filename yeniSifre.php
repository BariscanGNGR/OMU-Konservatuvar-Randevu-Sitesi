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

            if(isset($_GET['id']) && isset($_GET['sk']))
                {   
                    include('php/veriIslemler.php');
                    include('php/girisIslemleri.php');
                    if(sifremiUnuttumKontrol($sql,$_GET['id'],$_GET['sk']))
                    {
                        if($_GET['sk'] != ""){
                            if(isset($_POST['gonder']))
                            {
                            if(isset($_POST['sifre']) && isset($_POST['tekrar']))
                            {
                                if($_POST['sifre'] == $_POST['tekrar']){
                                    sifreDegis($sql,$_GET['id'],$_POST['sifre']);
                                    kodSifirla($sql,$_GET['id']);
                                    header('location:giris.php');
                                }
                                    else $GLOBALS['hataMesaj'] = "şifre ve şifre tekrarınız aynı olmalıdır";
                            }
                            else $GLOBALS['hataMesaj'] = "Formlar boş olamaz";
                            }
                        }else header('location:giris.php');
                    }
                    else
                    header('location:index.php');;
                }
                else
                header('location:index.php');
            


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
                    <input type="password" name="sifre" placeholder="Yeni Şifreniz"> 
                    <input type="password" name="tekrar" placeholder="Yeni şifrenizin tekrarı"> 
                    <input type="submit" name="gonder" value="Şifremi değiştir" href="#">
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