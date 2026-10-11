<?php
declare(strict_types=1);

namespace App\Resume;

use Cake\Log\Log;

class RchilliParser
{
    public function parse(?string $path = null): string
    {
        Log::warning('RChilli parser is not configured; returning unsuccessful parse stub.');

        return '<ResumeParserData><ParseSuccess>false</ParseSuccess></ResumeParserData>';
    }

    public function getCandidateFromParsedXml(mixed $xml = null, array $response = []): array
    {
        $response['parseSuccess'] = false;

        return $response;
    }
}
