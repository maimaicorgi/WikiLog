<?php declare(strict_types=1);

namespace Wikilog;

require_once __DIR__ . '/../app/Wikilog.php';

use PHPUnit\Framework\TestCase;
use Wikilog\Wikilog;

class InputSimulator
{
    private array $values;

    public function setValues(array $values): void
    {
        $this->values = $values;
    }

    public function next(): string
    {
        return (string)array_shift($this->values);
    }
}

function fgets(): string
{
    return WikilogTest::$simulator->next();
}

final class WikilogTest extends TestCase
{
    public static InputSimulator $simulator;

    public function testStart_menu1(): void
    {
        WikilogTest::$simulator = new InputSimulator();
        WikilogTest::$simulator->setValues([1, 2, 9]);

        $mockedDb = $this->getMockBuilder(\PDO::class)
                        ->disableOriginalConstructor()
                        ->disableArgumentCloning()
                        ->disallowMockingUnknownTypes()
                        ->getMock();

        $mockedResult = new class extends \PDOStatement implements \IteratorAggregate
                            {
                                function rowCount(): int
                                {
                                    return 2;
                                }

                                function getIterator(): \Iterator
                                {
                                    return new \ArrayIterator(
                                        [
                                            [
                                                'domain_code' => 'ab',
                                                'page_title' => 'title1',
                                                'count_views' => '1234'
                                            ],
                                            [
                                                'domain_code' => 'cd',
                                                'page_title' => 'title2',
                                                'count_views' => '5678'
                                            ]
                                        ]
                                    );
                                }
                            };

        $sql = <<<EOT
            SELECT domain_code, page_title, count_views
            FROM logs
            ORDER BY count_views DESC
            LIMIT 2
        EOT;

        $mockedDb->method('query')
        ->will($this->returnCallback(
            function($arg) use($sql, $mockedResult)
            {
                return $arg === $sql? $mockedResult : new \PDOStatement();
            }
        ));

        // ------------------------ Test ------------------------
        $wikilog = new Wikilog($mockedDb);
        $wikilog->start();

        $this->expectOutputString('操作を選択してください。' . PHP_EOL
                            . '1 : ログ検索 その１' . PHP_EOL
                            . '2 : ログ検索 その２' . PHP_EOL
                            . '9 : アプリを終了する' . PHP_EOL
                            . '表示する記事数を入力してください。' . PHP_EOL
                            . '2件の検索結果' . PHP_EOL
                            . '-----------------------------------------------------' . PHP_EOL
                            . 'ab title1 1234' . PHP_EOL
                            . 'cd title2 5678' . PHP_EOL
                            . '-----------------------------------------------------' . PHP_EOL
                            . '操作を選択してください。' . PHP_EOL
                            . '1 : ログ検索 その１' . PHP_EOL
                            . '2 : ログ検索 その２' . PHP_EOL
                            . '9 : アプリを終了する' . PHP_EOL
                            . 'アプリを終了します。' . PHP_EOL);
    }

    public function testStart_menu2(): void
    {
        WikilogTest::$simulator = new InputSimulator();
        WikilogTest::$simulator->setValues([2, 'ab cd', 9]);

        $mockedDb = $this->getMockBuilder(\PDO::class)
                        ->disableOriginalConstructor()
                        ->disableArgumentCloning()
                        ->disallowMockingUnknownTypes()
                        ->getMock();

        $mockedResult = new class extends \PDOStatement implements \IteratorAggregate
                            {
                                function rowCount(): int
                                {
                                    return 2;
                                }

                                function getIterator(): \Iterator
                                {
                                    return new \ArrayIterator(
                                        [
                                            [
                                                'domain_code' => 'ab',
                                                'total_views' => '1234'
                                            ],
                                            [
                                                'domain_code' => 'cd',
                                                'total_views' => '5678'
                                            ]
                                        ]
                                    );
                                }
                            };

        $sql = <<<EOT
            SELECT domain_code, SUM(count_views) AS total_views
            FROM logs
            WHERE domain_code IN ('ab', 'cd')
            GROUP BY domain_code
            ORDER BY total_views DESC
        EOT;

        $mockedDb->method('query')
        ->will($this->returnCallback(
            function($arg) use($sql, $mockedResult)
            {
                return $arg === $sql? $mockedResult : new \PDOStatement();
            }
        ));

        // ------------------------ Test ------------------------
        $wikilog = new Wikilog($mockedDb);
        $wikilog->start();

        $this->expectOutputString('操作を選択してください。' . PHP_EOL
                            . '1 : ログ検索 その１' . PHP_EOL
                            . '2 : ログ検索 その２' . PHP_EOL
                            . '9 : アプリを終了する' . PHP_EOL
                            . '表示するドメインコードをスペース区切りで入力してください。' . PHP_EOL
                            . '2件の検索結果' . PHP_EOL
                            . '-----------------------------------------------------' . PHP_EOL
                            . 'ab 1234' . PHP_EOL
                            . 'cd 5678' . PHP_EOL
                            . '-----------------------------------------------------' . PHP_EOL
                            . '操作を選択してください。' . PHP_EOL
                            . '1 : ログ検索 その１' . PHP_EOL
                            . '2 : ログ検索 その２' . PHP_EOL
                            . '9 : アプリを終了する' . PHP_EOL
                            . 'アプリを終了します。' . PHP_EOL);
    }

    // public function testStart_menu9(): void
    // {

    // }
}
