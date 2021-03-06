<?php 
include('database.php');
$sql = "SELECT * FROM users";
$query = mysqli_query($con,$sql);
$count_all_rows = mysqli_num_rows($query);  // all row count

// if(isset($_POST['search']['value'])){
//     $search = $_POST['search']['value'];
//     $sql .= " WHERE name like '%".$search."%' ";
//     $sql .= " OR email like '%".$search."%' ";
//     $sql .= " OR phone like '%".$search."%' ";
//     $sql .= " OR city like '%".$search."%' ";
// }
// if(isset($_POST['order'])){
//     $column = $_POST['order'][0]['column'];
//     $order = $_POST['order'][0]['dir'];
//     $sql .= " ORDER BY '".$column."' ".$order;
// }else{
//     $sql .= " ORDER BY id ASC";
// }

// if($_POST['length'] != -1){
//     $start = $_POST['start'];
//     $length = $_POST['length'];
//     $sql .= " LIMIT  ".$start.", ".$length;
// }
	
$data = array();

$run_query = mysqli_query($con,$sql);
$filtered_rows = mysqli_num_rows($run_query);  // filter row count
while($row = mysqli_fetch_assoc($run_query)){
    $subarray = array();
    $subarray[] = $row['id'];
    $subarray[] = $row['name'];
    $subarray[] = $row['email'];
    $subarray[] = $row['phone'];
    $subarray[] = $row['city'];
    $subarray[] = '<a href="javascript:void();" data-id="'.$row['id'].'"  class="editBtn btn btn-sm btn-info me-3" >Edit</a>
    <a href="javascript:void();" data-id="'.$row['id'].'" class="deleteBtn btn btn-sm btn-danger">Delete</a>';
    $data[] = $subarray;
}

$output = array(
    'data' => $data,
    'draw'=>intval($_POST['draw']),
    'recordsTotal'=> $count_all_rows,
    'recordsFiltered'=> $filtered_rows,
);
echo json_encode($output);