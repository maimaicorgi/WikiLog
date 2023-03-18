<?php declare(strict_types=1);

namespace Wikilog;

require_once __DIR__ . '/../app/Wikilog.php';

use PHPUnit\Framework\TestCase;
use Wikilog\Wikilog;

function fgets(): string
{
    return '1';
}

final class WikilogTest extends TestCase
{
    public function testStart(): void
    {
        $mockedDb = $this->getMockBuilder(\PDO::class)
                        ->disableOriginalConstructor()
                        ->disableArgumentCloning()
                        ->disallowMockingUnknownTypes()
                        ->getMock();

        $mockedDb->method('query')
                ->willReturn(new class extends \PDOStatement implements \IteratorAggregate
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
                                        'count_views' => '1234',
                                        'total_response_size' => '100000'
                                    ],
                                    [
                                        'domain_code' => 'cd',
                                        'page_title' => 'title2',
                                        'count_views' => '5678',
                                        'total_response_size' => '200000'
                                    ]
                                ]
                            );
                        }
                    }
                );

        $wikilog = new Wikilog($mockedDb);
        $wikilog->start();

        $this->expectOutputString('操作を選択してください。' . PHP_EOL
                            . '1 : ログ検索 その１' . PHP_EOL
                            . '2 : ログ検索 その２' . PHP_EOL
                            . '9 : アプリを終了する' . PHP_EOL
                            . '表示する記事数を入力してください。' . PHP_EOL
                            . '2件の検索結果' . PHP_EOL
                            . '-----------------------------------------------------' . PHP_EOL
                            . 'ab title1 1234 100000' . PHP_EOL
                            . 'cd title2 5678 200000' . PHP_EOL
                            . '-----------------------------------------------------' . PHP_EOL);
    }
}
