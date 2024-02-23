<?php
/** Create DB Folder if not existing yet */
if(!is_dir(__DIR__.'./db'))
    mkdir(__DIR__.'./db');
/** Define DB File Path */
if(!defined('db_file')) define('db_file',__DIR__.'./db/gpa_db.db');
/** Define DB File Path */
if(!defined('tZone')) define('tZone',"Asia/Manila");
if(!defined('dZone')) define('dZone',ini_get('date.timezone'));

/** DB Connection Class */
Class DBConnection extends SQLite3{
    protected $db;
    function __construct(){
        /** Opening Database */
        $this->open(db_file);
        $this->exec("PRAGMA foreign_keys = ON;");
        $this->exec("CREATE TABLE IF NOT EXISTS `grade_table` (
            `gt_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `grade_from` Float(2,2) NOT NULL DEFAULT 0,
            `grade_to` Float(2,2) NOT NULL DEFAULT 0,
            `letter_grade` TEXT NOT NULL,
            `scale` Float(2,2) NOT NULL DEFAULT 0
        )"); 

    }
    function __destruct(){
        /** Closing Database */
         $this->close();
    }
}

$conn = new DBConnection();