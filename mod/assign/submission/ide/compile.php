<?php
    function deleteDir($dirPath) {
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($file);
    }

    function tempdir()
    {
        $tempfile = tempnam(sys_get_temp_dir(), 'moodle');
        if (file_exists($tempfile)) {
            unlink($tempfile);
        }

        mkdir($tempfile);
        if (is_dir($tempfile)) {
            return $tempfile;
        }
    }

    $object['text'] = $_POST['text'];
    $object['lang'] = $_POST['lang'];
    
    $tmp = tempdir();

    $json = json_encode($object);

    $input = fopen($tmp + "\input", "rw");
    fwrite($input, $json);
    fclose($input);

    exec("convert-to-js " + $tmp + "\input " + $tmp + "\output");

    $js = file_get_contents($tmp + "\input", "r");

    deleteDir($tmp);
    
    echo($js);



