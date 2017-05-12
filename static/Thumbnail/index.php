<?php

/**
 * 生成缩略图
 * 依赖GD库
 * @description Hope You Do Good But Not Evil
 */

define('APP_PATH', __DIR__ . '/../../');

include APP_PATH . 'config/config.php';
include APP_PATH . 'models/base/ImageUtil.php';

$APP_PATH = $_SERVER['DOCUMENT_ROOT'];

//$filePath = str_replace('//', "/", $_SERVER['DOCUMENT_ROOT'] . $_GET['p']);

$width = $_GET['w'];

$height = $_GET['h'];

$file = $_GET['p'];

$filePath = APP_PATH . $file;

if (substr($file, 0, 4) == "http") {
    echo file_get_contents($file);
} else if (is_file($filePath)) {

    $size = getimagesize($filePath);
    $src_mime = $size['mime'];
    if (!$size) {
        return false;
    }
    //ob_clean();
    header("Cache-Control: private, max-age=10800, pre-check=10800");
    header("Pragma: private");
    //header("Expires: " . date(DATE_RFC822, strtotime(" 2 day")));
    header('Content-Type: ' . $src_mime);
    //header('Content-type: image/jpeg');
    $docroot = $_SERVER['DOCUMENT_ROOT'] . $config->shoproot;

    $ImageUtil = new ImageUtil();
    $exts = $ImageUtil->fileext($filePath);
    $cachePath = hash('md4', $filePath);
    $cacheRoot = APP_PATH . 'tmp' . DIRECTORY_SEPARATOR . 'img_cache' . DIRECTORY_SEPARATOR;

    $cacheSroot = $cacheRoot . substr($cachePath, 0, 4) . DIRECTORY_SEPARATOR;
    if (!is_dir($cacheSroot)) {
        mkdir($cacheSroot, 0755, true);
    }
    $cacheFile = $cacheSroot . $cachePath . '^' . $width . '-' . $height . '.' . $exts;

    if (is_file($cacheFile)) {
        echo file_get_contents($cacheFile);
        //echo fread(fopen($cacheFile, 'rb'), filesize($cacheFile));
    } else {
        $ImageUtil->img2thumb($filePath, $cacheFile, $width, $height);
        if (is_file($cacheFile)) {
            echo file_get_contents($cacheFile);
            //echo fread(fopen($cacheFile, 'rb'), filesize($cacheFile));
        } else {
            echo file_get_contents(dirname(__FILE__) . '/image_error.jpg');
            //echo fread(fopen(dirname(__FILE__) . '/image_error.jpg', 'rb'), filesize(dirname(__FILE__) . '/image_error.jpg'));
        }
    }
} else {
    header('HTTP/1.1 404 Not Found');
    echo file_get_contents(dirname(__FILE__) . '/image_error.jpg');
    //echo fread(fopen(dirname(__FILE__) . '/image_error.jpg', 'rb'), filesize(dirname(__FILE__) . '/image_error.jpg'));
}