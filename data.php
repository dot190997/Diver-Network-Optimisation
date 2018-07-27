<?php
error_reporting(0);

include 'deliveryboy.php';

$db = array();

function getMinIndex($a)
{
	$k=0;
	for($i=1; $i<count($a); $i++)
	{
		if($a[$i] < $a[$k])
		{
			$k = $i;
		}
	}
	if($a[$k]!=1000)
	{
		return $k;
	}
	else
	{
		return -1;
	}
}

function assignOrder($str1, $str2, $str3)
{
	global $db;
	$c = array();
	$temp = new deliveryboy;
	$temp2 = new deliveryboy;
	//$c = 1000;
	//$k = -1;

	$n = count($db);
	if($n == 0)
	{
		echo "No active drivers";
	}
	else
	{
		/*for($i=0; $i<$n; $i++)
		{

			$temp = clone $db[$i];
			/*$db[$i] = $temp->time;
			while(($db[$i] - $temp->time) < $t-5)
			{
				$temp->nextLocation();
			}*/
			/*$temp->dist = 0;
			$temp2 = clone $temp;

			$temp->addOrder($str1, $str2);
			$d1 = $temp->completeJob()[1];

			$temp = clone $temp2;
			$d2 = $temp->completeJob()[1];

			if(($d1-$d2)< $c)
			{
				$c = $d1-$d2;
				$k = $i;
			}
		}
		
		echo "Assign order-" . $str3 " to " . $db[$k+1]->name . "<br>";
		*/

		for($i=0; $i<$n; $i++)
		{

			$temp = clone $db[$i];

			$temp->dist = 0;
			$temp2 = clone $temp;

			$temp->addOrder($str1, $str2);
			$d1 = $temp->completeJob()[1];

			$temp = clone $temp2;
			$d2 = $temp->completeJob()[1];

			$d = ($d1-$d2);
			$c[$i] = $d;
			//echo $d . " ";
		}

		echo "Assignment suggestions for Order " . $str3 . ": <br>";
		$k = getMinIndex($c);
		//echo "Assign Order  " . $str3 . " to: <br>";
		while($k!=-1)
		{
			echo "<li>" . $db[$k]->name . "</li>";
			$c[$k] = 1000;
			$k = getMinIndex($c);
		}

		echo "<br>";

	}
}


$conn = mysqli_connect('localhost','dotato','qwerty1234','justdel2');
if (!$conn) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_select_db($conn,"justdel2");


$temp = "SELECT * FROM driver WHERE active=1";
$res = mysqli_query($conn, $temp);
if(mysqli_num_rows($res) > 0 )
{ 
	while($row = mysqli_fetch_assoc($res)){

		$numOrders = 0;
	    $id = $row['id'];
	    $name = $row['name'];
	    $loc = strtoupper($row['location']);
	    $db1 = new deliveryboy;
	    $db1->setCurrentLoc($loc);
	    $db1->name = $name;

	    //echo $id . " " . $name . " " . $loc;
	    $temp2 = "SELECT src, dest FROM orders where assigned_to='$name'";
	    $res2 = mysqli_query($conn, $temp2);
	    if(mysqli_num_rows($res2) > 0 )
	    {
		    while($row2 = mysqli_fetch_assoc($res2))
		    {
		    	$src = strtoupper($row2['src']);
		    	$dest = strtoupper($row2['dest']);
		    	$db1->addOrder($src, $dest);
		    }

		    $numOrders++;
		    //echo $db1->name . "(" . $db1->num . ") : ". "\t" . $db1->completeJob()[0] . "<br>";
		}
		else
		{
		} 

		$db1->reset();
		array_push($db, $db1);
	}
	if($numOrders==0)
	{
		echo "No assigned orders to show <br>";
	}
	else
	{
		for($i=0; $i<count($db); $i++)
		{
			echo $db[$i]->name . "(" . $db[$i]->num . ") : ". "\t" . $db[$i]->completeJob()[0] . "<br>";
		}
	}
	echo "<br>";
}
else
{
	echo "No drivers active <br><br>";
}

$temp = "SELECT * FROM orders WHERE assigned_to='-' AND status='undelivered' OR status='late' ORDER BY time_left LIMIT 1";
//$temp = "SELECT * FROM orders WHERE status='undelivered' OR status='late' ORDER BY time_left";
$res = mysqli_query($conn, $temp);
if(mysqli_num_rows($res) > 0 )
{ 
	while($row = mysqli_fetch_assoc($res)){
		$srcTemp = strtoupper($row['src']);
		$destTemp = strtoupper($row['dest']);
		$idTemp = $row['id'];
		$assign = $row['assigned_to'];
		assignOrder($srcTemp, $destTemp, $idTemp);
		//echo $srcTemp . " " . $destTemp . " " . $idTemp . " " . $assign . " " . gettype($assign) ."<br>";
	}
}
else
{
	echo "No orders to assign<br>";
}

?>