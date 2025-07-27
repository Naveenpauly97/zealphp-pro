<?php

namespace ZealPHP\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ZealPHP\Services\AuthService;
use ZealPHP\G;
use OpenSwoole\Core\Psr\Response;

use function ZealPHP\elog;

class AuthMiddleware implements MiddlewareInterface
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $g = G::instance();
        $uri = $g->server['REQUEST_URI'];
        
        // Skip authentication for public routes
        elog("AuthMiddleware: process() for URI: $uri");

        $publicRoutes = ['/login', '/register', '/api/auth/login', '/api/auth/register', '/about'];
        
        foreach ($publicRoutes as $route) {
            elog("AuthMiddleware: process() foreeach iterate URI: ". $route);
            if (strpos($uri, $route) === 0) {
                elog("AuthMiddleware: process() foreach contiotion -------------- URI: ". $uri);
                return $handler->handle($request);
            }
        }

        // Check if user is authenticated
        if (!$this->authService->isAuthenticated()) {
            elog("AuthMiddleware: process() isAuthenticated contiotion start -------------- URI: ");
            // For API routes, return JSON error
            if (strpos($uri, '/api/') === 0) {
                return new Response(
                    json_encode(['error' => 'Authentication required']),
                    401,
                    'Unauthorized',
                    ['Content-Type' => 'application/json']
                );
            }
            
            // For web routes, redirect to login
            elog("AuthMiddleware: process() isAuthenticated contiotion end-------------- URI: ");
            return new Response(
                '',
                302,
                'Found',
                ['Location' => '/login']
            );
        }
        
        elog("AuthMiddleware: process() end-------------- URI: ");
        return $handler->handle($request);
    }
}