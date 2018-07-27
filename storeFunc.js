document.getElementById("deliver").addEventListener("submit", updateOrder);
document.getElementById("store").addEventListener("submit", storeOrder);
document.getElementById("driver").addEventListener("submit", activate);
document.getElementById("assign").addEventListener("submit", assign);
document.getElementById("suggest").addEventListener("click", suggest);


function suggest(){

	//FUN OBERSVATION:
	//If GET request function is written after POST request function, xhr.open() doesn't work.
	//So, write all GET request function before writiing any POST reuqest function

	document.getElementById("del").innerHTML = "";
	document.getElementById("added").innerHTML = "";
	document.getElementById("ass").innerHTML = "";

	//console.log("inside function");
	var xhr = new XMLHttpRequest();
	xhr.open("GET", "data.php", true);
	//xhr.setRequestHeader('content-type', 'Application/x-www-form-urlencoded');
	xhr.onload = function(){
		console.log("OKAY");
	
	  if(this.status==200)
	  {
	    var res = (this.responseText);
	    document.getElementById("data2").innerHTML = res;
	  }
	}
	xhr.send();
}

function activate(e)
{

	if(e!=null)
	{
  		e.preventDefault();
  	}
  var proto = document.querySelectorAll('input[name="driver"]:checked');
  var active = [];
  var locs = [];
  for(var i=0; i<proto.length; i++)
  {
  	var loc = document.querySelector("input[name='" + proto[i].id + "']").value;
    active.push(proto[i].id);
    locs.push(loc);
  }

  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'driver.php', true);
  var sendActive = JSON.stringify(active);
  var sendLoc = JSON.stringify(locs);
  var params = 'data=' + sendActive + '&locs=' + sendLoc;
  console.log(params);

  xhr.setRequestHeader('content-type', 'Application/x-www-form-urlencoded');
  xhr.onload = function(){
    if(this.status==200)
    {
      var res = this.responseText;
      document.getElementById("added").innerHTML = res;
    }
  };
  xhr.send(params);
  document.getElementById("store").reset();
}

function getOrder(str)
{
  document.getElementById("del").innerHTML = "";
  document.getElementById("added").innerHTML = "";
  document.getElementById("ass").innerHTML = "";

  if (str == "") {
      document.getElementById("txtHint").innerHTML = "";
      return; }

  var xhr = new XMLHttpRequest();
  xhr.open("GET", "getOrder.php?q="+str, true);
  xhr.onload = function(){
    if(this.status==200)
    {
      var res = JSON.parse(this.responseText);
      var output='<table id="new"><tr><th>Id</th><th>Source</th><th>Destination</th><th>Self</th><th>Order Time</th><th>Time Left</th><th>Assigned To</th><th>Status</th></tr>';
      for(var i in res)
      {
        if(res[i].status=='delivered' || res[i].status=='late delivered' || res[i].status=='cancelled')
        {
            res[i].time_left='-';
        }
        else
        {
            var n =  parseInt(Date.now()/1000);
            res[i].time_left -= parseInt((n-res[i].order_time)/60);
            if(res[i].time_left<0)
            {
                res[i].status="late";
            }
        }
        output += "<tr><td>" + res[i].id + "</td><td>" + res[i].src  + "</td><td>" + res[i].dest  + "</td><td>" + res[i].self  + "</td><td>" + res[i].org_time  + "</td><td>" + res[i].time_left  + "</td><td>" + res[i].assigned_to  + "</td><td>" + res[i].status + "</td></tr>";
      }
      output += "</table>";
      document.getElementById("data").innerHTML = output;
    }
  }
  xhr.send();
 /* setTimeout(function(){
    console.log("hello");
    getOrder(str); } , 10000); */
}


function storeOrder(e)
{
  document.getElementById("del").innerHTML = "";
  document.getElementById("ass").innerHTML = "";
  e.preventDefault();
  var src = document.getElementById("src").value;
  var dest = document.getElementById("dest").value;
  var  selfOrder = document.querySelector("input[id='self']:checked");
  if(selfOrder==null)
  {
    selfOrder="false";
  }
  else
  {
    selfOrder = "true";
  }  

  var xhr = new XMLHttpRequest();
  xhr.open("POST", "storeorder.php", true);
  var params = "src="+src+"&dest="+dest+"&self="+selfOrder;
  xhr.setRequestHeader('content-type', 'Application/x-www-form-urlencoded');
  xhr.onload = function(){
    if(this.status==200)
    {
      var res = this.responseText;
      document.getElementById("added").innerHTML = res;
    }
  };
  xhr.send(params);
  document.getElementById("store").reset();
  console.log("stored");
  //var str2 = document.querySelector('select[name="get"]').value;
 // setTimeout(function(){
   // console.log("hello");
   // getOrder(2); } , 3000);

}

function updateOrder(e)
{
  document.getElementById("added").innerHTML = "";
  document.getElementById("ass").innerHTML = "";
  e.preventDefault();
  var id = document.getElementById("id").value;
  var str = document.querySelector('input[name="status"]:checked').value;
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "updateOrder.php", true);
  var params = "p="+id+"&q="+str;
  xhr.setRequestHeader('content-type', 'Application/x-www-form-urlencoded');
  xhr.onload = function(){
    if(this.status==200)
    {
      var res = this.responseText;
      if(res=="Order doesn't exist")
      {
        alert(res);
      }
      document.getElementById("del").innerHTML = res;
    }
  }
  xhr.send(params);

  var str2 = document.querySelector('select[name="get"]').value;
  setTimeout(function(){
    console.log("hello");
    getOrder(str2); } , 3000);
  document.getElementById("deliver").reset();
  document.getElementById("assign").reset();
  //Why is this code not working?
}

function assign(e)
{
	
  e.preventDefault();
  var str = document.querySelector("select[name='driver']").value;
  console.log(str);
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "updateOrder.php", true);
  var id = document.getElementById("id").value;
  var params = "p="+id+"&dri="+str;
  xhr.setRequestHeader('content-type', 'Application/x-www-form-urlencoded');
  xhr.onload = function(){
    if(this.status==200)
    {
      var res = this.responseText;
      if(res=="Driver inactive" || res=="Order doesn't exist")
      {
        alert(res);
      }
      document.getElementById("ass").innerHTML = res;
    }
  }
  xhr.send(params);

  var str2 = document.querySelector('select[name="get"]').value;
  setTimeout(function(){
    console.log("hello");
    getOrder(str2); } , 3000);
  document.getElementById("deliver").reset();
  document.getElementById("assign").reset();

  //Update Orders Table as soon as submit is clicked
}

//activate();
getOrder(2);