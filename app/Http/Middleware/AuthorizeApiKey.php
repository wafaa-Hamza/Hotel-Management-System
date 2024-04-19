<?php

namespace  App\Http\Middleware;

use Closure;
use  App\Models\ApiKey;
use App\Models\ApiKeyAccessEvent;
use Illuminate\Http\Request;

class AuthorizeApiKey
{
    const AUTH_HEADER = 'X-Authorization';

    /**
     * Handle the incoming request
     *
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Contracts\Routing\ResponseFactory|mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {

        $header = $request->header(self::AUTH_HEADER);
        $apiKey = ApiKey::getByKey($header);
       // dd($apiKey);
if($apiKey)
{
    $checkDomain = $apiKey['domain'] === $request->getHost() ;

    $checkexpiredDate = $apiKey['expired_date'] > now();
}
      

        if ($apiKey instanceof ApiKey && $checkDomain = true &&  $checkexpiredDate = true) {
            $this->logAccessEvent($request, $apiKey);
            return $next($request);
        }

        return response([
            'errors' => [[
                'message' => 'Unauthorized'
            ]]
        ], 401);
    }

    /**
     * Log an API key access event
     *
     * @param Request $request
     * @param ApiKey  $apiKey
     */
    protected function logAccessEvent(Request $request, ApiKey $apiKey)
    {
        $event = new ApiKeyAccessEvent;
        $event->api_key_id = $apiKey->id;
        $event->ip_address = $request->ip();
        $event->url        = $request->fullUrl();
        $event->save();
    }
}