<?php

use PDO;

// ログデータのインポートを（コマンドではなく）プログラムで行うための関数
// データのインポートにmysqlimportコマンドを使用する場合はこの関数を使うことはない
function importFiles(): void
{
    try
    {
        $dbHandler = new PDO(
            'mysql:host=db;dbname=wikilog',
            'user',
            'pass',
            array(PDO::MYSQL_ATTR_LOCAL_INFILE => true)
        );
    }
    catch (PDOException $ex)
    {
        echo 'データベースに接続できません。' . PHP_EOL;
        echo 'Error: ' . $ex->getMessage() . PHP_EOL;

        exit();
    }

    // 古いデータをクリア
    $dbHandler->query('DELETE FROM logs');

    // 新しいデータをインポート
    $path = __DIR__ . '/import/logs.csv';

    $sql = <<<EOT
            LOAD DATA LOCAL INFILE '{$path}' INTO TABLE logs
            FIELDS TERMINATED BY ' '
    EOT;

    $dbHandler->query($sql);

    $dbHandler = null;
}
