<?php

function debug($arg, $mode = 2)
{
    $trace = debug_backtrace();
    echo "<div style=\"background-color:#F7A230;border-radius:5px;padding:5px;border: 1px solid #A61D0F\"><strong style=\"font-size:24px; color:white\">Ligne " . $trace[0]['line'] . " &#8594; </strong>
		  <strong style=\"color:#A61D0F; font-size:18px;font-style:italic\"> " . $trace[0]['file'] . "</strong><span style=\"float:right; background-color: white; padding:7px 10px; font-size:12px; border-radius:5px; border: 1px solid #A61D0F\"> --- Débug demandé ---</span></div>";
    if ($mode == 1)
    {
        echo "<pre>";
        print_r($arg);
        echo "</pre>";
    }
    else
    {
        var_dump($arg);
    }
}
