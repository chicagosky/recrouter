<?php
declare(strict_types=1);

namespace App\Utility;

use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\Log\Log;
use Cake\Routing\Router;

class UploadUtil
{
    public function uploadFile(?array $file, bool $isUpload = true, bool $bulk = false): array
    {
        if (!$file) {
            return ['success' => false, 'message' => 'No file provided', 'filename' => ''];
        }
        $maxSize = $bulk ? 5 * 1024 * 1024 : 1024 * 1024;
        $validExtensions = $bulk
            ? ['rtf', 'pdf', 'doc', 'docx', 'txt', 'html', 'zip']
            : ['rtf', 'pdf', 'doc', 'docx', 'txt', 'html'];
        $filename = (string)($file['name'] ?? '');
        $filesize = (int)($file['size'] ?? 0);
        $extension = strtolower((string)substr($filename, (int)strrpos($filename, '.') + 1));
        if (!$filename || !in_array($extension, $validExtensions, true)) {
            return ['success' => false, 'message' => $filename . ' has a file extension that is not supported', 'filename' => $filename];
        }
        if ($filesize >= $maxSize) {
            return ['success' => false, 'message' => $filename . ' exceeds the maximum allowed file size', 'filename' => $filename];
        }
        $tmp = (string)($file['tmp_name'] ?? '');
        $dir = ROOT . DS . 'uploads' . DS . 'tmp';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $path = $dir . DS . $filename;
        $moved = $isUpload ? move_uploaded_file($tmp, $path) : rename($tmp, $path);
        if (!$moved) {
            return ['success' => false, 'message' => 'An error occurred during the move of ' . $filename, 'filename' => $filename];
        }
        if ($isUpload && !$bulk && $extension !== 'pdf' && $extension !== 'zip') {
            $conversion = $this->convertToPdf($path);
            if ($conversion['success']) {
                $filename = $conversion['filename'];
                $path = $conversion['path'];
            }
        }

        return [
            'success' => true,
            'message' => 'The file was uploaded successfully',
            'filename' => $filename,
            'path' => $path,
            'data' => Router::url('/candidates/getFileFor/-1/' . rawurlencode($filename)),
        ];
    }

    public function convertToPdf(string $path): array
    {
        $apiKey = (string)Configure::read('CloudConvert.apiKey');
        if ($apiKey === '') {
            Log::warning('CLOUDCONVERT_API_KEY is not set; leaving original file in place.');

            return ['success' => false, 'filename' => basename($path), 'path' => $path];
        }
        try {
            $client = new Client();
            $response = $client->post('https://api.cloudconvert.com/v2/jobs', json_encode([
                'tasks' => [
                    'import-1' => ['operation' => 'import/upload'],
                    'convert-1' => [
                        'operation' => 'convert',
                        'input' => 'import-1',
                        'output_format' => 'pdf',
                    ],
                    'export-1' => ['operation' => 'export/url', 'input' => 'convert-1'],
                ],
            ]), [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
            ]);
            if (!$response->isSuccess()) {
                return ['success' => false, 'filename' => basename($path), 'path' => $path];
            }
            $pdfName = pathinfo($path, PATHINFO_FILENAME) . '.pdf';
            $pdfPath = dirname($path) . DS . $pdfName;

            return ['success' => true, 'filename' => $pdfName, 'path' => $pdfPath];
        } catch (\Throwable $e) {
            Log::error('CloudConvert error: ' . $e->getMessage());

            return ['success' => false, 'filename' => basename($path), 'path' => $path];
        }
    }

    public function processZipArchive(string $path): array
    {
        $status = [];
        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) {
            return [['success' => false, 'message' => 'Unable to open zip', 'filename' => basename($path)]];
        }
        $extractTo = dirname($path) . DS . 'zip_' . time();
        mkdir($extractTo, 0777, true);
        $zip->extractTo($extractTo);
        $zip->close();
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($extractTo));
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $status[] = $this->uploadFile([
                    'name' => $file->getFilename(),
                    'size' => $file->getSize(),
                    'tmp_name' => $file->getPathname(),
                    'error' => 0,
                ], false, true);
            }
        }

        return $status;
    }
}
