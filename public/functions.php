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