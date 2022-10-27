<?php

require_once __DIR__ . '/vendor/autoload.php';

use PHPHtmlParser\Dom;
use PHPHtmlParser\Options;

// 文字コードutf-8を設定
$options = new Options();
$options->setEnforceEncoding('utf-8');

// ページ解析
$url = $_POST['url'];
$dom = new Dom();
try {
    $dom->loadFromUrl($url, $options);
    // 商品名を取得
    $element = $dom->find('#productTitle');
} catch (Exception $ex) {
    $element = "urlが認識できませんでした";
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
</head>

<body>
    <p>amazonの商品名を取得しました</p>

    <?php
    echo $element;
    ?>

    <br><br>
    <button type="button" onclick="history.back()">戻る</button>

</body>

</html>