<?php
declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UnwrapCake2DataMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();
        if (is_array($data)) {
            if (isset($data['data']) && is_array($data['data'])) {
                $unwrapped = $data['data'] + $data;
                unset($unwrapped['data']);
                $data = $unwrapped;
            }
            if (isset($data['User']['username'])) {
                $data['username'] = $data['User']['username'];
                $data['password'] = $data['User']['password'] ?? ($data['password'] ?? '');
            }
            $request = $request->withParsedBody($data);
        }

        return $handler->handle($request);
    }
}
