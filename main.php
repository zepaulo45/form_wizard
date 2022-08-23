<?php
/**
 * Created by Hox.
 * User: JosePaulo 
 * Date: 22/08/2022
 * Time: 23:39
 */
ob_start();
session_start();
echo "<h1>Finalizado o Form Wizard</h1>";

var_dump($_SESSION['wizard']);

ob_end_flush();