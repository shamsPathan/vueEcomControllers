<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;

class SocialController extends Controller
{
    public function redirect($provider)
    {
      return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
  
        $info = Socialite::driver('facebook')->user();
  
      if ($info) {

        $user = CustomerInfo::where('provider_id', $info->id)->first();
  
        if ($user) {
          //log him in
        } else {
          $user = $this->createUser('App\Model\CustomerInfo', $info, $provider);
        }
        $this->login($user);
        return redirect('/');
      } else {
        return redirect('/customer');
      }
    }
}
