<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Obtener el subdominio de la URL
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0];

        // Buscar el tenant por subdominio
        $tenant = Tenant::where('subdomain', $subdomain)->first();

        if (!$tenant) {
            return response()->json([
                'error' => 'Tenant not found',
                'message' => 'El subdominio no corresponde a ningún tenant válido'
            ], 404);
        }

        // Establecer el tenant en el contexto de la aplicación
        app()->instance('tenant', $tenant);
        config(['app.current_tenant' => $tenant]);

        return $next($request);
    }
}
