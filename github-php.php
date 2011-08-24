<?php
//define("REPO", "https://github.com/api/v2/json/blob/all/tcarlsen/carlsenindex/master");

    /* getting the repo list as it is now 
    echo '<pre>';
    print_r(json_decode(file_get_contents(REPO)));
    echo '</pre>';
*/

echo file_get_contents('https://raw.github.com/tcarlsen/carlsenindex/master/.gitignore');
?>