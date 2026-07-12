<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuditInteractions
{
    /**
     * Routes déjà journalisées manuellement (via AuditLogger) dans les
     * contrôleurs : on les ignore ici pour éviter les doublons.
     */
    private array $manuallyLoggedRoutes = [
        'login.post',
        'logout',
        'settings.users.toggle',
        'settings.users.reset',
        'settings.permissions.update',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (Auth::check()
            && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)
            && ! $this->isManuallyLogged($request)
        ) {
            $this->record($request, $response);
        }

        return $response;
    }

    private function isManuallyLogged(Request $request): bool
    {
        $routeName = optional($request->route())->getName();

        return $routeName !== null && in_array($routeName, $this->manuallyLoggedRoutes, true);
    }

    private function record(Request $request, Response $response): void
    {
        try {
            $routeName = optional($request->route())->getName() ?? $request->path();

            AuditLog::create([
                'user_id'     => Auth::id(),
                'action'      => $this->mapAction($request->method()),
                'description' => $this->describe($routeName, $response->getStatusCode()),
                'method'      => $request->method(),
                'url'         => $request->fullUrl(),
                'ip_address'  => $request->ip(),
                'user_agent'  => substr((string) $request->userAgent(), 0, 500),
            ]);
        } catch (\Throwable $e) {
            // Ne jamais interrompre la requête à cause de l'audit.
        }
    }

    private function mapAction(string $method): string
    {
        return match ($method) {
            'POST'           => 'created',
            'PUT', 'PATCH'   => 'updated',
            'DELETE'         => 'deleted',
            default          => 'action',
        };
    }

    private function describe(string $routeName, int $status): string
    {
        $result = ($status >= 200 && $status < 400) ? 'succès' : 'échec';

        return "Action sur « {$routeName} » ({$result}, HTTP {$status})";
    }
}
