<?php
require "session_check.php";
require "conn.php";

	define('stk_name', 'stk_name');
	define('symbol', 'symbol');
	define('share', 'share');
	define('last_price', 'last_price');
	define('idx_name', 'idx_name');
	define('currency_name', 'currency_name');
	define('rate', 'rate');
	define('currency', 'currency');
	define('cash_balance', 'cash_balance');
	define('current_price', 'current_price');

	// check user_id and porfolio_id
	$user_id = $_SESSION['user_id'];
	$user_name = $_SESSION['user_name'];
	define('pflo_name', 'pflo_name');
	define('pflo_id', 'pflo_id');
	define('pid', 'pid');
	
	if($_GET['pid'] == null || $_GET['pid'] == ""){
	//load the first portfolio created by user
		$sql = "SELECT * FROM portfolio WHERE user_id = $user_id ORDER BY pflo_id LIMIT 1";
		
		//echo $sql;
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$pflo_name = $row['pflo_name'];
				$pflo_id = $row['pflo_id'];
			}
		} else {//no portfolio found & illegal acces to this page
			echo "<script language=\"javascript\">";
			echo "location.href=\"index.html\";";
			echo"</script>";
		}
	} else {//load current portfolio
		$pflo_id = $_GET['pid'];
		$sql = "SELECT * FROM portfolio WHERE user_id = \"$user_id\" AND pflo_id = $pflo_id";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$pflo_name = $row['pflo_name'];
			}
		}
	}
?>
<script language="javascript">
	var currentItemNo = 1;
	var i = 0;
	var x;
	var showItemArray = new Array();
	function popupWindow(sURL, windownDef){
		//alert(sURL + windownDef);
		window.showModalDialog(sURL,null,windownDef);
	}
	
	/*function addTransItem(countNumber){
		if(countNumber>= 12){
			alert("You can not have more than 11 items");
			return false;
		}
		//alert("Add" + countNumber);
		document.getElementById("addTransItem_" + countNumber).className  = "";
		currentItemNo++;
		showItemArray[i] = countNumber;
		i++;
		return false;
	}

	function delTransItem(currNumber){
		//alert("Del" + currNumber);
		document.getElementById("addTransItem_" + currNumber).className  = "hidden";
		currentItemNo--;
		for(x in showItemArray){
			if(showItemArray[x] == currNumber){
				showItemArray[x] = 0;
			}
		}
		return false;
	}*/
</script>

<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="http://getbootstrap.com/favicon.ico">

    <title>Portfolio Optimization System</title>

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="./css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./css/dashboard.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="./js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Portfolio Optimization System</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">About POS</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="#"><?php echo $user_name;?></a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar"  style="margin-top:80px">
          <ul class="nav nav-sidebar">
            <li class="active"><a href="#">My Portfolio: <span class="sr-only">(current)</span></a></li>
<?php 
	$sql = "SELECT * FROM portfolio WHERE user_id = \"$user_id\"";
	$result = $conn->query($sql);
	if($result->num_rows >0){
		while($row = $result->fetch_assoc()){
			if($pflo_id == $row[pflo_id]){
				echo "<li class=\"active\"><a href=main.php?pid=$row[pflo_id]>-&nbsp;$row[pflo_name]<span class=\"sr-only\">(current)</span></a></li>";
			} else {
				echo "<li><a href=main.php?pid=$row[pflo_id]>-&nbsp;$row[pflo_name]</a></li>";
			}
		}
	}
?>
          </ul>
          <ul class="nav nav-sidebar">
            <li onClick="popupWindow('creat_portfolio.php','dialogHeight:200px;dialogWidth:300px;status:no;scroll:no;resizable:no;help:no;center:yes;')"><a>Create New Portfolio</a></li>
            <li><a href="">Edit Portfolio</a></li>
            <li><a href="">Delete Portfolio</a></li>
          </ul>
          <ul class="nav nav-sidebar">
            <li><a href="">My Information</a></li>
          </ul>
        </div>
        
        
   <div class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 main">
        <div class="row placeholder" style="margin-top:80px">
          <ul class="nav nav-tabs">
        <li class="active">
          <a href="#1" data-toggle="tab">Overview</a>
        </li>
        <li>
          <a href="#2" data-toggle="tab">Fundamentals</a>
        </li>
        <li>
          <a href="#3" data-toggle="tab">Performance</a>
        </li>
        <li>
          <a href="#4" data-toggle="tab">Transaction</a>
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="1">
          <div class="container">
            <!--Domestic Index Overview start-->
            <div class="container">
          <h4 class="sub-header"><?php echo $pflo_name;?> - Domestic</h4>
          <div class="table-responsive">
            <table  class="table table-striped" >
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Symbol</th>
				  <th>Index</th>
                  <th>Share</th>
                  <!--<th>Last Price</th>-->
                  <th>Current Price</th>
				  <th>Market Value</th>
				  <th>%</th>
                </tr>
              </thead>
              <tbody>
<?php 
	//$sql = "SELECT * FROM portfolio_view_domestic WHERE pflo_id = \"$pflo_id\"";
	$sql = "SELECT 
        stk_info.stk_id,
        stk_info.idx_id,
        pflo_stk_info.pflo_id,
        portfolio.cash_balance,
        stk_info.stk_name,
        stk_info.symbol,
        stk_info.current_price,
        pflo_stk_info.share,
        pflo_stk_info.last_price,
        idx_info.idx_name,
        currency.currency_id,
        currency.currency_name,
        currency.rate
    FROM
       stk_info,pflo_stk_info,portfolio,currency,idx_info
    WHERE
        stk_info.stk_id = pflo_stk_info.stk_id
            AND pflo_stk_info.currency_id = currency.currency_id
            AND pflo_stk_info.pflo_id = portfolio.pflo_id
            AND stk_info.idx_id = idx_info.idx_id
            AND idx_info.idx_id = 0
			AND pflo_stk_info.pflo_id = \"$pflo_id\"";
			
	$result = $conn->query($sql);

	
	$count = 1;
	$subtotal_dow = 0;
	$subtotal_percent_dow = 0;
	$portion = 0;
	$totalMktVal = 0;
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
		
		
		//$mkt_value = $row[share] * $row[last_price];
		$mkt_value = $row[share] * $row[current_price];
		$currency = $row[currency_name];
		$rate = $row[rate];
		$portion = $mkt_value / $row[cash_balance] * 100;
		$subtotal_percent_dow = $subtotal_percent_dow + $portion;
			echo "<tr>";
			echo "<td>$count</td>";
			echo "<td>$row[stk_name]</td>";
			echo "<td>$row[symbol]</td>";
			echo "<td>$row[idx_name]</td>";
			echo "<td>$row[share]</td>";
			//echo "<td>$row[last_price]</td>";
			echo "<td>$row[current_price]</td>";
			echo "<td>$mkt_value</td>";
			echo "<td>$portion%</td>";
			echo "</tr>";
			$subtotal_dow = $subtotal_dow + $mkt_value;
			$count++;
		}
		echo "<tr>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td>Subtotal</td>";
		echo "<td>$subtotal_dow</td>";
		echo "<td>$subtotal_percent_dow%</td>";
		echo "</tr>";
	} else {
		echo "<tr>";
		echo "<td colspan=\"8\">You have no stock in this section.</td>";
		echo "</tr>";
	}
	echo "<script language=\"javascript\">currentItemNo = $count + 1</script>";
?>
              </tbody>
            </table>
          </div>
        </div>
            <!--Domestic Index Overview end-->		  

            <!--Overseas Index Overview start-->
            <div class="container">		  
            <h4 class="sub-header"><?php echo $pflo_name;?> - Overseas</h4>
            <div class="table-responsive">
            <table  class="table table-striped" >
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Symbol</th>
				  <th>Index</th>
                  <th>Share</th>
                  <!--<th>Last Price</th>-->
                  <th>Current Price</th>
                  <th>Current Price(USD)</th>
				  <th>Rate</th>
				  <th>Market Value(USD)</th>
				  <th>%</th>
                </tr>
              </thead>
              <tbody>
<?php 
	//$sql = "SELECT * FROM portfolio_view_oversea WHERE pflo_id = \"$pflo_id\"";
	$sql = "SELECT 
        stk_info.stk_id,
        stk_info.idx_id,
        pflo_stk_info.pflo_id,
        portfolio.cash_balance,
        stk_info.stk_name,
        stk_info.symbol,
        stk_info.current_price,
        pflo_stk_info.share,
        pflo_stk_info.last_price,
        idx_info.idx_name,
        currency.currency_id,
        currency.currency_name,
        currency.rate
    FROM
       stk_info,pflo_stk_info,portfolio,currency,idx_info
    WHERE
        stk_info.stk_id = pflo_stk_info.stk_id
            AND pflo_stk_info.currency_id = currency.currency_id
            AND pflo_stk_info.pflo_id = portfolio.pflo_id
            AND stk_info.idx_id = idx_info.idx_id
            AND idx_info.idx_id <> 0
			AND pflo_stk_info.pflo_id = \"$pflo_id\"";
			
	$result = $conn->query($sql);
	//$count = 1;
	$subtotal_dow = 0;
	$subtotal_percent_dow = 0;
	$portion = 0;
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
		//$mkt_value = $row[share] * $row[last_price];
		$currency = $row[currency_name];
		$rate = $row[rate];
		$portion = $mkt_value / $row[cash_balance] * 100;
		$subtotal_percent_dow = $subtotal_percent_dow + $portion;
		$priceCurr = $rate * $row[current_price];
		$mkt_value = $row[share] * $row[current_price] * $rate;
			echo "<tr>";
			echo "<td>$count</td>";
			echo "<td>$row[stk_name]</td>";
			echo "<td>$row[symbol]</td>";
			echo "<td>$row[idx_name]</td>";
			echo "<td>$row[share]</td>";
			//echo "<td>$row[last_price]</td>";
			echo "<td>$row[current_price]</td>";
			echo "<td>$priceCurr</td>";
			echo "<td>$row[rate]</td>";
			echo "<td>$mkt_value</td>";
			echo "<td>$portion%</td>";
			echo "</tr>";
			$subtotal_dow = $subtotal_dow + $mkt_value;
			$count++;
		}
		echo "<tr>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td>Subtotal</td>";
		echo "<td>$subtotal_dow</td>";
		echo "<td>$subtotal_percent_dow%</td>";
		echo "</tr>";
	} else {
		echo "<tr>";
		echo "<td colspan=\"9\">You have no stock in this section.</td>";
		echo "</tr>";
	}
	echo "<script language=\"javascript\">currentItemNo = $count + 1</script>";
?>
              </tbody>
            </table>
          </div>
          </div>
            <!--Overseas Index Overview end-->
            
            <!--Cash Overview start-->
            <div class="container">		  
            <h4 class="sub-header"><?php echo $pflo_name;?> - Cash</h4>
            <div class="table-responsive">
            <table  class="table table-striped" >
              <thead>
                <tr>
                  <th>Cash(USD)</th>
                </tr>
              </thead>
              <tbody>
<?php 
	//$sql = "SELECT * FROM portfolio_view_oversea WHERE pflo_id = \"$pflo_id\"";
	$sql = "SELECT      
        portfolio.cash_balance
    FROM
       portfolio
    WHERE
			portfolio.pflo_id = \"$pflo_id\"";
			
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
		$cash = $row[cash_balance];
			echo "<tr>";
			echo "<td>$cash</td>";
			echo "</tr>";
		}
		echo "<tr>";
		echo "<td></td>";
		echo "</tr>";
	} else {
		echo "<tr>";
		echo "<td colspan=\"9\">Cash balance is not available yet.</td>";
		echo "</tr>";
	}
	echo "<script language=\"javascript\">currentItemNo = $count + 1</script>";
?>
              </tbody>
            </table>
          </div>
          </div>
            <!--Cash Overview end-->		
          </div>
        </div>
        <div class="tab-pane" id="2">
          <div class="container"></div>
        </div>
        <div class="tab-pane" id="3">
          <div class="container"></div>
        </div>
        <div class="tab-pane" id="4">
          <div class="container"></div>
        </div>
      </div>
        </div>
		  
<!--Add Transaction start-->		  
		  <hr>
		  <div class="container">
          <h4 class="sub-header">Add transaction</h4>
          <div class="table-responsive">
            <table class="table-condensed">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Symbol</th>
                  <th>Date</th>
                  <th>Share</th>
				  <th></th>
                </tr>
              </thead>
              <tbody>
			  <form class="form-group" name="newTransForm" method="post" action="SellBuy.php">
			    <tr>
                  <td><select class="form-control" name ="transType" id="transType">
						<option value="Buy">Buy</option>
						<option value="Sell">Sell</option>
					  </select></td>
                  <td><input class="form-control" type="text" list="json-datalist" id="symbol" name="symbol" placeholder = "e.g. IBM"></input></td>
                  <datalist id="json-datalist"></datalist>
                  <td><input class="form-control"  type="date" name="sDate"></input></td>
                  <td><input class="form-control" type="number" name="sShare"></input></td>
				  <td><input type="hidden" name="pid" value="<?php echo $pflo_id; ?>"></td>
				  <td><input type="hidden" name="uid" value="<?php echo $user_id; ?>"></td>
                </tr>

<tr><td colspan="4"><button type="submit" class="btn btn-success" name="sub" >Submit</button></td></tr>
				</form>
              </tbody>
            </table>
          </div>
          </div>
<!--Add Transaction end-->
<!-- Deposite/Withdraw container -->
        <div class="container">
          <h4 class="sub-header">Deposit/Withdraw</h4>
          <div class="table-responsive">
            <table class="table-condensed">
              <thead>
                <tr>
                  <th>Type</th>
                  <th>Date</th>
                  <th>Amount</th>
				  <th></th>
                </tr>
              </thead>
              <tbody>
          <form action="DepWit.php" method="post" name="CashForm">
            <tr><td>
              <select class="form-control" name="cashType">
                <option value="Deposit">Deposit</option>
                <option value="Withdraw">Withdraw</option>
              </select></td>
            <td>
              <input class="form-control" type="date" name="cashDate"></input>
            </td>
            <td>
              <input class="form-control" type="number" name="cashNumber"></input>
            </td>
             <td><input type="hidden" name="pid" value="<?php echo $pflo_id; ?>"></td>
			 <td><input type="hidden" name="uid" value="<?php echo $user_id; ?>"></td>
          </tr>
          <tr><td colspan="4">
          <button type="submit" name="sub1" class="btn btn-success">Submit</button></td></tr>
        </form>
        </tbody>
        </table>
        </div><!-- Deposite/Withdraw container ends here -->     </div>
      <hr>

        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="./js/jquery.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="./js/bootstrap.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="./js/holder.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="./js/ie10-viewport-bug-workaround.js"></script>
    <script>
      var datalist = document.getElementById('json-datalist');
      var input = document.getElementById('symbol');
      var request = new XMLHttpRequest();
      
      request.onreadystatechange = function(response) {
        if (request.readyState == 4) {
          if (request.status == 200) {
            var options = JSON.parse(request.responseText);
            var n = 0;
            options.forEach(function(item) {
              var option = document.createElement('option');
              option.value = item;
              option.id = n;
              datalist.appendChild(option);
              n++;
            });
            input.placeholder = "e.g. IBM";
          } else {
            input.placeholder = "Couldn't load options";
          }
        }
      };
      input.placeholder = "Loading options...";
      request.open('GET', 'stockSymbol.json', true);
      request.send();
    </script>
    <script>
    function saveStockData() {
      var name = $('#symbol').val();
      //var type = $('select[name=transType]').val();
      var type = $('#transType').val();
      var date = $('#sDate').val();
      var share = $('#sShare').val();
      var pid = $('#pid').attr('name');
      console.log(name);
      console.log(type);
      console.log(date);
      console.log(pid);
      if(name != null & type != null & date != null & share!= null & pid != null) {
        $.ajax({
          type: "POST",
          url: "SellBuy.php?p=add",
          data: "transType="+type+"&symbol="+name+"&sDate="+date+"&sShare="+share+"&pid"+pid,
          success: function(data) {
            //viewStockData();
          }
        });
      } else {
        alert("All the fields are required!");
      }
    }
    function viewStockData() {
      $.ajax({
        type: "GET",
        url:"SellBuy.php",
        success: function(data) {
          $('tbody').html(data);
        }
      });
    }

    </script>

</body></html>
<?php
require "conn_close.php";
?>