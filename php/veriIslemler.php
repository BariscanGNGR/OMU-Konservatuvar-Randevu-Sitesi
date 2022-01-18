<?php ob_start();

    function odalariSorgula($sql)//bugünkü tüm odaları sorgular
    {
        date_default_timezone_set("Europe/Istanbul");
		$date = new DateTime('x');
		$tarih = $date->format('Y-m-d');

            $sorgu = $sql->prepare('SELECT `id`,`oda_isim` FROM `odalar` WHERE `oda_aktif`=1;');
            $sorgu->execute();
            $sonuc = $sorgu->get_result();

            return $sonuc;
    }

    function tarihsaatSorgula($sql,$tarih,$saat) //tarih ve saate göre tüm odaları
    {
        $sorgu = $sql->prepare('SELECT `id`,`oda_isim` FROM `odalar` WHERE `oda_aktif`=1 AND `'.$saat.'`=1;');
        $sorgu->execute();
        $sonuc = $sorgu->get_result();

        $sorgu = $sql->prepare('SELECT `oda_id`,`id`,`ogrenci_id` FROM `randevular` WHERE `tarih`=? AND `saat`=? AND `pasif`=0;');
        $sorgu->bind_param("ss",$tarih,$saat);
        $sorgu->execute();
        $sonuc2 = $sorgu->get_result();

        return array($sonuc,$sonuc2);
    }

    function odaSorgula($sql,$odaid,$tarih) // seçilen odanın tüm günkü tablosu
    {
       $sorgu = $sql->prepare('SELECT * FROM `odalar` WHERE `oda_aktif`=1 AND `id`=? ;');
        $sorgu->bind_param('i',$odaid);
        $sorgu->execute();
        $sonuc = $sorgu->get_result();


        return $sonuc;
    }
    function tekliSorgulama($sql,$odaid,$tarih,$saat)
    {
        $sorgu = $sql->prepare('SELECT * FROM `randevular` WHERE `oda_id`=? AND `tarih`=? AND `saat`=? AND `pasif`=0;');
        $sorgu->bind_param("iss",$odaid,$tarih,$saat);
        $sorgu->execute();
        $sonuc = $sorgu->get_result();
        $satir = mysqli_num_rows($sonuc);

        if($satir >0)
            return $sonuc->fetch_assoc(); 
            else
            return 0;
    }

    function randevuAl($sql,$odaid,$ogr_no,$tarih,$saat) //seçilen odanın seçilen saatine rrandevu al
    {
        $sorgu = $sql->prepare('INSERT INTO `randevular`( `ogrenci_id`, `oda_id`, `saat`, `tarih`, `pasif`) VALUES (?,?,?,?,0);');
        $sorgu->bind_param('iiss',$ogr_no,$odaid,$saat,$tarih);
        if($sorgu->execute())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    function RandevuGunSorgulama($sql,$tarih,$ogr_no)
    {
        $sorgu = $sql->prepare('SELECT `id` FROM `randevular` WHERE `tarih`=? AND `ogrenci_id`=? AND `pasif`=0;');
        $sorgu->bind_param("si",$tarih,$ogr_no);
        $sorgu->execute();
        $sonuc = $sorgu->get_result();
        $satir = mysqli_num_rows($sonuc);
        if($satir >0)
            return "hidden";
    }

    function randevuIptal($sql,$randevuid) //alınan randevu iptali
    {
        //$sorgu = $sql->prepare('UPDATE `randevular` SET `pasif`=1 WHERE `id`=?;');
        $sorgu = $sql->prepare('DELETE FROM `randevular` WHERE `id`=?;');
        $sorgu->bind_param("i",$randevuid);
        if($sorgu->execute())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    function aktifRandevular($sql,$ogr_no,$sayfa) //aktif süresi gelmemiş randevuları listeler
    {
        tarihSorgula($sql,$ogr_no);
        
        $sayfa = ($sayfa-1)*20;

        $sorgu = $sql->prepare('SELECT * FROM `randevular` WHERE `ogrenci_id`=? AND `pasif`=0 ORDER BY `id` ASC Limit ?,20;');
        $sorgu->bind_param("ii",$ogr_no,$sayfa);
        $sorgu->execute();
        $sonuc = $sorgu->get_result();

        return $sonuc;
    }

    function gecmisRandevular($sql,$ogr_no,$sayfa) //süresi geçmiş randevuları listeler
    {
        tarihSorgula($sql,$ogr_no);

        $sayfa = ($sayfa-1)*20;

        $sorgu = $sql->prepare('SELECT * FROM `randevular` WHERE `ogrenci_id`=? AND `pasif`=1 ORDER BY `id` ASC Limit ?,20;');
        $sorgu->bind_param("ii",$ogr_no,$sayfa);
        $sorgu->execute();
        $sonuc = $sorgu->get_result();

        return $sonuc;
    }

    function odaGetir($sql,$odaid)
    {
        $sorgu = $sql->prepare('SELECT `id`,`oda_isim` FROM `odalar` WHERE `oda_aktif`=1 AND `id`=?;');
        $sorgu->bind_param('i',$odaid);
        $sorgu->execute();
        $sonuc = $sorgu->get_result();
        return $sonuc->fetch_assoc();
    }

    function ayarlarOdaSorgula($sql)
    {
        $sorgu = $sql->prepare('SELECT * FROM `odalar` WHERE 1;');
        $sorgu->execute();
        $sonuc = $sorgu->get_result();
        return $sonuc;
    }

    function updateOda($sql,$isim,$aktif,$s1,$s2,$s3,$s4,$s5,$s6,$s7,$s8,$s9,$id)
    {
        $sorgu = $sql->prepare('UPDATE `odalar` SET `oda_isim`=?,`oda_aktif`=?,`1`=?,`2`=?,`3`=?,`4`=?,`5`=?,`6`=?,`7`=?,`8`=?,`9`=? WHERE `id`=?;');
        $sorgu->bind_param("sssssssssssi",$isim,$aktif,$s1,$s2,$s3,$s4,$s5,$s6,$s7,$s8,$s9,$id);
        $sorgu->execute();
    }

    function yeniOda($sql,$isim,$aktif,$s1,$s2,$s3,$s4,$s5,$s6,$s7,$s8,$s9)
    {
        $sorgu = $sql->prepare('INSERT INTO `odalar` (`oda_isim`,`oda_aktif`,`1`,`2`,`3`,`4`,`5`,`6`,`7`,`8`,`9`) VALUES(?,?,?,?,?,?,?,?,?,?,?);');
        $sorgu->bind_param("sssssssssss",$isim,$aktif,$s1,$s2,$s3,$s4,$s5,$s6,$s7,$s8,$s9);
        $sorgu->execute();
    }

    function odaSil($sql,$id)
    {
        $sorgu = $sql->prepare('DELETE FROM `odalar` WHERE `odalar`.`id` = ?;');
        $sorgu->bind_param("i",$id);
        $sorgu->execute();
    }

    function tarihSorgula($sql,$ogr_no)
    {
        $sorgu = $sql->prepare('SELECT `oda_id`,`id`,`ogrenci_id`,`tarih` FROM `randevular` WHERE `ogrenci_id`=? AND `pasif`=0;');
        $sorgu->bind_param("s",$ogr_no);
        $sorgu->execute();
        $sonuc = $sorgu->get_result();

        while ($row = $sonuc->fetch_assoc()) {
            
            $today = new DateTime('yesterday');
            $date = new DateTime($row['tarih']);
            $interval = $today->diff($date);

           /* echo intval($interval->format("%r%a")) .'<br>';
            echo "The time is " . date("h:i:sa");
            echo "Today is " . date("Y/m/d") . "<br>";*/

            if(intval($interval->format("%r%a")) < 0)
            {
                $sorgu2 = $sql->prepare('UPDATE `randevular` SET `pasif`=1 WHERE `id`=?;');
                $sorgu2->bind_param("i",$row['id']);
                $sorgu2->execute();
            }
        }
    }

    function saatBirimleri($saat)
    {
        switch ($saat) {
            case '1':
                return '8:00 - 9:00';
                break;
            case '2':
                return '9:00 - 10:00';
                break;
            case '3':
                return '10:00 - 11:00';
                break;
            case '4':
                return '11:00 - 12:00';
                break;
            case '5':
                return '12:00 - 13:00';
                break;
            case '6':
                return '13:00 - 14:00';
                break;
            case '7':
                return '14:00 - 15:00';
                break;
            case '8':
                 return '15:00 - 16:00';
                 break;
             case '9':
                 return '16:00 - 17:00';
                 break;

            
            default:
                # code...
                break;
        }
    }

    function ogrno_to_id($sql,$ogr_no)
    {
        $sorgu = $sql->prepare('SELECT `id` FROM `kullanici` WHERE `ogrenci_numara`=?;');
        $sorgu->bind_param("s",$ogr_no);
        $sorgu->execute();
        $sonuc = $sorgu->get_result();
        $row = $sonuc->fetch_assoc();
        return $row['id'];
    }

    function ogrid_to_isim($sql,$ogr_id)
    {
        $sorgu = $sql->prepare('SELECT `ad_soyad`,`ogrenci_numara`,`telefon_numara` FROM `kullanici` WHERE `id`=?;');
        $sorgu->bind_param("s",$ogr_id);
        $sorgu->execute();
        $sonuc = $sorgu->get_result();
        $row = $sonuc->fetch_assoc();
        return array($row['ad_soyad'],$row['ogrenci_numara'],$row['telefon_numara']);
    }

    function ogr_ara_isim($sql,$isim)
    {
        $isim = $isim.'%';
        $sorgu = $sql->prepare('SELECT * FROM `kullanici` WHERE `ad_soyad` LIKE ?;');
        $sorgu->bind_param("s",$isim);
        $sorgu->execute();
        $sonuc = $sorgu->get_result();
        return $sonuc;
    }

    function ogr_ara_numara($sql,$tel_no)
    {
        $tel_no = $tel_no;
        $sorgu = $sql->prepare('SELECT * FROM `kullanici` WHERE `telefon_numara` LIKE ?;');
        $sorgu->bind_param("s",$tel_no);
        $sorgu->execute();
        $sonuc = $sorgu->get_result();
        return $sonuc;
    }

    function ogr_ara_ogrno($sql,$ogr_no)
    {
        $ogr_no = $ogr_no;
        $sorgu = $sql->prepare('SELECT * FROM `kullanici` WHERE `ogrenci_numara` LIKE ?;');
        $sorgu->bind_param("s",$ogr_no);
        $sorgu->execute();
        $sonuc = $sorgu->get_result();
        return $sonuc;
    }

    function yetkiVer($sql,$ogr_id)
    {
        $sorgu = $sql->prepare('UPDATE `kullanici` SET `yetkiler`="1" WHERE `id`=?;');
        $sorgu->bind_param("i",$ogr_id);
        $sorgu->execute();
    }

    function yetkiSil($sql,$ogr_id)
    {
        $sorgu = $sql->prepare('UPDATE `kullanici` SET `yetkiler`="0" WHERE `id`=?;');
        $sorgu->bind_param("i",$ogr_id);
        $sorgu->execute();
    }

    function kullaniciSil($sql,$ogr_id)
    {
        $sorgu = $sql->prepare('DELETE FROM `kullanici` WHERE `id`=?;');
        $sorgu->bind_param("i",$ogr_id);
        $sorgu->execute();
    }

    function verileriGuncelle($sql,$ad,$eposta,$tel_no,$ogr_no)
    {
        $sorgu = $sql->prepare('UPDATE `kullanici` SET `ad_soyad`=? , `e-posta`=? , `telefon_numara`=? WHERE `ogrenci_numara`=? ;');
        $sorgu->bind_param("ssss",$ad,$eposta,$tel_no,$ogr_no);
        $sorgu->execute();
    }


ob_end_flush();?>