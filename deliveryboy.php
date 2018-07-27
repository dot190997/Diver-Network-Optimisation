<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>


<?php
error_reporting(0);

include 'sourcelist.php';
include 'destinationlist.php';
include 'calculate.php'; 


class deliveryBoy
{
	var $name = '';
	var $sourceLoc = array();
	var $destLoc = array();
	var $source = array();
	var $dest = array();
	var $currentLoc="";
	var $dist = 0;
	var $stime = array();
	var $dtime = array();
	var $time=0;
	var $num=0;

	function reset()
	{
		$sourceLoc = array();
		$destLoc = array();
		$source = array();
		$dest = array();
		$stime = array();
		$dtime = array();
		$currentLoc="";
		$dist = 0;
		$time = 0;
		$num=0;
		$name = '';
	}
	

	function setCurrentLoc($str)
	{
		global $sourcelist, $destlist;
		if($sourcelist[$str])
		{
			$this->currentLoc=$sourcelist[$str];
		}
		else
		{
			$this->currentLoc=$destlist[$str];	
		}

	}

	function addOrder($str1, $str2)
	{
		global $sourcelist, $destlist;
		array_push($this->sourceLoc, $sourcelist[$str1]);
		array_push($this->destLoc, $destlist[$str2]);
		$this->num++;
		$this->updateDist($this->currentLoc, count($this->sourceLoc)-1);
	}

	function getSource()
	{
		global $sourcelist, $destlist;
		$c=1000;
		$k=0;
		for($i = 0; $i<$this->num; $i++)
		{
			if($this->source[$i] < $c)
			{
				$c = $this->source[$i];
				$k = $i;
			}
		}
		return $k;
	}

	function getDest()
	{
		global $sourcelist, $destlist;
		$c=1000;
		$k=0;
		for($i = 0; $i<$this->num; $i++)
		{
			if($this->dest[$i] < $c)
			{
				$c = $this->dest[$i];
				$k = $i;
			}
		}
		return $k;
	}

	function updateDist($str, $x)
	{
		global $sourcelist, $destlist;
		if($x==100)
		{
			for($i = 0; $i<$this->num ; $i++)
			{
				if($this->source[$i]!=1000)
				{
					$s = calculate($str, $this->sourceLoc[$i]);
					$this->source[$i] = $s[0];
					$this->stime[$i] = $s[1];
				}
				$d = calculate($str, $this->destLoc[$i]);
				$this->dest[$i] = $d[0];
				$this->dtime[$i] = $d[1];
			}
		}
		else
		{
			$s = calculate($str, $this->sourceLoc[$x]);
			$d = calculate($str, $this->destLoc[$x]);
			array_push($this->source, $s[0]);
			array_push($this->dest, $d[0]);
			array_push($this->stime, $s[1]);
			array_push($this->dtime, $d[1]);
		}
	}

	function update($str, $i)
	{
		global $sourcelist, $destlist;
		if($str == "source")
		{
			$this->currentLoc = $this->sourceLoc[$i];
			$this->updateDist($this->currentLoc, 100);
			$this->source[$i] = 1000;
		}

		elseif($str == "destination")
		{
			$this->currentLoc = $this->destLoc[$i];
	        $this->updateDist($this->currentLoc, 100);
	        array_splice($this->dest, $i, 1);
	        array_splice($this->sourceLoc, $i, 1);
	        array_splice($this->destLoc, $i, 1);
	        array_splice($this->source, $i, 1);
	        $this->num--;
		}
	}

	function nextLocation()
	{
		global $sourcelist, $destlist;
		if(count($this->sourceLoc) == 0)
		{
			$this->time=$this->time + 5;
			return "No more orders assigned";
		}

		$dest2 = $this->dest;
	    $s = $this->getSource();
	    $d = $this->getDest();

	    while($this->source[$d]!=1000)
	    {
	    	if($this->source[$s]<$this->dest[$d])
	    	{
	    		#Assuming that delivery boy will need 5 extra minutes at pick up point and destination location
	    		$this->dist = $this->dist + $this->source[$s];
	    		$this->time = $this->time + $this->stime[$s] + 5;
	    		$this->dest = $dest2;
	    		$str = array_search($this->sourceLoc[$s], $sourcelist);
	    		$this->update("source", $s);
	    		return $str;

	    	}
	    	else
	    	{
	    		$this->dest[$d]=1000;
	    		$d=$this->getDest();
	    	}
	    }

	    if($this->source[$s]<$this->dest[$d])
	    	{
	    		$this->dist = $this->dist + $this->source[$s];
	    		$this->time = $this->time + $this->stime[$s] + 5;
	    		$this->dest = $dest2;
	    		$str = array_search($this->sourceLoc[$s], $sourcelist);
	    		$this->update("source", $s);
	    		return $str;
	    	}
	    	else
	    	{
	    		$this->dist = $this->dist + $this->dest[$d];
	    		$this->time = $this->time + $this->dtime[$d] + 5;
	    		$this->dest = $dest2;
	    		$str = array_search($this->destLoc[$d], $destlist);
	    		$this->update("destination", $d);
	    		return $str;

	    	}
	}

	function completeJob()
	{
		$str = '';
		while($this->num>0)
		{
			$str = $str . ' => ' . $this->nextLocation();
		}
		$a = array($str, $this->dist);
		$this->reset();
		return $a;
	}

	function displayOrders()
	{
		global $sourcelist, $destlist;
		for($i=0; $i<$this->num; $i++)
		{
			echo $i+1; 
			echo ".";
			echo array_search($this->sourceLoc[$i], $sourcelist);
			echo " - ";
			echo array_search($this->destLoc[$i], $destlist);
			echo "<br>";
		}
	}
}

?>

</body>
</html>