<?php
// In app/Http/Middleware/SetJsonAcceptHeader.php
namespace App\Http\Middleware;

use Closure;

class SetJsonAcceptHeader
{
    public function handle($request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');
        return $next($request);
    }
}
