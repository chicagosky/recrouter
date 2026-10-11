<?php
declare(strict_types=1);

namespace App\Test\TestCase\Utility;

use App\Utility\SearchUtil;
use Cake\TestSuite\TestCase;

class SearchUtilTest extends TestCase
{
    public function testConvertToQueryString(): void
    {
        $this->assertSame('%php%|%cake%', SearchUtil::convertToQueryString('php cake'));
        $this->assertSame('%', SearchUtil::convertToQueryString(''));
    }
}
