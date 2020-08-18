<?php
    $domain = 'http://localhost/yu';

    // Database Configuration
    $_db['host'] = 'localhost';
    $_db['user'] = 'root';
    $_db['pass'] = '';
    $_db['name'] = 'yu_db';

    $db = new mysqli($_db['host'], $_db['user'], $_db['pass'], $_db['name']) or die('MySQL Error');

    error_reporting(0);
    