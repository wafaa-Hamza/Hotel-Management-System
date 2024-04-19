<?php

namespace App\Http\Middleware;
use Closure;

use App\Models\Plan;
use App\Models\CustomTenant;
//use Spatie\Multitenancy\Models\Tenant;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DomainSubscrip
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //  $tenantCurrent = CustomTenant::current();
       
        //  $plan = Plan::first();
        // $subscribe = $tenantCurrent->isValidSubscriptionPlan($plan->name,$tenantCurrent->id); 
        // if($subscribe = false)
        // {
        //     return $next($request);
        // }else{
        //     return response()->json(['Message' => 'Not Subscription'],401);        }
        return $next($request);
    }
}
