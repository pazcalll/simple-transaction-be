<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreTransactionHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get HTTP method
        $httpMethod = $request->method();

        // Get x-api-key from headers
        $xApiKey = $request->header('X-API-KEY');

        // Concatenate HTTP method and x-api-key
        $concatenated = $httpMethod . ':' . $xApiKey;

        // Generate SHA256 hash
        $expectedHash = hash('sha256', $concatenated);

        // Get X-Signature from headers
        $providedHash = $request->header('X-SIGNATURE');

        // Compare the provided hash with the expected hash
        if ($providedHash !== $expectedHash) {
            // If they don't match, return a 403 Forbidden response
            // return response('Invalid signature.', 403);
            throw new \Exception('Invalid signature.', 403);
        }
        elseif ($request->header('X-API-KEY') != 'DATAUTAMA') {
            throw new \Exception('Invalid API Key.', 403);
        }

        return $next($request);
    }
}
