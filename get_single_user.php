<?php 
include('database.php');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = $_POST['id'];
   $sql = "SELECT * FROM users WHERE id='$id'";
   $query = mysqli_query($con,$sql);
   $row = mysqli_fetch_assoc($query);
   echo json_encode($row);
}