<?php
/* configurations below */
define("GIT_USER", "tcarlsen");
define("GIT_PASS", ""); /* I will try to make this work for private repos aswell */
define("GIT_REPO", "github-php");

define("PATH", "repo/"); /* where shall the script unload the files */

/* the functions that makes it work */

function flush_buffers(){ 
    ob_end_flush(); 
    ob_flush(); 
    flush(); 
    ob_start(); 
} 

function create_new_file($file_structure) {
    $new_file_content = file_get_contents("https://raw.github.com/".GIT_USER."/".GIT_REPO."/master/".$file_structure);
        
    if (!is_dir(PATH.dirname($file_structure))) 
    {
    	mkdir(PATH.dirname($file_structure), 0, true);
    }
    $new_file = fopen(PATH.$file_structure, 'w') or die("There was a problem trying to make ".$file_structure);
    fwrite($new_file, $new_file_content);
    fclose($new_file);
}

function clone_repo()
{
    /* lets make sure that a clone is not allready done */
    if (file_exists('commit_stage.txt')) {
    	echo 'the clone action have allreday ran ones!';
    	exit;
    } 
    set_time_limit(0);
    /* lets get the list of files in the repo and make it into an array */
    $repo_url = "https://github.com/api/v2/json/blob/all/".GIT_USER."/".GIT_REPO."/master";
    $repo_file_array = json_decode(file_get_contents($repo_url), true);

    /* now we need to create all the files one by one */
    create_files();
    foreach ($repo_file_array['blobs'] as $file_structure => $sha)
    {
        create_new_file($file_structure);
        //flush_buffers();
        echo $file_structure.' was created<br>';
    }
    
    /* now lets save the sha data to compare with on a pull */
    $commit_url = "https://github.com/api/v2/json/repos/show/".GIT_USER."/".GIT_REPO."/branches";
    $commit_array = json_decode(file_get_contents($commit_url), true);
    
    $new_file = fopen('commit_stage.txt', 'w');
    fwrite($new_file, $commit_array['branches']['master']);
    fclose($new_file);
    
    /* end of clone_repo function */
}

function pull()
{
    if (!file_exists('commit_stage.txt')) {
    	echo 'You need to run clone first!';
    	exit;
    }
    /* lets start testing if there is anything new */
    $commit_url = "https://github.com/api/v2/json/repos/show/".GIT_USER."/".GIT_REPO."/branches";
    $commit_array = json_decode(file_get_contents($commit_url), true);
    $commit_stage = fgets(fopen('commit_stage.txt', "r"),50);
    if ($commit_stage == $commit_array['branches']['master']) {
    	echo 'nothing new in this repo!';
    	exit;
    } 
	/* now we need to know wich commits are newer then the one we got */
	$commits_url = "https://github.com/api/v2/json/commits/list/".GIT_USER."/".GIT_REPO."/master";
    $repo_commits_array = json_decode(file_get_contents($commits_url), true);
    
    foreach ($repo_commits_array['commits'] as $commit)
    {
        if ($commit['id'] == $commit_stage) {
        	break;
        }
        
        //flush_buffers();
        echo $commit['message'].':<br>';

        $commit_url = "https://github.com/api/v2/json/commits/show/".GIT_USER."/".GIT_REPO."/".$commit['id'];
        $commit_file_array = json_decode(file_get_contents($commit_url), true);
        
        if (!empty($commit_file_array['commit']['added'])) 
        {
        	foreach ($commit_file_array['commit']['added'] as $file)
        	{
        	    create_new_file($file['filename']);
        	    echo $file['filename'].' was added<br>';
        	}
        }
        
        if (!empty($commit_file_array['commit']['modified'])) 
        {
        	foreach ($commit_file_array['commit']['modified'] as $file)
        	{
        	    create_new_file($file['filename']);
        	    echo $file['filename'].' was modified<br>';
        	}
        }
        
        if (!empty($commit_file_array['commit']['removed'])) 
        {
        	foreach ($commit_file_array['commit']['removed'] as $file)
        	{
        	    unlink($file['filename']);
        	    echo $file['filename'].' was removed<br>';
        	}
        }
        echo '<br>';
    }
    
    /* now lets update the sha data to compare with the next pull */
    $new_file = fopen('commit_stage.txt', 'w');
    fwrite($new_file, $commit_array['branches']['master']);
    fclose($new_file);
    /* end of pull function */
}

if ($_GET) {
	if ($_GET['action'] == 'clone') {
        clone_repo();
    } elseif ($_GET['action'] == 'pull') {
    	pull();
    }
} else {
	echo '<a href="?action=clone">clone</a> - ';
	echo '<a href="?action=pull">pull</a>';
}
?>