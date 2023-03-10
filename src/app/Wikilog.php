<?php

namespace Wikilog;

class Wikilog
{
    public function start(): void
    {
        $this->checkDb();

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
                    exit('アプリを終了しました。' . PHP_EOL);
            }
        }
    }

    private function checkDb(): void
    {
    }
}
