<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calender in vue</title>
    <script src="jquery-3.5.1.min.js"> </script>
</head>

<body>
<?php
    $sdateErr = $edateErr = $stimeErr = $etimeErr = $bookfErr = '';
    $sdate = $edate = $stime = $etime = $bookf = '';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["sdate"])) {
            $sdateErr = "Start Date is required.";
        } else {
            $d1 = $_POST["sdate"];
        }
        if (empty($_POST["edate"])) {
            $edateErr = "End Date is required.";
        } else {
            $d2 = $_POST['edate'];
        }
        if (empty($_POST["stime"])) {
            $stimeErr = "Start Time is required.";
        } else {
            $t1 = $_POST['stime'];
        }
        if (empty($_POST["etime"])) {
            $etimeErr = "End Time is required.";
        } else {
            $t2 = $_POST['etime'];
        }
        if (empty($_POST["bookf"])) {
            $bookfErr = "Book For is required.";
        } else {
            $conten = $_POST['bookf'];
        }
    }

    $file_name='data'. '.json';
    if (!empty($_POST["sdate"])) {
        $d11 = $_POST["sdate"];
        $d12= date_create($d11);
        $d1 = date_format($d12,"d");
    }
    if (!empty($_POST["edate"])) {
        $d21 = $_POST['edate'];
        $d22 = date_create($d21);
        $d2 = date_format($d22,"d");
    }
    if (!empty($_POST["stime"])) {
        $t1 = $_POST['stime'];
    }
    if (!empty($_POST["etime"])) {
        $t2 = $_POST['etime'];
    }
    if (!empty($_POST["bookf"])) {
        $conten = $_POST['bookf'];
    }
        
    if(file_exists("$file_name")) {  
        $current_data=file_get_contents("$file_name"); 
        $array_data=json_decode($current_data, true); 

        $check=0;
        
        
        foreach($array_data as $item){
        for($r1=$t1;$r1<=$t2;$r1++){
            for($c1=$d1;$c1<=$d2;$c1++){
                    for($i=$item['sDate'];$i<=$item['eDate'];$i++){
                        for($j=$item['sTime'];$j<=$item['eTime'];$j++){
                            // echo 'jsondate'.$i.'-'.'jsontime'.$j.'-inputdate'.$c1.'-inputtime'.$r1.'-check'.$check.'<br/>';
                            if($i==$c1 && $j==$r1){
                                $check=$check+1;
                            }
                        }
                    }
                }
            }
        }

        if($d1 != null && $d2 != null && $t1 != null && $t2 != null && $conten != null){
            if($check==0){
            $extra=array( 
            'sDate'=>$d1,
            'eDate'=>$d2,
            'sTime'=>$t1,
            'eTime'=>$t2,
            'bookf'=>$conten
            ); 
            $array_data[]=$extra; 
            file_put_contents("$file_name", json_encode($array_data));
            echo "data added successfully <br/>"; 
        }
        else{
            echo 'Slot is Full ';
        }
    }
        else{
            echo "add data properly";
        }        
    } 
    else {
        if($d1 != null && $d2 != null && $t1 != null && $t2 != null && $conten != null){
            echo "file not selected properly<br/>"; 
        } 
        else{
            echo 'add data properly';  
        }
    } 
?>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="date" min="1999-07-01" max="1999-07-31" id="sdate" name="sdate" placeholder="Start Date">&nbsp;&nbsp;<?php echo "<font color='red'> $sdateErr </font>" ?>
        <input type="date" min="1999-07-01" max="1999-07-31" id="edate" name="edate" placeholder="End Date">&nbsp;&nbsp;<?php echo "<font color='red'> $edateErr </font>" ?><br /><br />
        <input type="number" min="0" max="23" id="stime" name="stime" placeholder="Start Time">&nbsp;&nbsp;<?php echo "<font color='red'> $stimeErr </font>" ?>
        <input type="number" min="0" max="23" id="etime" name="etime" placeholder="End Time">&nbsp;&nbsp;<?php echo "<font color='red'> $etimeErr </font>" ?><br /><br />
        <input type="text" id="bookf" name="bookf" placeholder="Book For ">&nbsp;&nbsp;<?php echo "<font color='red'> $bookfErr </font>" ?><br /><br />
        <input type="submit" value="submit">&nbsp;&nbsp;
        <input type="reset" value="Reset"><br /><br />
    </form>
    <table border=1 width=100%>
        <tbody>
            <?php
            for ($col = 0; $col <= 24; $col++) {
            ?>
                <tr>
                <?php
                for ($row = 0; $row <= 31; $row++) {
                    $id = $col.'-'.$row;
                    if ($row < 1) {
                        if ($col > 0) {
                            echo "<td>" . $col . " Hour </td>";
                        } else {
                            if ($col < 1) {
                                echo "<td>Date :- Hour </td>";
                            }
                        }
                    } else {
                        if ($col < 1) {
                            echo "<td>" . $row . " day </td>";
                        } else {
                            echo "<td id='$id'></td>";
                        }
                    }
                }
            } ?>
                </tr>
        </tbody>
    </table>
    
    <script type = "text/javascript" >

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
            var raw_data = this.responseText;
            var obj = JSON.parse(raw_data);
            var n = obj.length
            var q=0;
            for (let index = 0; index < n; index++) {
                var d1 = parseInt(obj[index].sDate);
                var d2 = parseInt(obj[index].eDate);
                var t1 = parseInt(obj[index].sTime.slice(0,2));
                var t2 = parseInt(obj[index].eTime.slice(0,2));
                var conten= obj[index].bookf;
                let cs=(d2-d1)+1;
                let rs=(t2-t1)+1;
                var z= document.getElementById(t1+"-"+d1);
                
                z.innerHTML=conten;
                z.setAttribute('colspan',cs);
                z.setAttribute('rowspan',rs);
                
                var s=d1;
                while(s<=d2){
                    var p = parseInt(t1)+1;

                    while(p<=t2){
                        var l=document.getElementById(p+"-"+s);
                        l.remove();
                        p=p+1;
                    }
                    s=s+1;
                }
                var j=d1+1;
                while(j<=d2){
                    var z=t1;
                    while(z < t1+1){
                        var v=document.getElementById(z+"-"+j);
                        v.remove();
                        z=z+1;
                    }
                    j=j+1;
                }
            //     q=q+1;
            }
            }
        };
        xhttp.open("GET", "data.json", true);
        xhttp.send();
    </script> 
</body>

</html>
