<?php
 
namespace Exceedone\Exment\Providers;
 
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Exceedone\Exment\Model\Define;
use Exceedone\Exment\Model\LoginUser;
 
class CustomUserProvider extends \Illuminate\Auth\EloquentUserProvider
{
    /**
     * Create a new database user provider.
     *
     * @param  \Illuminate\Contracts\Hashing\Hasher  $hasher
     * @param  string  $model
     * @return void
     */
    public function __construct($hasher, $model)
    {
        $this->model = $model;
        $this->hasher = $hasher;
    }

    public function retrieveById($identifier)
    {
        //return \Encore\Admin\Auth\Database\Administrator::find($identifier);
        return LoginUser::find($identifier);
    }
 
    public function retrieveByToken($identifier, $token)
    {
 
    }
 
    public function updateRememberToken(Authenticatable $user, $token)
    {
 
    }
 
    public function retrieveByCredentials(array $credentials)
    {
        $login_user = null;
        foreach(['email', 'user_code'] as $key){
            $login_user = LoginUser
            ::whereHas('base_user', function($query) use ($key, $credentials){
                $query->where(getColumnNameByTable(Define::SYSTEM_TABLE_NAME_USER, $key), array_get($credentials, 'username'));
            })->first();

            if(isset($login_user)){break;}
        }
        
        if(isset($login_user)){
            return $login_user;
        }
        return null;
    }
 
    public function validateCredentials(Authenticatable $login_user, array $credentials)
    {
        if(is_null($login_user)){return false;}
        $password = $login_user->password;
        $credential_password = array_get($credentials, 'password');
        // Verify the user with the username password in $ credentials, return `true` or `false`
        return !is_null($credential_password) && Hash::check($credential_password, $password);
    }
}