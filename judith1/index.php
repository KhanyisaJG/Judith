<?php
 session_start();

 // a function to reload the page
if (isset($_POST['reload_page'])) {
  // when loading, set everything to zero
  $_SESSION['total'] = 0;

  @apache_setenv('no-gzip', 1);
  @ini_set('zlib.output_compression', 0);
  @ini_set('output_buffering', 'Off');
  @ini_set('implicit_flush', 1);
// Flush buffers
ob_implicit_flush(1);
for ($i = 0, $level = ob_get_level(); $i < $level; $i++) ob_end_flush(); 
}
// variables initialization
$msg ="";
$msgClass = "";
$product = array();
$change = 0;
$change= sprintf("%.2f", $change);
// check if the total is entered
if (isset($_POST['total'])) {

    $cents =$_POST['cent']; //number of 50c coins entered
    $r1s =$_POST['r1'];     //number of R1 coins entered
    $r2s =$_POST['r2'];     //number of R2 coins entered
    $r5s =$_POST['r5'];     //number of R5 coins entered

   // assign the value returned from the calculated total to a session variable total
    $_SESSION['total'] = totalAmout($cents, $r1s, $r2s, $r5s);
   
}

// check if the total is entered, then set all values to zero;
if (isset($_POST['coin-refresh'])) {

    $cents  = 0;
    $r1s  = 0;
    $r2s  =0;
    $r5s  = 0;
    $_SESSION['total'] = 0;
    
}

// check if the the purchase or buy button is clicked
if (isset($_POST['purchase'])) {

    $soda = $_POST['soda'];
    $sodaPrice = 10;

    if (!empty($soda)) {

        $sodalength = count($soda);
        

        if ($_SESSION['total'] >= ($sodaPrice * $sodalength)) {

             $change = $_SESSION['total'] - ($sodaPrice * $sodalength);
              $change= sprintf("%.2f", $change);
              


             if ($sodalength == 1) {
                $msg ="You bought ".$sodalength." drink";
                $msgclass ="success";
            }else{
                $msg ="You bought ".$sodalength." drinks";
                $msgclass ="success";
            }
        
            for($i=0; $i < $sodalength; $i++){

             array_push($product,$soda[$i]);
            }
            $_SESSION['total'] = 0;

        }else{
            $change = $_SESSION['total'];
            $change= sprintf("%.2f", $change);
            $msg ="You do not have enough funds to buy a drink, Insert more coins";
            $msgclass ="danger";
            $_SESSION['total'] = 0;
        }
       
        

    }

    else{
        $change = $_SESSION['total'];
        $change= sprintf("%.2f", $change);
        $msg =" No drink selected, R".$change." returned. Please insert a coin and select a drink";
        $msgclass ="danger";
        $_SESSION['total'] =0;
    }

}

// cancelling the transaction
if (isset($_POST['cancel'])) {

  if ($_SESSION['total'] > 0 ) {
      $msg ="Your transaction was successfully cancelled, R".$_SESSION['total']." returned";
      $msgclass ="success";
      $_SESSION['total'] = 0;
  }else{
      $msg ="There is no transaction to cancel, Make transactions";
      $msgclass ="danger";
  }
  
}



// functions

// calculate the total amount entered by a user
function totalAmout($cents, $r1s, $r2s, $r5s){
    // get the number of coins entered by the user 
    // define the value of each coin
    $cent = 0.5;
    $r1 = 1;
    $r2 = 2;
    $r5 = 5;


    // check if the coin is not selected, then set it to zero
    if(empty($cents)){
        $cents = 0;
    }
    if(empty($r1s)){
        $r1s = 0;
    }
    if(empty($r2s)){
        $r2s = 0;
    }
    if(empty($r5s)){
        $r5s = 0;
    }

    // calculate the total amout in each of the 4 coins

    // multiply the total number of coins entered by the value of each coin
    $total_cents = $cents * $cent;
    $total_r1s = $r1s * $r1;
    $total_r2s = $r2s * $r2;
    $total_r5s = $r5s * $r5;
    
     // calculate the overall amount entered
    $total = $total_cents + $total_r1s + $total_r2s + $total_r5s;
    // convert int to float 
    $total = sprintf("%.2f",$total);

    return $total;
}


?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<title>Vending Machine</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>


	<body>

    <!-- heading section -->
		<header id="main_header" >
      <div class="flex">
        <h1>KHANYISA VENDING MACHINE</h1>
        <form method="POST" action="index.php">
          <button id="reload-page" onclick="loadPage()" name="reload_page">Refresh</button>
        </form>
      </div>
			
		</header>

    
    <!-- !heading section -->    
    <!-- php code to load refresh the page -->
          <?php
            // We need to send enough junk messages to make it works for all browsers
            echo str_repeat(" ", 1024), "\n";
            $load ="please wait while we reload your page";
            ob_start();
            // Long process starts here
            // For this example, just sleep for 5 seconds
            sleep(1); 
           
            $load = "";
            // Flush output like this
            ob_flush();
            flush();

            ?>
    <!-- main section -->
        <main id="main">
        	<!-- output section-->
        	   <section id="output">

        	   	   <div class=" title">
        	   	   	  <h2>Output</h2>
        	   	   </div>
                   <div class="msg <?php echo $msgclass; ?>">
                       <h3><?php echo $msg; ?></h3>
                   </div>
        	   	   <div>
                  <!-- the php output all the products purchased -->
    	   	   	   	    <p>Name of Drink(s): 
                            <span>
                            <?php

                               $totalSelectedSnack = count($product);

                              if ($totalSelectedSnack >= 1) {

                                 for($i=0; $i < $totalSelectedSnack; $i++){

                                    if ($i < $totalSelectedSnack -1) {
                                       echo strtoupper($product[$i]) .', and '; 

                                    }
                                    elseif($i == count($product) -1){
                                        echo strtoupper($product[$i]).'.'; 
                                    }
                                  }

                               }else{
                                 echo "None";
                               } 

                              
                            ?>
                                  
                            </span>
                        </p>
    	   	   	   	  <p>Change: R <span><?php echo $change; ?></span></p>
        	   	   </div>
        	   </section>
        	<!-- !output section-->

        	<!-- coins section-->
        	   <section id="coins">
        	   	   <div class=" title">
        	   	   	  <h2>Insert Coin(s)</h2>
        	   	   </div>

                   <form class="coin-container" method="POST" action="index.php">
                       <div class="flex">
                           <div class="coin">
                            <div class="cent"></div>
                               <input type="number" name="cent"  value="<?php echo isset($_POST['cent'])? $cents : ''; ?>">
                           </div>

                           <div class="coin">
                               <div class="r1"></div>
                               <input type="number" name="r1"  value="<?php echo isset($_POST['r1'])? $r1s : ''; ?>">
                           </div>

                           <div class="coin">
                               <div class="r2"></div>
                               <input type="number" name="r2" value="<?php echo isset($_POST['r2'])? $r2s : ''; ?>">
                           </div>

                           <div class="coin">
                               <div class="r5"></div>
                               <input type="number" name="r5" value="<?php echo isset($_POST['r5'])? $r5s : ''; ?>">
                           </div>
                       </div>

                       <div class="buttons flex">
                           <p><button type="submit" name="total">Total Amount</button> <span>R <?php  echo $_SESSION['total']; ?></span></p>
                           <button type="submit" name="coin-refresh">Reload</button>
                       </div>
                   </form>
        	   </section>
        	<!-- !coins section-->

            <!-- products section -->
                <section id="products">
                    <div class=" title">
                      <h2>Select your Drink</h2>
                    </div>

                        <form class="snack-container" method="POST" action="index.php">
                        <div class="drinks">
                            <h3>Drinks</h3>
                            <div class="flex">
                                <div class="snack">
                                    <label class="container">Red Bull
                                       <input type="checkbox"  name="soda[]" value="red-bull">
                                       <span class="checkmark"></span>
                                    </label>
                                    <p><b>Price</b> R 10.00</p>
                                </div> 
                                <div class="snack">
                                    <label class="container">Mofaya
                                       <input type="checkbox"  name="soda[]" value="mofaya">
                                       <span class="checkmark"></span>
                                    </label>
                                    <p><b>Price</b> R 10.00</p>
                                </div> 
                                <div class="snack">
                                    <label class="container">Vitamin water Zero
                                       <input type="checkbox"  name="soda[]" value="vitamin-water-zero">
                                       <span class="checkmark"></span>
                                    </label>
                                    <p><b>Price</b> R 10.00</p>
                                </div> 
                                <div class="snack">
                                    <label class="container">Pepsi
                                       <input type="checkbox" name="soda[]" value="pepsi">
                                       <span class="checkmark"></span>
                                    </label>
                                   <p><b>Price</b> R 10.00</p>
                                </div>
                                <div class="snack">
                                    <label class="container">Sprite
                                       <input type="checkbox"  name="soda[]" value="sprite">
                                       <span class="checkmark"></span>
                                    </label>
                                    <p><b>Price</b> R 10.00</p>
                                </div>
                                <div class="snack">
                                    <label class="container">Mountain Dew
                                       <input type="checkbox"  name="soda[]" value="mountainDew">
                                       <span class="checkmark"></span>
                                    </label>
                                    <p><b>Price</b> R 10.00</p>
                                </div>
                            </div>
                            
                        </div>
                        <div class="buttons flex">
                            <button type="submit" name="purchase">Buy</button>
                            <button onclick="cancelTrasaction()"  type="submit" name="cancel">Cancel</button>
                        </div>
                    </form>
                </section>
            <!-- !products section -->
        </main>
    <!-- !main section -->
		

    <script type="text/javascript">
      function loadPage(){
           confirm(" By refreshing the page, all data will be lost. Click Ok to continue");
      }
      function cancelTrasaction(){
        confirm("Do You really want to cancel this transaction?");
      }
    </script>
	</body>

</html>

