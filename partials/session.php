<?php
session_start();

if(isset($_POST['submit_pass']) && $_POST['pass'])
{
    $pass=$_POST['pass'];
    if($pass=="æøå123")
    {
        $_SESSION['password']=$pass;
    }
    else
    {
        $error="Incorrect Pssword";
    }
}

if(isset($_POST['page_logout']))
{
    unset($_SESSION['password']);
}