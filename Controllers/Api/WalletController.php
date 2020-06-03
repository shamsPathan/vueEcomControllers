<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Frontend\StudentUser;
use App\StudentWallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function studentWalletHistory(StudentUser $student)
    {
        return StudentWallet::where('author_id', $student->id)->with('transaction')->get();
    }
}
