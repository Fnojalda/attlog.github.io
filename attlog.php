<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_FILES['attlog']) && $_FILES['attlog']['error'] === 0) {

        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];
        $name = $_POST['nam'];
        

        $tmpName = $_FILES['attlog']['tmp_name'];
        $lines = file($tmpName);

        $output = [];

        foreach ($lines as $line) {

            // Extract date (YYYY-MM-DD)
            if (preg_match('/(\d{4}-\d{2}-\d{2})/', $line, $dateMatch)) {
                $logDate = $dateMatch[1];

                // Check if within range
                if ($logDate >= $startDate && $logDate <= $endDate) {

                    // Fix ID (preserve spacing)
                    if (preg_match('/^(\s*)(\d{1,10})(.*)$/', $line, $matches)) {

                        $id = $matches[2];
                        $rest = $matches[3];

                        $formattedID = str_pad($id, 5, " ", STR_PAD_LEFT);

                        $line = $formattedID . $rest;
                    }

                    $output[] = $line;
                }

            } else {
                // If no date found, keep original (optional: you can skip instead)
                $output[] = $line;
            }
        }

        $finalData = implode("", $output);

        // Download file
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="' . $name . '.dat"');
        header('Content-Length: ' . strlen($finalData));
        header("http://localhost/attlog/attlog.php");
        echo $finalData;
        
        exit;
        
    } else {
        echo " ";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>ATTLOG Formatter + Date Filter</title>
      <style>

        body{
           
        }
        
        .bt-dl {
        border: solid, 1px;
        width: 130px;
        height: 60px;
        border-radius: 15px;
        background-color: #f6b092;
        color: black;
        
        
    }

    .bt-dl:hover {
        color: white;
        }
        
    .up input[type="file"]{
        display:none;
    }

     .up {
        border: solid, 1px;
        border-radius: 15px;
        background-color: #f6b092;
        
    }

    .up:hover {
        color: white;
        }
        
    .uptwo {
        width: 20px;
        height: 60px;

    }

        
    </style>
</head>
<body>

<br><br>

<form method="POST" enctype="multipart/form-data" style="border: solid, 1px; padding-top: 30px; padding-left: 30px; padding-bottom: 30px;  background-color: #f6a192;">

    <center><h1 style="color: white;">ATTLOG AUTOMATIC TRIM</h1></center><hr>

    <br><label style="font-size: 25px; color: white;">SELECT BRANCH</label><br><br>
     <select name="nam" id="nam" size="3" style="width:500px; background-color:#f6d992;" required>
        <option value="SBPM Cainta" style="font-size: 20px;">SBPM Cainta</option>
         <option value="BK" style="font-size: 20px;">BK</option>
          <option value="Mandurriao" style="font-size: 20px;">Mandurriao</option>
        
    </select>
    <br><br><hr><br>

    <label style="font-size: 25px; color: white;" >START DATE:</label><br><br>
    <input type="date" name="start_date" style="width:500px; height:30px; font-size: 20px; background-color:#f6d992;"  required><br><br><hr><br>

    <label style="font-size: 25px; color: white;">END DATE:</label><br><br>
    <input type="date" name="end_date" style="width:500px; height:30px; font-size: 20px; background-color:#f6d992;" required><br><br><hr><br>

    <label style="font-size: 15px;" class="up">UPLOAD ATTLOG FILE
    <input type="file" name="attlog" class="uptwo" required></label><br><br><hr><br>

    <button type="submit" class="bt-dl" id="">Download</button>
    <br><br>
    <br><br>
    <br><br>
    <br><br>
    <br><br>
    
    
</form>

</body>
</html>