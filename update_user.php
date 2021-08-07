<?php
include('database.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $sql = "UPDATE `users` SET `name`='$name',`email`='$email',`phone`='$phone',`city`='$city' WHERE id='$id'";
    $query = mysqli_query($con, $sql);
    if ($query == true) {
        $data = array(
            'status' => 'success',
        );
        echo json_encode($data);
    } else {
        $data = array(
            'status' => 'failed'
        );
        echo json_encode($data);
    }
}
