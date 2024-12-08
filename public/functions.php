<?php 
    
        function qsearch($table,$column,$target,$conn){
            $query = "SELECT * FROM $table WHERE $column = '$target' ";
            $result = mysqli_query($conn, $query);
            return $result;
        }
        
?>