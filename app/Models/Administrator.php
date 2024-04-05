<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use App\Models\User;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Administrator extends Authenticatable
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'parent_id','referrer_id', 'name', 'mobile', 'email', 'password', 'username' ,'referral_token','usercode','registered_ip','user_position','min_lot_wise_brokerage', 'min_lot_wise_brokerage_is_percentage', 'min_amount_wise_brokerage','min_amount_wise_brokerage_is_percentage', 'max_intraday_multiplication', 'max_delivery_multiplication','user_remark','order_outside_of_high_low', 'apply_auto_square', 'intra_day_auto_square', 'only_position_squareoff', 'mtm_linked_with_ledger', 'user_broker_id', 'loss_alert_percentage', 'close_alert_margin', 'user_account_type_id', 'margin_limit','master_account_type_ids','partnership','master_creation_limit','last_login_at','last_login_ip'
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'master_account_type_ids' => 'array'
    ];
    protected $hidden = ['password','usercode','registered_ip','mobile','email','roles','remaining_master_limit']; 
    protected $appends = ['user_delivery_multiplication','user_intraday_multiplication','user_parent','user_broker','formatted_market_data','remaining_master_limit','is_last_master'];   
 
    # Accessor for getting the parent name
    public function getUserParentAttribute()
    {
        # Use the 'parent_id' to find the parent user
        $parent = $this->parent()->first();
        # Return the parent name if found, otherwise return null
        return $parent ? $parent : null;
    }

    # Get User Intraday Multiplication from parent(master) 
    protected function getUserIntradayMultiplicationAttribute() {
        $intraday_multi = $this->parent()->value('max_intraday_multiplication');        
        return $intraday_multi<=0 ? 1 : $intraday_multi;
    }

    # Get User Delivery Multiplication from parent(master) 
    protected function getUserDeliveryMultiplicationAttribute() {
        $intraday_multi = $this->parent()->value('max_delivery_multiplication');        
        return $intraday_multi<=0 ? 1 : $intraday_multi;
    }

    # Get User user is last master from parent(master)
     protected function getIsLastMasterAttribute() {
        return $this->isLastMaster();
    }

    #function for get allowed market ids 
    public function AllowedMarketIds($user_id=0,$parentMarket=0)
    {
        $user_id = $user_id!=0?$user_id: $this->id;
        $userData = Administrator::find($user_id);    
        
        $userData = $parentMarket!=0 && !$userData->hasRole('admin') ? $userData->user_parent : $userData;  
        $getMasketIds = Nex_Market::select('id as market_id')->where('market_status','active');

        if($userData->hasRole('master'))
        {
            $getMasketIds = Nex_master_market_detail::select('market_id')->where('user_id',$userData->id)->groupBy('market_id');
            if($userData->is_last_master)
                $getMasketIds->whereIn('market_id',$this->AllowedMarketIds($userData->parent_id));
        }
        elseif($userData->hasRole('user'))
        {
            $getMasketIds = Nex_user_market_detail::select('market_id')->where('user_id',$userData->id)->groupBy('market_id');
            $getMasketIds->whereIn('market_id',$this->AllowedMarketIds($userData->parent_id));
        }
        
        return  $getMasketIds->get()->pluck('market_id')->toArray();
    }
    #--------------------------------------------    
    #-----------------------------------------------------
    // function for return setting content
    public function get_levels($id = 0){

        $get_levels = Nex_Level::select('*');

        if($this->UserParent && !$this->UserParent->hasRole(['admin']))
            $get_levels->whereIn('id',$this->UserParent->master_account_type_ids);
        
        if($id>0)
            return $get_levels->where('id',$id)->first();
        
        return $get_levels->get();
    } 


    # Accessor for getting the parent name
    public function getUserBrokerAttribute()
    {
        # Use the 'parent_id' to find the parent user
        $broker = $this->userBroker()->first();
        # Return the parent name if found, otherwise return null
        return $broker ? $broker : null;
    }

    # Accessor to get the Remaining Master Limit
    public function getRemainingMasterLimitAttribute()
    {
      $TotalMasterCreated = $this->hasMany(Administrator::class, 'parent_id', 'id')->role('master')
      ->where('parent_id', $this->id)
      ->count();  
        return  ($this->master_creation_limit - $TotalMasterCreated) ;
    }

     // Accessor to get the total number of child users
    //  public function getChildCountAttribute()
    //  {
    //      return $this->children()->count();
    //  }

     // Accessor to get the total number of child users based on a dynamic condition
     public function getChildCount($role = null)
     {
         $query = $this->children();
 
         if ($role !== null) {
             // Adjust the condition based on the provided parameter
            return $query->role($role)->count();
         }
 
         return $query->role('master')->count();
     }

     // Relationship to get child users with a dynamic limit
     public function children($role = null,$limit = null)
     {
         $query = $this->hasMany(Administrator::class, 'parent_id', 'id');

         if ($limit !== null) {
             $query->limit($limit);
         }

         if ($limit !== null) {
            $query->role($role);
        }
        
        return $query;
     }
    
    /** 
        * Get the user's referral link.
     *
     * @return string
     */
    public function getReferralLinkAttribute()
    {
        return $this->referral_link = route('showuserregister', ['ref' => "NXCREF".numberToCharacterString($this->id)]);
    }

    /**
     * A user has a referrer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function referrer()
    {
        return $this->belongsTo(Administrator::class, 'referrer_id', 'id');
    }

    /**
     * A user has many referrals.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function referrals()
    {
        return $this->hasMany(Administrator::class, 'referrer_id', 'id');
    }

    public function parent() 
    {
        return $this->belongsTo(Administrator::class, 'parent_id');
    }

    public function userBroker() 
    {
        return $this->belongsTo(Administrator::class, 'user_broker_id');
    }

    public function submasters() 
    {
        return $this->hasMany(Administrator::class, 'parent_id')->role('master');
    }

    public function users() 
    {
        return $this->hasMany(Administrator::class, 'parent_id')->role('user');
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isMaster()
    {
        return $this->hasRole('master');
    }

    public function isBroker()
    {
        return $this->hasRole('broker');
    }

    public function isLastMaster()
    {
        $isLastMaster = Administrator::role('admin')
                            ->whereIn('id',[$this->parent_id,$this->id])
                            ->exists();
        return !$isLastMaster;
    }

    public function MasterMarketDetails()
    {
        return $this->hasMany(Nex_master_market_detail::class, 'user_id');
    }
    
    public function UserMarketDetails()
    {
        return $this->hasMany(Nex_user_market_detail::class, 'user_id');
    }

    public function getformattedMarketDataAttribute()
    {
        $formattedData = [];

        $markertDetails = $this->hasRole('user')? $this->UserMarketDetails:$this->MasterMarketDetails;
        
        foreach ($markertDetails as $data) {

            if(!in_array($data->market_id, $this->AllowedMarketIds()))
                continue;

            $marketId = $data->market_id;
            $marketField = $data->market_field;
            $marketName = $data->market_name;
            $marketFieldAmount = $data->market_field_amount;
            $amountIsPercentage = $data->amount_is_percentage;
            $script_id = !empty($data->script_id)?$data->script_id:0;

            $formattedData[$marketId]['market_name'] = $marketName;
            $formattedData[$marketId][$marketField] = [
                'market_field' => $marketField,
                'market_field_amount' => $marketFieldAmount,
                'amount_is_percentage' => $amountIsPercentage
            ];
            if($script_id>0)
            {
                $formattedData[$marketId]['script_data'][$script_id][$marketField] =                 
                [
                    'market_field' => $marketField,
                    'market_field_amount' => $marketFieldAmount,
                    'amount_is_percentage' => $amountIsPercentage
            
                ];
            }
        }       

        return $formattedData;
    }

    #function for get user max amount limit
    public function userMaxLimit($Arr=['user_id'=>0,'market_id'=>0,'market_name'=>0,'isParentData'=>0])
    {
        $user_id = !empty($Arr['user_id'])?$Arr['user_id']:$this->id;
        $market_id = !empty($Arr['market_id'])?$Arr['market_id']:0;
        $market_name = !empty($Arr['market_name'])?$Arr['market_name']:'';
        $isParentData = !empty($Arr['isParentData'])?$Arr['isParentData']:0;

        $userData = Administrator::find($user_id);     
        $userData = $isParentData!=0 && !$userData->hasRole('admin') ? $userData->user_parent : $userData;
        $markertDetails = $userData->hasRole('user')? $userData->UserMarketDetails:$userData->MasterMarketDetails;
        
        $getMasket = Nex_Market::select('id as market_id','market_user_required_fields','user_required_fields')->where('market_status','active')
        ->where(function($query) use ($market_id, $market_name) {
            $query->where('id', $market_id)
                  ->orWhere('market_name', $market_name);
        })->first();
        
        if(!$getMasket->exists() || !in_array($getMasket->market_id, $userData->AllowedMarketIds())) 
            return [];

        $getMarket_detail = collect();

        if($userData->hasRole('master'))
            $getMarket_detail = Nex_master_market_detail::select('*')->where('user_id',$userData->id)->where('market_id',$getMasket->market_id)->whereIn('market_field',$getMasket->market_user_required_fields)->get();
        elseif($userData->hasRole('user'))
            $getMarket_detail = Nex_user_market_detail::select('*')->where('user_id',$userData->id)->where('market_id',$getMasket->market_id)->whereIn('market_field',$getMasket->user_required_fields)->get();
       
        if($getMarket_detail->isEmpty())
            return [];

        $marketDetails = [];
        foreach ($getMarket_detail->toArray() as $detail) {
            
            $marketDetails[$detail['market_field']] = $detail;

            if(in_array($detail['market_field'] , ['amount_wise_brokerage','lot_wise_brokerage']))
            {
                $field = $detail['market_field'] == 'amount_wise_brokerage'? 'amount_wise_delivery_commission' : 'lot_wise_delivery_commission';
                $marketDetails[$field] = $detail;
                $field = $detail['market_field'] == 'amount_wise_brokerage'? 'amount_wise_intraday_commission' : 'lot_wise_intraday_commission';
                $marketDetails[$field] = $detail;
            }
        }
        return  $marketDetails;
    }
     #--------------------------------------------    

    // public function siblings() {
    //     return $this->hasMany(Administrator::class, 'parent_id', 'id')
    //                 ->where('id', '!=', $this->id);
    // }

    // public function getVariantsAttribute() {
    //     return $this->siblings->reject(function($elem) {
    //        return $elem->id == $this->id;
    //     });
    // }

    public function getVariantsAttribute($role) {
        $siblings = $this->getAllDescendants($this->id,$role)->reject(function($elem) {
           return $elem->id == $this->id;
        });
    
        return $siblings->map(function($sibling) {
            return $sibling->append('children');
        });
    }
    
    protected function getAllDescendants($parentId = 0 ,$role = 'master') {

        $parentId = $parentId==0 ? $this->id : $parentId; 

        $descendants = $this->hasMany(Administrator::class, 'parent_id', 'id')
                            ->role($role)
                            ->where('id', '!=', $this->id)
                            ->where('parent_id', $parentId)
                            ->get();
    
        foreach ($descendants as $descendant) {
            $descendant->children = $this->getAllDescendants($descendant->id,$role);
        }
    
        return $descendants;
    }
    
}
