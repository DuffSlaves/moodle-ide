<?php
    $object['text'] = $_POST['text'];
    $object['lang'] = $_POST['lang'];
    
    $json = json_encode($object);

    $input  = tempnam(sys_get_temp_dir(), 'moodle');
    $output = tempnam(sys_get_temp_dir(), 'moodle');
    $outtxt = "";

    file_put_contents($input, $json);

    exec(getenv("BACKEND_INSTALL_DIR") . "/commands/convert-to-js " . $input . " " . $output, $outtxt);

    error_log("Compiled successfully");

    $js = file_get_contents($output);

    unlink($input);
    unlink($output);
    
    echo $js;



