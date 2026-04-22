<?php
// Permitir que Blogger y tu App accedan a los datos
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Recibir el parámetro del canal (ej: index.php?c=espn)
$canal = isset($_GET['c']) ? $_GET['c'] : 'espn';
$url_objetivo = "https://la14hd.com/vivo/canales.php?stream=" . $canal;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_objetivo);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Engañamos a la web diciendo que somos un Chrome real
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36');
curl_setopt($ch, CURLOPT_REFERER, 'https://la14hd.com/');

$html = curl_exec($ch);
curl_close($ch);

// Buscador de m3u8 mejorado
if (preg_match('/https?[:\/\\]+[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}[\/\\a-zA-Z0-9\-\._~%]+\.m3u8[^"\' ]*/i', $html, $matches)) {
    $link = str_replace('\\', '', $matches[0]);
    echo json_encode(["status" => "success", "url" => $link]);
} else {
    echo json_encode(["status" => "error", "message" => "No se encontró señal activa"]);
}
