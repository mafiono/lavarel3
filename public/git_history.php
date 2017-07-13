<?php

$dir = "..";
$output = array();
chdir($dir);
exec("git log",$output);
$history = array();
foreach($output as $line){
    if(strpos($line, 'commit')===0){
        if(!empty($commit)){
            array_push($history, $commit);
            unset($commit);
        }
        $commit['hash']   = substr($line, strlen('commit'));
    }
    else if(strpos($line, 'Author')===0){
        $commit['author'] = substr($line, strlen('Author:'));
    }
    else if(strpos($line, 'Date')===0){
        $commit['date']   = substr($line, strlen('Date:'));
    }
    else if (!empty($line)){
        if (!isset($commit['message'])) {
            $commit['message'] = '';
        }
        $commit['message']  .= $line;
    }
}
echo '<table>';
echo '<tr>';
    echo '<td width="100px">###</td>';
    echo '<td width="200px">Author</td>';
    echo '<td width="250px">Date</td>';
    echo '<td width="600px">Message</td>';
echo '</tr>';
foreach ($history as $commit) {
    echo '<tr>';
    echo '<td>'.$commit['hash'].'</td>';
    echo '<td>'.$commit['author'].'</td>';
    echo '<td>'.$commit['date'].'</td>';
    echo '<td>'.$commit['message'].'</td>';
    echo '</tr>';
}
echo '</table>';

die();