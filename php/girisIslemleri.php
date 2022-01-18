<?php ob_start();
    function giris($sql,$ogr_no,$sifre)
    {
            $sorgu = $sql->prepare('SELECT * FROM `kullanici` WHERE `ogrenci_numara`=? AND `sifre`=?;');
            $sorgu->bind_param("ss",$ogr_no,$sifre);
            $sorgu->execute();
            $sonuc = $sorgu->get_result();
            $satir = mysqli_num_rows($sonuc);

            if($satir == 1)
            {
                if(!isset($_SESSION)) 
                { 
                 session_start(); 
                } 
                while ($row = $sonuc->fetch_assoc()) {
                    $_SESSION['ogr_no'] = $row['ogrenci_numara'];
                    $_SESSION['sifre'] = $row['sifre'];
                }
            }
            
            return $satir;

    }

    function yetkiSorgula($sql,$ogr_no)
    {
        $sorgu = $sql->prepare('SELECT `yetkiler` FROM `kullanici` WHERE `ogrenci_numara`=?;');
        $sorgu->bind_param("s",$ogr_no);
        $sorgu->execute();
        $sonuc = $sorgu->get_result();
        $row = $sonuc->fetch_assoc();
        return $row['yetkiler'];
    }

    function kaydol($sql,$ogr_no,$sifre,$ad,$telefon_no,$yetki,$eposta)
    {
        $sorgu= $sql->prepare('INSERT INTO `kullanici`( `ogrenci_numara`, `sifre`, `ad_soyad`,  `telefon_numara`, `yetkiler`, `e-posta`) VALUES (?,?,?,?,?,?)');
        $sorgu->bind_param("ssssss",$ogr_no,$sifre,$ad,$telefon_no,$yetki,$eposta);
        if($sorgu->execute())
        {
            return 1;
        }
        else
        {return 0;}

        
    }

    function kayitHatasi($sql,$ogr_no,$telefon_no)
    {
        $mesaj = "";

        $sorgu = $sql->prepare('SELECT `ogrenci_numara` FROM `kullanici` WHERE `ogrenci_numara`=?;');
        $sorgu->bind_param("s",$ogr_no);
        $sorgu->execute();
        $sonuc = $sorgu->get_result();
        $satir = mysqli_num_rows($sonuc);
        if($satir >= 1)
        {
            $mesaj .= "Öğrenci numaranıza ait hesap mevcuttur <br>";
        }   
        $satir = 0;

        $sorgu = $sql->prepare('SELECT `telefon_numara` FROM `kullanici` WHERE `telefon_numara`=?;');
        $sorgu->bind_param("s",$telefon_no);
        $sorgu->execute();
        $sonuc = $sorgu->get_result();
        $satir = mysqli_num_rows($sonuc);
        if($satir >= 1)
        {
            $mesaj .= "Telefon numaranıza ait hesap mevcuttur <br>";
        }   

        return $mesaj;
    }

    function epostaKontrol($sql,$eposta,$ogr_no)
    {
        $sorgu = $sql->prepare('SELECT `id` FROM `kullanici` WHERE `e-posta`=? AND `ogrenci_numara`=? ;');
        $sorgu->bind_param("ss",$eposta,$ogr_no);
        $sorgu->execute();
        $sonuc = $sorgu->get_result();
        $satir = mysqli_num_rows($sonuc);
        if($satir >= 1)
            return 1;
        else
            return 0;
    }

    function sifremiSifirla($sql,$ogr_id,$yeniSifre)
    {
        $sorgu = $sql->prepare('UPDATE `kullanici` SET `sifre`=?,`sifirlama_kodu`="" WHERE `id`=?;');
        $sorgu->bind_param("si",$yeniSifre,$ogr_id);
        $sorgu->execute();
    }

    function yeniSifreTalep($sql,$eposta,$kod)
    {
        $sorgu = $sql->prepare('UPDATE `kullanici` SET `sifirlama_kodu`=? WHERE `e-posta`=?;');
        $sorgu->bind_param("ss",$kod,$eposta);
        $sorgu->execute();
    }

    function kodSifirla($sql,$ogr_id)
    {
        $sorgu = $sql->prepare('UPDATE `kullanici` SET `sifirlama_kodu`="" WHERE `id`=?;');
        $sorgu->bind_param("i",$ogr_id);
        $sorgu->execute();
    }

    function sifremiUnuttumKontrol($sql,$ogr_id,$dk)
    {
        $sorgu = $sql->prepare('SELECT `id` FROM `kullanici` WHERE `sifirlama_kodu`=? AND `id`=? ;');
        $sorgu->bind_param("si",$dk,$ogr_id);
        $sorgu->execute();
        $sonuc = $sorgu->get_result();
        $satir = mysqli_num_rows($sonuc);
        if($satir >= 1)
            return 1;
        else
            return 0;
    }

    function sifreDegis($sql,$ogr_id,$sifre)
    {
        $sorgu = $sql->prepare('UPDATE `kullanici` SET `sifre`=? WHERE `id`=?;');
        $sorgu->bind_param("si",$sifre,$ogr_id);
        $sorgu->execute();
    }

ob_end_flush();?>