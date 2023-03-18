<?php

require_once __DIR__ . '/Wikilog.php';

use PDO;
use Wikilog\Wikilog;

try
{
    $dbHandler = new PDO('mysql:host=db;dbname=wikilog', 'user', 'pass');
}
catch (PDOException $ex)
{
    echo 'データベースに接続できません。' . PHP_EOL;
    echo 'Error: ' . $ex->getMessage() . PHP_EOL;

    exit('アプリを終了します。' . PHP_EOL);
}

$dbHandler->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$wikilog = new Wikilog($dbHandler);
$wikilog->start();

$dbHandler = null;
