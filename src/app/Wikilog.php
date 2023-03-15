<?php

namespace Wikilog;

use PDO;
use PDOException;
use PDOStatement;

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

                    $domains = explode(' ', trim(fgets(STDIN)));
                    $this->showViewsByDomain($domains);

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
            $this->dbHandler->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
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
        $sql = <<<EOT
            SELECT domain_code, page_title, count_views
            FROM logs
            ORDER BY count_views DESC
            LIMIT {$num}
        EOT;

        $result = $this->dbHandler->query($sql);

        $this->printResult($result);
    }

    private function showViewsByDomain(array $domains): void
    {
        $inClause = join("', '", $domains);

        $sql = <<<EOT
            SELECT domain_code, SUM(count_views) AS total_views
            FROM logs
            WHERE domain_code IN ('{$inClause}')
            GROUP BY domain_code
            ORDER BY total_views DESC
        EOT;

        $result = $this->dbHandler->query($sql);

        $this->printResult($result);
    }

    private function printResult(PDOStatement $result): void
    {
        echo $result->rowCount() . '件の検索結果' . PHP_EOL;
        echo '-----------------------------------------------------' . PHP_EOL;
        foreach ($result as $row)
        {
            echo join(' ', $row) . PHP_EOL;
        }
        echo '-----------------------------------------------------' . PHP_EOL;
    }
}
