<?php
function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

//Then you can use it like this:

debug_to_console("Test");

//This will create an output like this:

//Debug Objects: Test
?>