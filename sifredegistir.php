<?php ob_start(); ?> 
<!doctype html>
<html lang="tr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="icon" href="icon.png">
     <!--meta http-equiv="refresh" content="9000;url=otomatikcikis.php" /-->
    <title>Anasayfa</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	  <script src="bootstrap/assets/js/vendor/holder.min.js"></script>
	  <script src="bootstrap/assets/js/vendor/popper.min.js"></script>
	  <script src="bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Custom styles for this template -->
    <link href="css/anaekran.css" rel="stylesheet">
  </head>

  <body>
			<?php
	        if(!isset($_SESSION)) 
				 { 
				   session_start(); 
				 } 
				 include('php/sqlBaglanti.php');
				 $GLOBALS['hataMesaj'] = " ";
         $GLOBALS['mevcutTarih'] = "";
         $GLOBALS['secilenTarih'] = "";
         $GLOBALS['secilenSaat'] = "";
         $GLOBALS['yazdir'] = "";
         $GLOBALS['select'] = array("","","","","","","","",""); 
         $GLOBALS['bilgi'] = "";
         $Rhidden = "";
         $GLOBALS['yonetici'] = 0;
		 
				 //giriş yapılmışsa ne gerek var tekrar giriş yapmaya :D
				 if(isset($_SESSION))
				 {
					 if(isset($_SESSION['ogr_no']) && isset($_SESSION['sifre']))
					 {
						 include('php/girisIslemleri.php');
						 if(giris($sql,$_SESSION['ogr_no'],$_SESSION['sifre']))
						 {
							 //header("location:index.php");
						 }
						 else
						 {
							 header("location:php/cikisYap.php");
						 }
		 
					 }
					 else{header('location:giris.php');}
				 }
				 else{header('location:giris.php');}
	 
				
         include('php/veriIslemler.php');
         $GLOBALS['yonetici'] = yetkiSorgula($sql,$_SESSION['ogr_no']);

         if(isset($_POST['anasayfa']))
         header('location:index.php');
       if(isset($_POST['aktif']))
       {
         if(isset($_GET['ogr_id']))
           header('location:aktifrandevular.php?ogr_id='.$_GET['ogr_id']);
         else
         header('location:aktifrandevular.php');
       }
       if(isset($_POST['gecmis']))
       {
         if(isset($_GET['ogr_id']))
           header('location:gecmisrandevular.php?ogr_id='.$_GET['ogr_id']);
         else
         header('location:gecmisrandevular.php');
       }
       if(isset($_POST['yonetici']) && $GLOBALS['yonetici'])
       {
         header('location:ayarlar.php');
       }
       if(isset($_POST['hesap']))
       {
         header('location:hesap.php');
       }


       if(isset($_POST['sifre']))
       {
           if(isset($_POST['msifre']) && isset($_POST['ysifre']) && isset($_POST['ytsifre']))
           {
            $sonuc = ogr_ara_ogrno($sql,$_SESSION['ogr_no']);
            $row = $sonuc->fetch_assoc();
                //echo $row['sifre']."  ". $_POST['msifre'];
                if($row['sifre'] == $_POST['msifre'])
                     {
                        if($_POST['ysifre'] == $_POST['ytsifre'])
                        {
                            if(strlen($_POST['ysifre']) >= 6)
                            {
                            sifremiSifirla($sql,ogrno_to_id($sql,$_SESSION['ogr_no']),$_POST['ysifre']);
                            $_SESSION['sifre'] = $_POST['ysifre'];
                            $GLOBALS['hataMesaj'] ="Şifreniz değişti";
                            }
                            else
                            $GLOBALS['hataMesaj'] ="Şifreniz 6 haneden uzun olmalı";
                            
                        }
                        else
                         $GLOBALS['hataMesaj'] ="Yeni şifreniz ve yeni şifre tekrarı uyuşmuyor";
                    }else
                    $GLOBALS['hataMesaj'] ="Mevcut Şifreniz uyuşmuyor";
                    
                }else
                $GLOBALS['hataMesaj'] ="Formlar boş olamaz";
                
           }
           

       $GLOBALS['yazdir'] .= '';

       if(isset($_POST['duzenle']))
       {
         header('location:hesap.php');
       }



         $sql->close();

			  ?>  
	  
    <header>
      <div class="collapse bg-dark" id="navbarHeader">
        <div class="container">
          <div class="row">
            <div class="col-sm-8 col-md-7 py-4">
            <h4 class="text-white">Ondokuz Mayıs Üniversitesi Devlet Konservatuvarı</h4>
              <p class="text-muted">Samsun/Atakum</p>
            </div>
            <div class="col-sm-4 offset-md-1 py-4">
              <h4 class="text-white">Yardımcı Siteler</h4>
              <ul class="list-unstyled">
                <li><a href="https://www.omu.edu.tr/tr" class="text-white">OMÜ</a></li>
			        	<li><a href="https://konservatuvar.omu.edu.tr/tr" class="text-white">Konservatuvar</a></li>
                <li><a href="https://samsunmyo.omu.edu.tr/tr" class="text-white">Meslek Yüksekokulu</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="navbar navbar-dark bg-dark box-shadow">
        <div class="container d-flex justify-content-between">
		<div class="d-flex justify-content-start">
          <a href="index.php" class="navbar-brand d-flex align-items-center">
            <!--svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg-->
            <img src="http://www.omu.edu.tr/sites/all/themes/anasayfa/images/Kurumsal/OMULogo-EN-PNG.png" width="40" height=40 style="margin-right:10px;">
			<strong>Ondokuz Mayıs Üniversitesi</strong>
          </a>
			</div>
		  <div class="d-flex justify-content-end">
		  <a class="btn btn-danger mr-2" href="php/cikisYap.php" >
		  	Çıkış yap
		  </a>
          <button class="navbar-toggler ml-0" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>
      </div>
		</div>
    </header>

    <main role="main">

      <section class="jumbotron text-center">
        <div class="container">
          <h1 class="jumbotron-heading">Müzik odası randevu sistemi</h1>
          <p class="lead text-muted"></p>
          <p>
          <form action="" method="post">
            <button name="anasayfa" class="btn btn-succes my-2">Anasayfa</button>
            <button name="aktif"class="btn btn-primary my-2">Aktif randevularım</button>
            <button name="gecmis" class="btn btn-secondary my-2">Geçmiş randevularım</button>
            <button name="hesap" class="btn btn-info my-2">Hesabım</button>
			      <?php if($GLOBALS['yonetici']) echo '<button name="yonetici" class="btn btn-danger my-2">Yönetici Paneli</button>'; ?>
            </form>
          </p>
        </div>
        <div class="album py-5 bg-light mt-4">
		 <div class="container mt-3 mb-0 "style="" >
		 	<form class="form-control justify-content-center border-0"  action="" method="post">
             <p class="lead text-muted">Kullanıcı Bilgilerinizi düzenleyin</p>

             <?php echo $GLOBALS['yazdir']; ?>
             <label for="msifre" class="text-muted">Mevcut Şifre</label>
       <input class="form-control mb-4" type="password" id="msifre" name="msifre" value="">

       <label for="ysifre" class="text-muted">Yeni Şifre</label>
       <input class="form-control mb-4" type="password" id="ysifre" name="ysifre" value="">

       <label for="ytsifre" class="text-muted">Yeni Şifre tekrar</label>
       <input class="form-control mb-5" type="password" id="ytsifre" name="ytsifre" value="">
                
				<input type="submit" class="btn btn-sm btn-primary btn-block mt3 mb-2" name="sifre" value="Şifre Değiştirmek için tıklayınız">
                <input type="submit" class="btn btn-sm btn-danger mt-3 mb-2" name="duzenle" value="Bilgileri Güncelle">
                <p class="lead text-muted"><?php echo $GLOBALS['hataMesaj']; ?></p>
                 
        
			</form> 	
            </div>
		 </div>
      </section>

      
        <div class="container mt-0">

        
      </div>

    </main>

    <footer class="text-muted">
      <div class="container">
        <p class="float-right">
          <a href="#">Üste Çık</a>
        </p>
        <a href="http://bariscangungor.com.tr"><p>Barışcan Güngör &copy; 2021</p></a>
      </div>
    </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="/bootstrap-4.0.0/assets/js/vendor/popper.min.js"></script>
    <script src="/bootstrap-4.0.0/dist/js/bootstrap.min.js"></script>
    <script src="/bootstrap-4.0.0/assets/js/vendor/holder.min.js"></script>
  </body>
</html>
<?php ob_end_flush(); ?>
