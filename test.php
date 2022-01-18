<?php
    include('php/girisIslemleri.php');
    include('php/sqlBaglanti.php');
    include('php/veriIslemler.php');
    include('php/mail.php');

    //echo giris($sql,"123","bg");
    //echo kaydol($sql,"123","bg","test","123","0");

    //echo ogrno_to_id($sql,"19480066");,

    /*$today = new DateTime('yesterday');
    $date = new DateTime('2013-03-10');
    $interval = $today->diff($date);
    echo $interval->format("%r%a");*/

    //epostaGonder("brsgng@gmail.com","test","test mesajxd");


    $sql->close();
?>