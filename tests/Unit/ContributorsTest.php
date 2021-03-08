<?php
declare(strict_types=1);

namespace Unit;

use Cotya\Maintainer\Contributors\ContributorsService;
use PHPUnit\Framework\TestCase;

class ContributorsTest extends TestCase
{

    public function testHtmlGeneration(): void
    {

        $contributorService = new ContributorsService();
        $result = $contributorService->generateHtml(file_get_contents(__DIR__ . '/../res/contributorssrc_1.json'));
        $this->assertSame(
            file_get_contents(__DIR__ . '/../res/contributorshtml_1.html'),
            $result
        );
    }
}
