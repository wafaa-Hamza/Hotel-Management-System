<?php

namespace App\Rules;

use Closure;
use App\Models\Room;
use Illuminate\Contracts\Validation\ValidationRule;

class RoomNameCountRule implements ValidationRule
{
   protected $FromFloor_id;
   public function __construct($FromFloor_id){
    $this->FromFloor_id = $FromFloor_id;
   }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate($attrpute,$value, Closure $fail): void
    {
            $stringCounts = Room::where('floor_id',$this->FromFloor_id)->select('rm_name_en')->get();
            foreach($stringCounts as $stringCount)
            {
                $charCount = strlen($stringCount->rm_name_en);
               // dump($value.' '.$charCount );
                if( $charCount < $value)
                {
                   $fail('the number of digit is bigger than exist digit for room Name ='.$stringCount->rm_name_en);
                }
            }
    
    }
}