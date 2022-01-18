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
	 
				 date_default_timezone_set("Europe/Istanbul");
				 $date = new DateTime('x');
				 $GLOBALS['mevcutTarih'] = $date->format('Y-m-d');

         if(isset($_POST['tarih']))
         {
           $GLOBALS['secilenTarih'] = $_POST['tarih'];
           $_SESSION['tarih'] = $_POST['tarih'];
         }
         else
         {
          $GLOBALS['secilenTarih'] = $GLOBALS['mevcutTarih'] ;
         }
         
         if(isset($_POST['saat']))
         {
          $GLOBALS['select'][$_POST['saat']-1] = "selected";
          $_SESSION['saat'] = $_POST['saat'];
        }
				
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

				 if(isset($_POST['ara']))
				 {
            $tarih = $_POST['tarih'];
            $saat = $_POST['saat'];
            $sonucDizi = tarihsaatSorgula($sql,$tarih,$saat);

            $sonucOda = $sonucDizi[0];
            $odaSatir = mysqli_num_rows($sonucOda);
            $sonucRandevu = $sonucDizi[1];
            $randevuSatir = mysqli_num_rows($sonucRandevu);

            $doluOdalar = array();

            if($GLOBALS['yonetici'])
              $Rhidden=0;
              else
            $Rhidden = RandevuGunSorgulama($sql,$tarih,ogrno_to_id($sql,$_SESSION['ogr_no']));

            if($randevuSatir > 0)
            {
                while ($row = $sonucRandevu->fetch_assoc()) 
                {
                    array_push($doluOdalar,array($row['oda_id'],$row['ogrenci_id'],$row['id']));
                }
            }

            //echo '<br><br>';
            if($odaSatir > 0)
            {
                while ($row = $sonucOda->fetch_assoc()) 
                {
                   // echo '<br>'.$row['id'];
                    $dolu = false;

                    for($i = 0 ; $i < count($doluOdalar) ; $i++)
                    {
                      if($row['id'] == $doluOdalar[$i][0])
                      {
                        $dolu=true;
                        break;
                      }
                    }

                    if($dolu)
                    {
                      $value = ""; $hidden="";
                     // echo strval($doluOdalar[$i][1]) ." " . strval(ogrno_to_id($sql,$_SESSION['ogr_no']));

                      if($doluOdalar[$i][1] == ogrno_to_id($sql,$_SESSION['ogr_no']) || $GLOBALS['yonetici'])
                        $value = $doluOdalar[$i][2];
                      else
                      {
                        if(!$GLOBALS['yonetici'])
                          $hidden = "hidden";
                      }
                      $isim="";
                      if($GLOBALS['yonetici'])
                      {
                        $ogrBilgiler = ogrid_to_isim($sql,$doluOdalar[$i][1]);
                        $isim = '<a href="aktifrandevular.php?ogr_id='.$doluOdalar[$i][1].'">'.$ogrBilgiler[0]  .'<br>' .$ogrBilgiler[1]. '</a><br> <a href="tel:'.$ogrBilgiler[2].'">'.$ogrBilgiler[2].'</a><br> ';
                      }

                      $yazdir .= '<div class="col-md-4">
                   <div class="card mb-4 box-shadow" >
                   <img class="card-img-top img-responsive" data-src="holder.js/100px225" src="resimler/red.jpg" alt="">
                   <div class="card-body">
                  <p class="card-title font-weight-bold">'.$isim.$row['oda_isim'].'</p>
                     <p class="card-text">DOLU<br>'.$_POST['tarih'].' <br>'.saatBirimleri($_POST['saat']).'</p>
                     <div class="d-flex justify-content-between align-items-center">
                       <div class="btn-group">
                    <form action="" method="post">
                         <button type="submit" class="btn btn-sm btn-outline" name="randevuiptal" value="'. $value .'" '.$hidden.'>Randevu İptal</button>
                    <button type="submit" class="btn btn-sm btn-outline-secondary" name="goruntule" value="'.$row['id'].'">Odayı görüntüle</button>
                      </form>
                       </div>
                       <small class="text-muted"></small>
                     </div>
                   </div>
                    </div>
                   </div>';
                    }
                    else
                    {
                      $yazdir .= '<div class="col-md-4">
                   <div class="card mb-4 box-shadow" >
                   <img class="card-img-top img-responsive" data-src="holder.js/100px225" src="resimler/green.jpg" alt="">
                   <div class="card-body">
                  <p class="card-title font-weight-bold">'.$row['oda_isim'].'</p>
                     <p class="card-text">'.$_POST['tarih'].' <br>'.saatBirimleri($_POST['saat']).'</p>
                     <div class="d-flex justify-content-between align-items-center">
                       <div class="btn-group">
                    <form action="" method="post">
                         <button type="submit" class="btn btn-sm btn-outline" name="randevuAl" value="'.$row['id'].' "'.$Rhidden.'>Randevu Al</button>
                    <button type="submit" class="btn btn-sm btn-outline-secondary" name="goruntule" value="'.$row['id'].'">Odayı görüntüle</button>
                      </form>
                       </div>
                       <small class="text-muted"></small>
                     </div>
                   </div>
                    </div>
                   </div>';
                    }
                }
            }
				 }
				 else
				 {
            $sonuc =odalariSorgula($sql);

            $satir = mysqli_num_rows($sonuc);
            if($satir > 0)
            {
                while ($row = $sonuc->fetch_assoc()) 
                {

                  $yazdir .= '<div class="col-md-4">
                  <div class="card mb-4 box-shadow" >
                   <img class="card-img-top img-responsive" data-src="holder.js/100px225" src="resimler/gri.jpg" alt="">
                   <div class="card-body">
                  <p class="card-title font-weight-bold">'.$row['oda_isim'].'</p>
                     <p class="card-text">'.$GLOBALS['secilenTarih'].'</p>
                     <div class="d-flex justify-content-between align-items-center">
                       <div class="btn-group">
                    <form action="" method="post">
                         <button type="submit" class="btn btn-sm btn-outline" name="ranevu" value="'.$row['id'].'" hidden>Randevu Al</button>
                    <button type="submit" class="btn btn-sm btn-outline-secondary" name="goruntule" value="'.$row['id'].'">Odayı görüntüle</button>
                      </form>
                       </div>
                       <small class="text-muted"></small>
                     </div>
                   </div>
                    </div>
                   </div>';
                    
                }
            }
            
				 }


         //echo $_SESSION['saat'];
         //echo $_SESSION['tarih'];
         if(isset($_POST['randevuAl']))
         {
            if(randevuAl($sql,$_POST['randevuAl'],ogrno_to_id($sql,$_SESSION['ogr_no']),$_SESSION['tarih'],$_SESSION['saat']))
              $GLOBALS['bilgi']= '<p class="lead text-muted">Randevu Başarılıyla alındı <br> '.$_SESSION['tarih'].' '.saatBirimleri($_SESSION['saat']).'</p>';
              header('location:index.php');
            }

         if(isset($_POST['randevuiptal']))
         {
           echo $_POST['randevuiptal'];
           if(randevuIptal($sql,$_POST['randevuiptal']))
            $GLOBALS['bilgi'] = '<p class="lead text-muted">Randevu Başarılıyla İptal edildi</p>';
            header('location:index.php');
         }
         if(isset($_POST['goruntule']))
         {
           header('location:oda.php?id='.$_POST['goruntule']);
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
		  
		 <div class="container mt-5 mb-0 "style="max-width: 300px;" >
		 	<form class="form-control justify-content-center"  action="" method="post">

			 <label for="tarih" class="text-muted">Tarih ve Saati Seçiniz</label>
                 <input placeholder="Tarih Seçiniz" type="date" name="tarih" id="tarih" class="form-control mb-2" value="<?php echo $GLOBALS['secilenTarih']; ?>" min="<?php echo $GLOBALS['mevcutTarih']; ?>" >
				<select list="saat" class="form-control mb-2" name="saat" id="saat">
                              
                                <option value="1" <?php echo $GLOBALS['select'][0];?>>8:00 - 9:00</option>
                                <option value="2" <?php echo $GLOBALS['select'][1];?>>9:00 - 10:00</option>
                                <option value="3" <?php echo $GLOBALS['select'][2];?>>10:00 - 11:00</option>
                                <option value="4" <?php echo $GLOBALS['select'][3];?>>11:00 - 12:00</option>
                                <option value="5" <?php echo $GLOBALS['select'][4];?>>12:00 - 13:00</option>
                                <option value="6" <?php echo $GLOBALS['select'][5];?>>13:00 - 14:00</option>
                                <option value="7" <?php echo $GLOBALS['select'][6];?>>14:00 - 15:00</option>
                                <option value="8" <?php echo $GLOBALS['select'][7];?>>15:00 - 16:00</option>
                                <option value="9" <?php echo $GLOBALS['select'][8];?>>16:00 - 17:00</option>
				</select>
				<input type="submit" class="btn btn-sm btn-secondary btn-block mt3 mb-2" name="ara" value="Göster">
        <?php echo $GLOBALS['bilgi']; ?>
        
			</form> 	
		 </div>
      </section>

      <div class="album py-5 bg-light">
        <div class="container mt-0">

          <div class="row">
			 
			<?php echo $GLOBALS['yazdir']; ?>  		
			
			
          </div>
        </div>
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
