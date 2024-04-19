<?php

namespace App\Traits;

use LogicException;
use App\Models\Plan;
use OverflowException;
use App\Models\Feature;
use OutOfBoundsException;
use App\Models\CustomTenant;
use App\Models\Subscription;
use App\Models\FeatureTicket;
use App\Models\SubscriptionRenewal;
use Carbon\Carbon;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;


trait HasSubscriptions{

    public function createPlanForTenant($tenantID,$planID,$expired_at,$started_at,$was_switched=0)
    {
     // dd($tenantID,$planID,$expired_at,$started_at,$was_switched);
     $plan = Plan::where('id',$planID)->select('grace_days_ended_at')->first();
     if($plan->grace_days_ended_at != 0 || $plan->grace_days_ended_at != null)
     {
      $grace_days_ended_at =Carbon::parse($expired_at)->addDays($plan->grace_days_ended_at);
     }

      $createTenantPlan = Subscription::create([
        'subscriber_id' =>$tenantID,
        'plan_id' => $planID,
        'expired_at' =>$expired_at,
        'grace_days_ended_at' =>(!empty($grace_days_ended_at)) ? $grace_days_ended_at : null,
        'started_at' =>$started_at,
        'subscriber_type' =>'App\Model\Tenant',
        'was_switched ' =>$was_switched,
       ]);

       return $createTenantPlan;
    }

    public function switchPlan()
    {

    }

    public function isPlanOverdue($subscriptionID)
    {
      $notOverDue = Subscription::where('id',$subscriptionID)->whereDate('grace_days_ended_at','>=',Carbon::now())->first();
      if(empty($notOverDue))
      {
        return false;
      }else{
        return true;
      }
    }

    // public function renew($subscriptionID)
    // {
    //   SubscriptionRenewal::create(
    //     [
    //       'overdue' => $this-> $notOverDue($subscriptionID),
    //       'renewal' => ,
    //       'subscription_id' => $this->id,
    //     ]);
    // }
      
      public function getPlan()
      {
        return $this->plan;
      }

    
}
