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
            echo '9 : アプリを終了する' . PHP_EOL;

            $menu = fgets(STDIN);

            switch ($menu)
            {
                case '1':
                    echo '表示する記事数を入力してください。' . PHP_EOL;

                    $num = (int)fgets(STDIN);
                    $this->showPageViews($num);

                    break;
                case '2':
                    echo '表示するドメインコードをスペース区切りで入力してください。' . PHP_EOL;

                    $domain = fgets(STDIN);
                    $this->showViewsByDomain($domain);

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

    private function showPageViews(int $num): void
    {
    }

    private function showViewsByDomain(string ...$domains): void
    {
    }
}
