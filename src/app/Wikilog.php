<?php

namespace Wikilog;

use PDO;
use PDOException;

class Wikilog
{
    private PDO $dbHandler;

    public function start(): void
    {
        $this->connectDb();

        while (true)
        {
            echo '操作を選択してください。' . PHP_EOL;
            echo '1 : ログ検索 その１' . PHP_EOL;
            echo '2 : ログ検索 その２' . PHP_EOL;
            echo '8 : ログデータを追加する' . PHP_EOL;
            echo '9 : アプリを終了する' . PHP_EOL;

            $input = fgets(STDIN);

            switch ($input)
            {
                case '1':
                    break;
                case '2':
                    break;
                case '8':
                    break;
                case '9':
                    exit('アプリを終了します。' . PHP_EOL);
            }
        }
    }

    private function connectDb(): void
    {
        try
        {
            $this->dbHandler = new PDO('mysql:host=db;dbname=wikilog', 'user', 'pass');
        }
        catch (PDOException $ex)
        {
            echo 'データベースに接続できません。' . PHP_EOL;
            echo 'Error: ' . $ex->getMessage() . PHP_EOL;

            exit('アプリを終了します。' . PHP_EOL);
        }
    }
}
