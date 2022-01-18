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
         $GLOBALS['bilgi'] = "";
         $Rhidden = "";
         $GLOBALS['yonetici'] = 0;
         $GLOBALS['tabloYazdir']="";
		 
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
         
        
				
         include('php/veriIslemler.php');
         $GLOBALS['yonetici'] = yetkiSorgula($sql,$_SESSION['ogr_no']);

         if(!$GLOBALS['yonetici'])
         header('location:index.php');
         
         if(!isset($_SESSION['kullanici']))
         $_SESSION['kullanici']="";
         if(!isset($_SESSION['oda']))
         $_SESSION['oda']="";

         if(isset($_POST['oda']))
         {
           if($_POST['oda'] == ".")
           {
             $_SESSION['oda'] = "1";
             $_SESSION['kullanici'] = "0";
           }
         }
         if(isset($_POST['kullanici']))
         {
           if($_POST['kullanici'] == ".")
           {
             $_SESSION['kullanici'] = "1";
             $_SESSION['oda'] = "0";
           }
         }

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



          $arama = 0;
          if(isset($_POST['ogrnoara']))
          {
            $sonuc = ogr_ara_ogrno($sql,$_POST['ogr_no']);
            if($row = $sonuc->fetch_assoc())
            {
              $yetki="";$yetkiname="";
              if($row['yetkiler'] == "1")
              {
                $yetki = "Yetki sil";
                $yetkiname="yetkisil";
              }
              else
              {
                $yetki="Yönetici yetki ver";
                $yetkiname="yetkilendir";
              }
            $GLOBALS['tabloYazdir'] .= 
            '<tr>
            <th scope="row"><a href="aktifrandevular.php?ogr_id='.$row['id'].'">'.$row['ogrenci_numara'].'</a></th>
            <td>'.$row['ad_soyad'].'</td>
            <td ><a href="mail:'.$row['e-posta'].'">'.$row['e-posta'].'</a></td>
            <td ><a href="tel:'.$row['telefon_numara'].'">'.$row['telefon_numara'].'</a></td>
            <td ><button class="btn btn-sm btn-danger" name="'.$yetkiname.'" value="'.$row['id'].'" type="submit">'.$yetki.'</button></td>
            <td><button class="btn btn-sm btn-danger" name="kullaniciSil" type="submit" value="'.$row['id'].'">KULLANICIYI SİL!</button> </td>
         </tr>';
            }
          }
          if(isset($_POST['adsoyadara']))
          {
            $sonuc = ogr_ara_isim($sql,$_POST['ad_soyad']);
            while($row = $sonuc->fetch_assoc())
            {
              $yetki="";$yetkiname="";
              if($row['yetkiler'] == "1")
              {
                $yetki = "Yetki sil";
                $yetkiname="yetkisil";
              }
              else
              {
                $yetki="Yönetici yetki ver";
                $yetkiname="yetkilendir";
              }
              $GLOBALS['tabloYazdir'] .= 
              '<tr>
              <th scope="row"><a href="aktifrandevular.php?ogr_id='.$row['id'].'">'.$row['ogrenci_numara'].'</a></th>
              <td>'.$row['ad_soyad'].'</td>
              <td ><a href="mail:'.$row['e-posta'].'">'.$row['e-posta'].'</a></td>
              <td ><a href="tel:'.$row['telefon_numara'].'">'.$row['telefon_numara'].'</a></td>
              <td ><button class="btn btn-sm btn-danger" name="'.$yetkiname.'" value="'.$row['id'].'" type="submit">'.$yetki.'</button></td>
              <td><button class="btn btn-sm btn-danger" name="kullaniciSil" type="submit" value="'.$row['id'].'">KULLANICIYI SİL!</button> </td>
           </tr>';
            }
          }
          if(isset($_POST['telefonara']))
          {
            $sonuc = ogr_ara_ogrno($sql,$_POST['ogr_no']);
            if($row = $sonuc->fetch_assoc())
            {
              $yetki="";$yetkiname="";
              if($row['yetkiler'] == "1")
              {
                $yetki = "Yetki sil";
                $yetkiname="yetkisil";
              }
              else
              {
                $yetki="Yönetici yetki ver";
                $yetkiname="yetkilendir";
              }
            $GLOBALS['tabloYazdir'] .= 
            '<tr>
            <th scope="row"><a href="aktifrandevular.php?ogr_id='.$row['id'].'">'.$row['ogrenci_numara'].'</a></th>
            <td>'.$row['ad_soyad'].'</td>
            <td ><a href="mail:'.$row['e-posta'].'">'.$row['e-posta'].'</a></td>
            <td ><a href="tel:'.$row['telefon_numara'].'">'.$row['telefon_numara'].'</a></td>
            <td ><button class="btn btn-sm btn-danger" name="'.$yetkiname.'" value="'.$row['id'].'" type="submit">'.$yetki.'</button></td>
            <td><button class="btn btn-sm btn-danger" name="kullaniciSil" type="submit" value="'.$row['id'].'">KULLANICIYI SİL!</button> </td>
         </tr>';
            }
          }


          if($_SESSION['kullanici'] == "1")
          {
            $_SESSION['oda'] = "0";
            $_SESSION['kullanici'] = "1";

            $GLOBALS['yazdir']=
          '      <form method="post" class="form-control border-0">

          <div class="row justify-content-center mb-4 mt-4">
          <div class="col-2 ml-3">
          <p class="text-muted">Öğrenci Numarası</p>
        </div><div class="col-6">
          <input type="text" class="form-control ml-3" name="ogr_no">
        </div>
           <div class="col-2">
          <button type="submit" name="ogrnoara" class="btn btn-sm btn-primary mt3 mb-2">Ara</button>
        </div>
          </div>
  
          <div class="row justify-content-center mb-4">
          <div class="col-2 ml-3">
          <p class="text-muted">Ad Soyad</p>
        </div><div class="col-6">
          <input type="text" class="form-control ml-3" name="ad_soyad">
        </div>
           <div class="col-2">
          <button type="submit" name="adsoyadara" class="btn btn-sm btn-primary mt3 mb-2">Ara</button>
        </div>
          </div>
  
          
          <div class="row justify-content-center mb-4">
          <div class="col-2 ml-3">
          <p class="text-muted">Telefon Numarası</p>
        </div><div class="col-6">
          <input type="text" class="form-control ml-3" name="telefon_no">
        </div>
           <div class="col-2">
          <button type="submit" name="telefonara" class="btn btn-sm btn-primary mt3 mb-2">Ara</button>
        </div>
          </div>
  
        </form>
  
  
        <table class="table table-sm table-responsive mt-5">
          <thead>
          <tr>
            <th scope="col">Öğrenci no</th>
            <th scope="col">Ad soyad</th>
            <th scope="col">E-posta</th>
            <th scope="col">Telefon Numarası</th>
            <th scope="col">Yetki</th>
            <th scope="col">SİL!</th>
         </tr>
         </thead>
         <tbody>
          <form method="post" action="">
         '. $GLOBALS["tabloYazdir"].'
          </form>
  
  
        </tbody>
  
        </table>';

          }
          if($_SESSION['oda'] == "1")
          {
            $_SESSION['kullanici'] = "0";
            $_SESSION['oda'] = "1";

            $sonuc = ayarlarOdaSorgula($sql);
            while($row = $sonuc->fetch_assoc())
            {
              $degerler = array($row['oda_aktif'],$row['1'],$row['2'],$row['3'],$row['4'],$row['5'],$row['6'],$row['7'],$row['8'],$row['9']);
              $degerler2 = array("","","","","","","","","","");

              for($i = 0 ; $i < 10; $i++)
              {
                if($degerler[$i] == "1")
                  $degerler2[$i] = "checked";
              }

              $GLOBALS['tabloYazdir'] .= 
              ' <form method="post" action=""><tr>
              <th scope="row"><a href="oda.php?id='.$row['id'].'">'.$row['id'].'</a></th>
              <td><input type="text" name="oda_isim" value="'.$row['oda_isim'].'"></td>
              <td> <input class="ml-2" type="checkbox" value="0" name="oda_aktif" '.$degerler2[0].'> </td>
              <td> <input class="ml-2" type="checkbox" value="0" name="1" '.$degerler2[1].'> </td>
              <td> <input class="ml-2" type="checkbox" value="0" name="2" '.$degerler2[2].'> </td>
              <td> <input class="ml-2" type="checkbox" value="0" name="3" '.$degerler2[3].'> </td>
              <td> <input class="ml-2" type="checkbox" value="0" name="4" '.$degerler2[4].'> </td>
              <td> <input class="ml-2" type="checkbox" value="0" name="5" '.$degerler2[5].'> </td>
              <td> <input class="ml-2" type="checkbox" value="0" name="6" '.$degerler2[6].'> </td>
              <td> <input class="ml-2" type="checkbox" value="0" name="7" '.$degerler2[7].'> </td>
              <td> <input class="ml-2" type="checkbox" value="0" name="8" '.$degerler2[8].'> </td>
              <td> <input class="ml-2" type="checkbox" value="0" name="9" '.$degerler2[9].'> </td>
              <td><button class="btn btn-sm btn-danger" name="duzenle" type="submit" value="'.$row['id'].'">Düzenle</button> </td>
              <td><button class="btn btn-sm btn-danger" name="sil" type="submit" value="'.$row['id'].'">Sil</button> </td>
           </tr></form>';


            }
            $GLOBALS['tabloYazdir'] .= 
              ' <form method="post" action=""><tr>
              <th scope="row">yeni oda</th>
              <td><input type="text" name="oda_isim" value="" placeholder="yeni oda ismi"></td>
              <td> <input class="ml-2" type="checkbox" value="0" name="oda_aktif" checked> </td>
              <td> <input class="ml-2" type="checkbox" value="0" name="1" checked> </td>
              <td> <input class="ml-2" type="checkbox" value="0" name="2" checked> </td>
              <td> <input class="ml-2" type="checkbox" value="0" name="3" checked> </td>
              <td> <input class="ml-2" type="checkbox" value="0" name="4" checked> </td>
              <td> <input class="ml-2" type="checkbox" value="0" name="5" checked> </td>
              <td> <input class="ml-2" type="checkbox" value="0" name="6" checked> </td>
              <td> <input class="ml-2" type="checkbox" value="0" name="7" checked> </td>
              <td> <input class="ml-2" type="checkbox" value="0" name="8" checked> </td>
              <td> <input class="ml-2" type="checkbox" value="0" name="9" checked> </td>
              <td><button class="btn btn-sm btn-danger" name="olustur" type="submit" value="Yeni" >Yeni oda oluştur</button> </td>
           </tr></form>';
            $GLOBALS['yazdir'] .=
            '   <table class="table table-sm table-responsive mt-5" >
            <thead>
            <tr>
              <th scope="col">Oda id</th>
              <th scope="col">Oda isim</th>
              <th scope="col">Oda Aktif</th>
              <th scope="col">8:00 - 9:00</th>
              <th scope="col">9:00 - 10:00</th>
              <th scope="col">10:00 - 11:00</th>
              <th scope="col">11:00 - 12:00</th>
              <th scope="col">12:00 - 13:00</th>
              <th scope="col">13:00 - 14:00</th>
              <th scope="col">14:00 - 15:00</th>
              <th scope="col">15:00 - 16:00</th>
              <th scope="col">16:00 - 17:00</th>
              <th scope="col">Onayla</th>
              <th scope="col">Sil</th>
           </tr>
           </thead>
           <tbody>
           
          ' . $GLOBALS["tabloYazdir"].'
  
    
  
    
          </tbody>
    
          </table>';
  
          }

          

          if(isset($_POST['yetkilendir']))
          {
              yetkiVer($sql,$_POST['yetkilendir']);
              header('location:ayarlar.php');
          }
          if(isset($_POST['yetkisil']))
          {
              yetkiSil($sql,$_POST['yetkisil']);
              header('location:ayarlar.php');
          }
          if(isset($_POST['kullaniciSil']))
          {
             kullaniciSil($sql,$_POST['kullaniciSil']);
             header('location:ayarlar.php');
          }

          if(isset($_POST['olustur']))
          {
          $veriler= array();
           if(isset($_POST['oda_aktif'])) array_push($veriler,1); else array_push($veriler,0);
           if(isset($_POST['1'])) array_push($veriler,1); else array_push($veriler,0);
           if(isset($_POST['2'])) array_push($veriler,1); else array_push($veriler,0);
           if(isset($_POST['3'])) array_push($veriler,1); else array_push($veriler,0);
           if(isset($_POST['4'])) array_push($veriler,1); else array_push($veriler,0);
           if(isset($_POST['5'])) array_push($veriler,1); else array_push($veriler,0);
           if(isset($_POST['6'])) array_push($veriler,1); else array_push($veriler,0);
           if(isset($_POST['7'])) array_push($veriler,1); else array_push($veriler,0);
           if(isset($_POST['8'])) array_push($veriler,1); else array_push($veriler,0);
           if(isset($_POST['9'])) array_push($veriler,1); else array_push($veriler,0);
           
           yeniOda($sql,$_POST['oda_isim'],$veriler[0],$veriler[1],$veriler[2],$veriler[3],$veriler[4],$veriler[5],$veriler[6],$veriler[7],$veriler[8],$veriler[9]);
           header('location:ayarlar.php');
          }

          if(isset($_POST['sil']))
          {
              odaSil($sql,$_POST['sil']);
              header('location:ayarlar.php');
          }

          if(isset($_POST['duzenle']))
          {
           $veriler= array();
           if(isset($_POST['oda_aktif'])) array_push($veriler,1); else array_push($veriler,0);
           if(isset($_POST['1'])) array_push($veriler,1); else array_push($veriler,0);
           if(isset($_POST['2'])) array_push($veriler,1); else array_push($veriler,0);
           if(isset($_POST['3'])) array_push($veriler,1); else array_push($veriler,0);
           if(isset($_POST['4'])) array_push($veriler,1); else array_push($veriler,0);
           if(isset($_POST['5'])) array_push($veriler,1); else array_push($veriler,0);
           if(isset($_POST['6'])) array_push($veriler,1); else array_push($veriler,0);
           if(isset($_POST['7'])) array_push($veriler,1); else array_push($veriler,0);
           if(isset($_POST['8'])) array_push($veriler,1); else array_push($veriler,0);
           if(isset($_POST['9'])) array_push($veriler,1); else array_push($veriler,0);
           
            updateOda($sql,$_POST['oda_isim'],$veriler[0],$veriler[1],$veriler[2],$veriler[3],$veriler[4],$veriler[5],$veriler[6],$veriler[7],$veriler[8],$veriler[9],$_POST['duzenle']);
            header('location:ayarlar.php');
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
		  
		 <div class="container mt-5 mb-0 "style="max-width: 500px;" >
		 	<form class="form-control justify-content-center border-0"  action="" method="post">

				<button type="submit" class="btn btn-md btn-secondary mt3 mb-2" name="kullanici" value=".">Kullanıcı Ayarları</button>
        <button type="submit" class="btn btn-md btn-secondary mt3 mb-2" name="oda" value=".">Oda Ayarları</button>

        <?php echo $GLOBALS['bilgi']; ?>
        
			</form> 	
		 </div>
      </section>

      <div class="album py-5 bg-light">
        <div class="container mt-0">

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
