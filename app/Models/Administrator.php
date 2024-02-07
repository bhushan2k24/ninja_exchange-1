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
    protected $appends = ['user_delivery_multiplication','user_intraday_multiplication','user_parent','user_broker','formatted_market_data','remaining_master_limit'];   
 
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
      $TotalMasterCreated = $this->hasMany(Administrator::class, 'parent_id', 'id')
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
             $query->role($role);
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
        return $this->hasMany(Administrator::class, 'admin_id')->role('user');
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

    public function marketDetails()
    {
        return $this->hasMany(Nex_user_market_detail::class, 'user_id');
    }

    public function getformattedMarketDataAttribute ()
    {
        $formattedData = [];
        foreach ($this->marketDetails as $data) {
            $marketId = $data->market_id;
            $marketField = $data->market_field;
            $marketName = $data->market_name;
            $marketFieldAmount = $data->market_field_amount;
            $amountIsPercentage = $data->amount_is_percentage;

            $formattedData[$marketId]['market_name'] = $marketName;
            $formattedData[$marketId][$marketField] = [
                'market_field' => $marketField,
                'market_field_amount' => $marketFieldAmount,
                'amount_is_percentage' => $amountIsPercentage,
            ];
        }

        return $formattedData;
    }

    // public function siblings() {
    //     return $this->hasMany(Administrator::class, 'parent_id', 'id')
    //                 ->where('id', '!=', $this->id);
    // }

    // public function getVariantsAttribute() {
    //     return $this->siblings->reject(function($elem) {
    //        return $elem->id == $this->id;
    //     });
    // }

    public function getVariantsAttribute() {
        $siblings = $this->getAllDescendants($this->id)->reject(function($elem) {
           return $elem->id == $this->id;
        });
    
        return $siblings->map(function($sibling) {
            return $sibling->append('children');
        });
    }
    
    protected function getAllDescendants($parentId) {
        $descendants = $this->hasMany(Administrator::class, 'parent_id', 'id')
                            ->where('id', '!=', $this->id)
                            ->where('parent_id', $parentId)
                            ->get();
    
        foreach ($descendants as $descendant) {
            $descendant->children = $this->getAllDescendants($descendant->id);
        }
    
        return $descendants;
    }
    
}
