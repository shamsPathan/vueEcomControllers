<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Frontend\StudentUser;
use Illuminate\Http\Request;

class MobileAppController extends Controller
{
    public function __construct()
    {
        $this->valid = true;

        $this->middleware(function ($request, $next) {
            
            $request->validate([
                '_token' => 'required'
            ]);

            $user = $this->checkUser($request->all());
            
            if($user){
                if( ($user->api_token!=$request->_token)){
                    return redirect('/api/error');
                }
            } else {
                return redirect('/api/error');
            }

            return $next($request);
        });
    }

    private function checkUser(array $data)
    {
        return isset($data['email']) ? StudentUser::where('email', $data['email'])->first() :
            isset($data['phone']) ? StudentUser::where('phone', $data['phone'])->first() :
            null;
    }
}
