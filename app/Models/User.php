<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Session;
use Kyslik\ColumnSortable\Sortable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, sortable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

      protected $table ='users';
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
      
        'company',
       
        'role_id',
        'phone_no',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $sortable = [
         'name',
        'last_name',
        'email',
        'password',
      
        'company',
       
        'role_id',
        'phone_no',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function referedBy(){
        return $this->belongsTo(User::class,'reference_id');
    }


    //-----Authenticating if request is comming from the frontend modified form
    public static function isAuthenticatedRequest($user_id, $level){
        try {
            $session_user = Session::get('user_id');
            if($level == 1){
                if ($session_user == $user_id) {
                    return 1;
                }
            }elseif ($level == 2) {
                $user = User::find($user_id);
                $parent = User::find($user->reference_id);
                if ($session_user == $parent->reference_id) {
                    return 1;
                }
            }elseif ($level == 3) {
                $user = User::find($user_id);
                $parent = User::find($user->reference_id);
                $grandParent = User::find($parent->reference_id);
                if ($session_user == $grandParent->reference_id) {
                    return 1;
                }
            }else{
                return 0;
            }
        } catch (\Exception $e) {
            return 0;
        }
    }
}
