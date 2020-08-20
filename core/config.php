<?php
    $domain = 'http://localhost/yu';
    $tmdb_key = 'bc457c6e89c45bbb2f34a7bdd23688cf';
    define('TMDB_KEY',$tmdb_key);
    // Database Configuration
    $_db['host'] = 'localhost';
    $_db['user'] = 'root';
    $_db['pass'] = '';
    $_db['name'] = 'yu_db';

    $db = new mysqli($_db['host'], $_db['user'], $_db['pass'], $_db['name']) or die('MySQL Error');

   /*  error_reporting(0); */
    