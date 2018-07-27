<?php
error_reporting(0);
$q = intval($_GET['q']);

$con = mysqli_connect('localhost','dotato','qwerty1234','justdel2');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_select_db($con,"justdel2");
if($q==2)
{
    $sql="SELECT * FROM orders";
}
elseif($q==1)
{
    $sql="SELECT * FROM orders WHERE status='undelivered'";
}

$result = mysqli_query($con,$sql);

$order = mysqli_fetch_all($result, MYSQLI_ASSOC);

echo json_encode($order);

mysqli_close($con);

?>