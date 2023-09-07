<?php
$mysqli = mysqli_connect('localhost','osnfbdfahosting_nguduyen','nguduyen@123','osnfbdfahosting_nguduyen');
$mysqli->set_charset('utf8');
if(mysqli_connect_errno()){
    echo 'connect that bai' .mysqli_connect_error();
    exit;
}


?>