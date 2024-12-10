<?php

function qsearch($table, $column, $target, $conn)
{
    $query = "SELECT * FROM $table WHERE $column = '$target' ";
    $result = mysqli_query($conn, $query);
    return $result;
}

?>

<!DOCTYPE html>
<script>
    function showpassword($idname) {
        var x = document.getElementById($idname);
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>