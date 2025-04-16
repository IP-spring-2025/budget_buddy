#!/usr/local/bin/php
<?php 
session_start();

$balance = 0;

$living = 0;
$saving = 0;
$investing = 0;
$other = 0;
$data = [];
$x = [];
$y = [];
$row_count = 0;

if(isset($_SESSION['UserID']) && isset($_SESSION['Username'])){
    $userID = $_SESSION['UserID'];
    $username = $_SESSION['Username'];

  
      //creds for db connnection
      $servername = "mysql.cise.ufl.edu";  
      $dbUsername = "ngleason";
      $dbPassword = "Bigtime12";
      $dbname     = "Budget_Buddy";

      // Create connection
      $conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbname);
      // Check connection
      if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
      }

      #get total budget
      $balance = 0;
      $sql = "SELECT * FROM users WHERE UserID = '".$userID."'";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
          $balance = $row['Balance'];
        }
      }


      #get transaction values
      $sql = "SELECT * FROM transactions WHERE UserID = '".$userID."' LIMIT 100";
      $result = mysqli_query($conn, $sql);

      //set total amounts for each budget category & store transactions (all time)
      $row_count = mysqli_num_rows($result);
      if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
          $data[] = $row;
          $x[] = $row['TransactionDate'];
          $y[] = $row['Amount'];

          if($row['Category'] == 'Living'){
            $living += $row['Amount'];
          } else if($row['Category'] == 'Saving'){
            $saving += $row['Amount'];
          } else if($row['Category'] == 'Investing'){
            $investing += $row['Amount'];
          } else if($row['Category'] == 'Other'){
            $other += $row['Amount'];
          }
        }
      } 

      //set 

} else{
  echo "Session for userid and username not set!";
}

function BudgetPercent($category){
  global $living;
  global $investing;
  global $other;
  global $saving;

  $total = $living + $investing + $other + $saving;

  $res = 0;

  if($category == 'Living'){
    $res = $living / $total;
  } else if($category == 'Saving'){
    $res = $saving / $total;
  } else if($category == 'Investing'){
    $res = $investing / $total;
  } else if($category == 'Other'){
    $res = $other / $total;
  }

  $res *= 100;
  
  return number_format($res,2);
}


function DisplayTransactions($category){
  if(isset($_SESSION['UserID']) && isset($_SESSION['Username'])){
    $userID = $_SESSION['UserID'];
    $username = $_SESSION['Username'];

  
      //creds for db connnection
      $servername = "mysql.cise.ufl.edu";  
      $dbUsername = "ngleason";
      $dbPassword = "Bigtime12";
      $dbname     = "Budget_Buddy";

      // Create connection
      $conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbname);
      // Check connection
      if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
      }

      $sql = "SELECT * FROM transactions WHERE UserID = '".$userID."' LIMIT 100";
      $result = mysqli_query($conn, $sql);

      echo '<table class="table table-bordered border-primary"> <thead> <tr>';
      echo '<th scope="col">#</th> <th scope="col">Description</th> <th scope="col">Amount</th> <th scope="col">Date</th> </tr> </thead> <tbody>';
    

      if (mysqli_num_rows($result) > 0) {
        // output data of each row
        $count = 1;
        while($row = mysqli_fetch_assoc($result)) {
          if($row['Category'] == $category){
            echo '<tr>';
            echo '<th scope="row">'.$count.'</th>';
            echo '<td>'.$row['Description'].'</td>';
            echo '<td>$'.$row['Amount'].'</td>';
            echo '<td>'.$row['TransactionDate'].'</td>';
            echo '</tr>';
          }
        }
      } else {
        echo '<tr>';
        echo '<td colspan="4"> 0 results </td>';
        echo '</tr>';
      }

      echo '</tbody>'.' </table>';
      

  } else{
    echo "Session for userid and username not set!";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body style="width:100vw; height:100vh">
    
    <script src="../navbar.js"></script>
   
    <header class="bg-primary text-white text-center py-5">
          
        <div class="container">
            <div class="card">
                <div class="card bg-dark" style="color:white"><h3>Total Balance:</h3></div>
                <div class="card">$<?php echo $balance?></div>
                
            </div>
        </div>
        <button type="button" class="btn btn-secondary" style="width:200px;margin-top:10px;border:1px solid white;">
          <i class="bi bi-gear-fill"></i> 
        </button>
    </header>
    <div class="container my-5 w-75 p-3 text-center">
        <div style="border-radius: 5px; padding: 15px; border: 1px solid rgb(204, 204, 204)">
            <h2>Expenses Over Time</h2>
            <canvas id="line-graph"></canvas>
        </div>
    </div>
    <div class="container my-5 w-75 p-3" style="border-radius: 10px; padding: 15px; border: 1px solid rgb(204, 204, 204)">
        <div class="row">
            <div class="col-md-6 text-center" style="border-radius: 5px; padding: 15px; margin-top: 10px; border: 1px solid rgb(204, 204, 204)">
                <h2>Budget Breakdown</h2>
                <canvas id="pie-chart"></canvas>
            </div>
            <div class="col-md-6" style='margin-top: 10px;'>
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                      <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                          Living Expense Items
                        </button>
                      </h2>
                      <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <?php DisplayTransactions('Living'); ?>  
                        </div>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                          Investing Expense Items
                        </button>
                      </h2>
                      <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                          <?php DisplayTransactions('Investing'); ?>  
                        </div>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                          Saving Expense Items
                        </button>
                      </h2>
                      <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <?php DisplayTransactions('Saving'); ?> 
                        </div>
                      </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Other Expense Items
                          </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                          <div class="accordion-body">
                              <?php DisplayTransactions('Other'); ?>
                          </div>
                          
                        </div>
                      </div>
                  </div>
            </div>
            <div class="container" style="margin-top: 10px;">
                <!-- Card Component -->
                <div class="card">
                  <div class="card-header text-center">
                    Item Percentages
                  </div>
                  <div class="card-body" style="display: flex; flex-direction: row; justify-content: space-evenly;">
                    <div>
                      <span class="item-name">Living</span>
                      <span class="badge bg-danger" ><?php echo BudgetPercent('Living')?>%</span>
                    </div>
                    <div>
                      <span class="item-name">Saving</span>
                      <span class="badge bg-primary"><?php echo BudgetPercent('Saving')?>%</span>
                    </div>
                    <div>
                        <span class="item-name">Investing</span>
                        <span class="badge bg-warning"><?php echo BudgetPercent('Investing')?>%</span>
                      </div>
                    <div>
                      <span class="item-name">Other</span>
                      <span class="badge bg-success"><?php echo BudgetPercent('Other')?>%</span>
                    </div>
                  </div>
                </div>
              </div>
        </div>
    </div>
    

    

    <div class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 Budget Buddy. All rights reserved.</p>
    </div>

    <script>
      window.onload = function () {
        //https://www.w3schools.com/ai/ai_chartjs.asp
        //https://stackoverflow.com/questions/5618925/convert-php-array-to-javascript
        //https://stackoverflow.com/questions/37204298/how-can-i-hide-dataset-labels-in-chart-js-v2

        //line graph of spending over a year
        console.log(<?php echo $data[0]['Amount']?>);

        //x = transaction dates
        var xValues = [<?php echo '"'.implode('","', $x).'"' ?>];
        //y = amounts
        var yValues = [];

        //convert amounts to numbers
        var y = [<?php echo '"'.implode('","', $y).'"' ?>];
        for (var i = 0; i < y.length; i++){
          yValues.push(parseFloat(y[i]));
        }

        //set the line graph up
        var ctx1 = document.getElementById("line-graph").getContext("2d");
        new Chart(ctx1, {
          type: "line",
          data: {
            labels: xValues,
            datasets: [{
              backgroundColor:"rgba(0,0,255,1.0)",
              borderColor: "rgba(0,0,255,0.1)",
              data: yValues
            }]
          },
          options: {
            plugins: {
              legend: {
                display: false
              }
            }
          } 
        });

        //PIE CHART - actual expenses budget breakdown
        var living = Number(<?php echo $living?>);
        var saving = Number(<?php echo $saving?>);
        var investing = Number(<?php echo $investing?>);
        var other =Number(<?php echo $other?>);

        const data = {
          labels: ['Living', 'Saving', 'Investing', 'Other'],
          datasets: [{
            data: [living, saving, investing, other],
            backgroundColor: ['#FF5733', '#33C3FF', '#FFEB33', '#29AB87'],
            hoverOffset: 4
          }]
        };

        const config = {
          type: 'pie',
          data: data,
          options: {
            responsive: true,
            plugins: {
              legend: {
                position: 'top',
              },
              tooltip: {
                callbacks: {
                  label: function(tooltipItem) {
                    return tooltipItem.label + ': ' + tooltipItem.raw;
                  }
                }
              }
            }
          }
        };

        const ctx = document.getElementById('pie-chart').getContext('2d');
        new Chart(ctx, config);

        
      };
      </script>
    
      <!-- Add Bootstrap JS -->
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    
      
    
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery Script Example -->
    
    <script>
        $(document).ready(function() {
            console.log("jQuery is working!");
            $("#navbar").load("/Users/maryhanson/Desktop/budget_buddy/frontend/home/navbar.html"); 
        });

    </script>
</body>
</html>
