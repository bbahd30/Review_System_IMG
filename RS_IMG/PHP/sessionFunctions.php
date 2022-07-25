<?php
if(!isset($_SESSION))
{
    session_start();
}

class mySession
{
    public static function findSessVar ($find, $val)
    {
        if(isset($_SESSION[$find]))
        {
            if($_SESSION[$find] == $val)
            {
                return true;
            }
        }
        return false;
        // foreach $arrOfSessVar as $sessVar => $value
        // {
        //     if (strpos($sessVar , $find) > -1)
        //     {
        //         echo("found");
        //     }
        // }
    }  
}