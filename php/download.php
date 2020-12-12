<?php
    include('managment/session.php');

    if (!isset($_SESSION['path_to_file'])) {
        //Not authorized to download
        header("location:welcome.php");
        die();

    }

    $file = $_SESSION['path_to_file'];

    unset($_SESSION['path_to_file']);

    //To download the file
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);

    exit;
?>