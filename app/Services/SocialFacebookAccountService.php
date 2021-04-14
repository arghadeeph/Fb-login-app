<?php
namespace App\Services;

use App\SocialFacebookAccount;
use App\User;
use Laravel\Socialite\Contracts\User as ProviderUser;

class SocialFacebookAccountService
{
    public function createOrGetUser(ProviderUser $providerUser)
    {

        $account = SocialFacebookAccount::whereProvider('facebook')
            ->whereProviderUserId($providerUser->getId())
            ->first();
        if ($account) {
            return $account->user;
        } else {
            $account = new SocialFacebookAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider' => 'facebook'
            ]);
            $user = User::whereEmail($providerUser->getEmail())->first();

           
            $name_array=explode(' ', $providerUser->getName()) ;

            if (!$user) {
                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'first_name' => $name_array[0],
                    'last_name' => $name_array[1],
                    'password' => bcrypt('password'),
                ]);
            }
            $account->user()->associate($user);
            $account->save();
            return $user;
        }
    }
}