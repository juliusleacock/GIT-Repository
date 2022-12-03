<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
session_unset();

if(session_destroy()) // Destroying All Sessions
{
    header("Location: /KISS_RAADMA"); // Redirecting To Home Page
}

?>

