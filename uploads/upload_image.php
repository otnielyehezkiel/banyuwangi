<?php
    $base=$_REQUEST['image'];
    $binary=base64_decode($base);
    header('Content-Type: bitmap; charset=utf-8');
    $fi = new FilesystemIterator(__DIR__, FilesystemIterator::SKIP_DOTS);
    $fcount=iterator_count($fi);
    $filename="image".strval($fcount).".jpg";
    $file = fopen( $filename, 'wb');
    fwrite($file, $binary);
    fclose($file);
    echo $filename;
?>