<?php
$fileUrl = 'https://github.com/P3TERX/GeoLite.mmdb/raw/download/GeoLite2-City.mmdb';

// Сохраняем БД
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $_SERVER['DOCUMENT_ROOT'] = '/home/bitrix/www';
}
$saveTo = $_SERVER['DOCUMENT_ROOT'] . '/local/components/custom/region_descriptor/GeoLite2-City.mmdb';

function downloadFile($url, $path)
{
    $ch = curl_init($url);
    if ($ch === false) {
        echo 'Error: Unable to initialize cURL session';
        return;
    }

    $fp = fopen($path, 'wb');
    if ($fp === false) {
        echo 'Error: Unable to open file for writing';
        curl_close($ch);
        return;
    }

    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);

    curl_exec($ch);
//    if (curl_errno($ch)) {
//        echo 'Error: ' . curl_error($ch);
//    }

    curl_close($ch);
    fclose($fp);
}

downloadFile($fileUrl, $saveTo);

//echo "Файл обновлен!";