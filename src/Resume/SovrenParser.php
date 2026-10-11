<?php
declare(strict_types=1);

namespace App\Resume;

use Cake\Core\Configure;
use Cake\Log\Log;

class SovrenParser
{
    public function parse(?string $path = null): string
    {
        $parsedXml = '<Resume><ParseSuccess>false</ParseSuccess></Resume>';
        if (!$path) {
            return $parsedXml;
        }
        $account = (string)Configure::read('ResumeParsers.sovrenAccountId');
        $key = (string)Configure::read('ResumeParsers.sovrenServiceKey');
        if ($account === '' || $key === '') {
            Log::warning('Sovren credentials are not configured.');

            return $parsedXml;
        }
        try {
            $content = @file_get_contents($path);
            if (!$content) {
                return $parsedXml;
            }
            $client = new \SoapClient((string)Configure::read('ResumeParsers.sovrenWsdl'), ['soap_version' => SOAP_1_2]);
            $hrxml = $client->ParseResume([
                'request' => [
                    'AccountId' => $account,
                    'ServiceKey' => $key,
                    'FileBytes' => $content,
                ],
            ]);
            $parsedXml = $hrxml->ParseResumeResult->Xml;
        } catch (\Throwable $e) {
            Log::error('Sovren parse error: ' . $e->getMessage());
            $parsedXml = '<ResumeParserData><ParseSuccess>false</ParseSuccess><ParseError>' .
                h($e->getMessage()) . '</ParseError></ResumeParserData>';
        }

        return $parsedXml;
    }

    public function getCandidateFromParsedXml(mixed $xml = null, array $response = []): array
    {
        if (!is_string($xml)) {
            $response['parseSuccess'] = false;

            return $response;
        }
        $cakeXml = simplexml_load_string($xml);
        if ($cakeXml === false) {
            $response['parseSuccess'] = false;

            return $response;
        }
        $parseSuccess = $cakeXml->xpath('//ParseSuccess');
        if (!$parseSuccess) {
            $cakeXml->registerXPathNamespace('hr', 'http://ns.hr-xml.org/2006-02-28');
            $node = $cakeXml->xpath('//hr:PersonName');
            $response['firstname'] = isset($node[0]) ? (string)$node[0]->GivenName : '';
            $response['lastname'] = isset($node[0]) ? trim((string)$node[0]->MiddleName . ' ' . (string)$node[0]->FamilyName) : '';
            $node = $cakeXml->xpath('//hr:ContactMethod//hr:Telephone');
            $response['phone'] = isset($node[0]) ? (string)$node[0]->FormattedNumber : '';
            $node = $cakeXml->xpath('//hr:ContactMethod//hr:Mobile');
            $response['phone_alt'] = isset($node[0]) ? (string)$node[0]->FormattedNumber : '';
            $node = $cakeXml->xpath('//hr:ContactMethod//hr:InternetEmailAddress');
            $response['email'] = isset($node[0]) ? (string)$node[0] : '';
            $node = $cakeXml->xpath('//hr:ContactMethod//hr:PostalAddress');
            $response['city'] = isset($node[0]) ? (string)$node[0]->Municipality : '';
            $response['state'] = isset($node[0]) ? (string)$node[0]->Region : '';
            $response['parseSuccess'] = true;
        } else {
            $response['parseSuccess'] = false;
        }

        return $response;
    }

    public function saveXml(string $xml, ?string $path = null): bool
    {
        if (!$path) {
            return false;
        }

        return (bool)file_put_contents($path, $xml);
    }
}
