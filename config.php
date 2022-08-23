<?php
/**
 * Created by Hox.
 * User: JosePaulo 
 * Date: 22/08/2022
 * Time: 23:39
 */

define('DATABASE', [
    'HOST' => 'localhost',
    'USER' => 'root',
    'PASS' => '',
    'NAME' => 'db_form_wizard'
]);

require_once __DIR__ . '/Source/Crud/Conn.php';
require_once __DIR__ . '/Source/Crud/Read.php';
require_once __DIR__ . '/Source/Crud/Create.php';