<?php
  set_time_limit(0);
  ignore_user_abort(1);
  if(!$_GET['id']){
    header('location: index.php');
    exit;
  }
  $ch = curl_init('http://api.joox.com/web-fcgi-bin/web_get_songinfo?songid='.base64_decode(trim($_GET['id'])).'&lang=id&country=id&from_type=null&channel_id=null&_='.time());
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Forwarded-For: 36.73.34.109"));
  curl_setopt($ch, CURLOPT_COOKIE, 'wmid=142420656; user_type=1; country=id; session_key=2a5d97d05dc8fe238150184eaf3519ad;');
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36');
  $json = curl_exec($ch);

  $json = str_replace('MusicInfoCallback(', '', $json);
  $json = str_replace(')', '', $json);
  $json = json_decode($json);
  $fi = $json->mp3Url;
   /*	echo '<pre>';
   	print_r($json);
   	echo '</pre>'; */
  if(!$json->album_url){
    header('location: index.php');
    exit;
  }
  $ch = curl_init('http://api.joox.com/web-fcgi-bin/web_lyric?musicid='.base64_decode(trim($_GET['id'])).'&lang=id&country=id&_='.time());
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Forwarded-For: 36.73.34.109"));
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36');
  $ly = curl_exec($ch);
  curl_close($ch);
  $ly = str_replace('MusicJsonCallback(', '', $ly);
  $ly = str_replace(')', '', $ly);
  $ly = json_decode($ly);

  $ly = str_replace('[999:00.00]***Lirik didapat dari pihak ketiga***', '***Recoded By J***', base64_decode($ly->lyric));
  $ly = str_replace('[offset:0]', '', $ly);
  $name = $json->msong;
?>
<?php
include ("__parts/header.php");
?>
<?php
include ("__parts/navbar.php");
?>
<div class="container">
   <div class="panel panel-info" >
      <div class="panel-heading">
         <div class="panel-title"><?=$name?> - Download Lagu Gratis</div>
      </div>
      <div class="panel-body">
         <div class="text-center">
            <img class="img-circle" height="128" width="128" src="<?=$json->imgSrc?>">
            <h2><?=$name?></h2>
            <audio controls>
               <source src="<?=$json->mp3Url?>" type="audio/mpeg">
            </audio>
         </div>
         <hr>
         Waktu Rilis: <b><?=$json->public_time?></b><br>
         Penyanyi: <a href="singer.php?id=<?=$json->msingerid?>"><b><?=$json->msinger?></b></a><br>
         Album: <a href="album.php?id=<?=$json->malbumid?>"><b><?=$json->malbum?></b></a><br>
         Durasi: <b><?=gmdate('H:i:s', $json->minterval)?></b><br>
         MP3 320kbps (Original Quality): <a href="mp3.php?id=<?=$_GET['id']?>"><b>Mirror Download</b></a><br>
         M4A 100kbps+ (TV Quality): <a href="m4a.php?id=<?=$_GET['id']?>"><b>Mirror Download</b></a><br>
         <hr>
         <pre><?=$ly?></pre>
         <center>
            <a href="lyric.php?id=<?=$_GET['id']?>&track=<?= base64_encode($name); ?>" style="text-decoration:none;"><b>Download Lyric</b></a><br><br>
         </center>
      </div>
   </div>
</div>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/jquery-ui.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>