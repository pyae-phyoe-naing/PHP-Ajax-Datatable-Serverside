<?php 
$con = mysqli_connect('localhost','root','','test');
if(mysqli_connect_errno()){
    echo "Database connection fail!";
}