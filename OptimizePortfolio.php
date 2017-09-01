<?php
	set_include_path('/afs/cad/u/d/l/dl369/public_html/ssh2/');
	include('/afs/cad/u/d/l/dl369/public_html/ssh2/Net/SSH2.php');
	require "session_check.php";
	require "conn.php";
	$user_id = $_SESSION['user_id']; 
	$user_name = $_SESSION['user_name'];
	$pflo_id = $_GET['pid'];
	$w = array();
	$beta = array();
	$er = array();
	$i = 1;
	$sql = "SELECT * FROM pflo_stk_info WHERE pflo_id = $pflo_id";
	$result = $conn->query($sql);
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){
			$stk_id = $row[stk_id];
#				echo 'Stock id='.$stk_id;
			
			$sql1 = "SELECT * FROM stk_cal WHERE stk_id = $stk_id";
			$result1 = $conn->query($sql1);
			if($result1->num_rows > 0) {
				$row1 = $result1->fetch_assoc();
				$beta[$i] = $row1[Beta];
				$er[$i] = $row1[ER];
#				echo 'er is' . $er[$i];
			}	
		$i++;
		}
	}
	$sql = "SELECT * FROM pflo_stk_info WHERE pflo_id = $pflo_id";
	$result = $conn->query($sql);
	$num_row = $result->num_rows;
#	echo 'The number of row is' . $num_row;
    
	$max = '';
	$weight = '  weight: ';
	$beta_string = ' beta: ';
	$bounds = array();
	$Bounds_sum = '';
	$low = 10;
	$high = 200;
	$int = ''; 
	for ($x=1; $x<= $num_row; $x++) {
		if( $x < $num_row){
			$max = $max . ' ' . $er[$x] . ' w' . $x . ' +';
#			echo $x;
			$weight = $weight . ' w' . $x . ' +';
			$beta_string = $beta_string . ' ' . $beta[$x] . ' w' . $x . ' +';
		} else{
			$max = $max . ' ' . $er[$x] . ' w' . $x;
			$weight = $weight . ' w' . $x . ' = 1000';
			$beta_string = $beta_string . $beta[$x] . ' w' . $x .  " \<= 1350";
		}
		$bounds[$x] = '  ' . $low . " \< " . 'w' . $x . " \<= " . $high;
		$int = $int . '  w' . $x;
	}

	$head = 'Maximize';
	$subject = 'Subject To';
	$bounds_string = 'Bounds';
	$int_string = 'Integers';
	$end = 'End';
	$out = shell_exec('echo \'\' >| optimizer.sol');
    $out = shell_exec("echo $head >| optimizer.lp");
	$out = shell_exec("echo $max >> optimizer.lp");
	$out = shell_exec("echo $subject >> optimizer.lp");
	$out = shell_exec("echo $weight >> optimizer.lp");
	$out = shell_exec("echo $beta_string >> optimizer.lp");
	$out = shell_exec("echo $bounds_string >> optimizer.lp");
	for($x=1; $x<= $num_row; $x++){
		$out = shell_exec("echo $bounds[$x] >> optimizer.lp");
	}
	$out = shell_exec("echo $int_string >> optimizer.lp");
	$out = shell_exec("echo $int >> optimizer.lp");
	$out = shell_exec("echo $end >> optimizer.lp");
	
#	$out = shell_exec('more optimizer.lp');

#	$connection = ssh2_connect('afs6.njit.edu', 22);
	$ssh = new Net_SSH2('afs6.njit.edu', 22);
	$ssh->login('dl369', 'Love200608201');
	$output = $ssh->exec('pwd');
	$output = $ssh->exec('gurobi_cl ResultFile=optimizer.sol public_html/1206/optimizer.lp');
	echo $output;
	$output = $ssh->exec('cp optimizer.sol public_html/1206/optimizer.sol');
	$handle = fopen("optimizer.sol", "r");
	$i = 1;
	if ($handle) {
		while (($line = fgets($handle)) !== false) {
			// process the line read.
			if(preg_match("/w\d/",$line,$matches)){
				preg_match("/w\d (\d+)/",$line,$matches);
				$w[$i] = $matches[1] / 1000;
				$i++;
		}
		
		}

    fclose($handle);
	} else {
		echo 'error opening file optimizer.sol';
	} 

	$i = 1;
	$sql = "SELECT * FROM pflo_stk_info WHERE pflo_id = $pflo_id";
	$result = $conn->query($sql);
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){
			$stk_id = $row[stk_id];
#				echo 'Stock id='.$stk_id;
			
			$sql1 = "UPDATE pflo_stk_info SET pflo_stk_info.weight = $w[$i] WHERE pflo_stk_info.pflo_id = $pflo_id AND pflo_stk_info.stk_id = $stk_id";
			$result1 = $conn->query($sql1);
			echo $conn->error;
			$i++;
		}
	}
	$output = $ssh->disconnect();

#	echo "<script>location.href=\"main.php\";</script>";
   ?>

<?php 
require "conn_close.php";
?>
