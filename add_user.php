<?php
include('database.php');

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$city = $_POST['city'];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
   $sql = "INSERT INTO `users` (`name`,`email`,`phone`,`city`) VALUES ('$name','$email','$phone','$city')";
   $query = mysqli_query($con,$sql);

   if($query==true){
       $data = array(
           'status' => 'success',
       );
       echo json_encode($data);
   }else{
    $data = array(
        'status' => 'failed',
    );
    echo json_encode($data);
   }
}