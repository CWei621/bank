<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Balance;
use App\Models\User;

class BankController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user() == null) {
            return redirect('/')->with('message', 'Your post has been updated!');
        }
        
        $balance = 0;
        $balance = Balance::where('user_id', Auth::user()->id)->first()->balance;
        $username = User::where('id', Auth::user()->id)->first()->name;

        return view('home', compact('balance', 'username'));
    }

    public function getAccount(Request $request)
    {
        $userId = $request->query('user_id');
        
        if (!isset($userId)) {
            throw new Exception('invalid user id');
        }

        $balance = Balance::where('user_id', $userId)->first()->balance;

        return response()
            ->json([
                'user_id' => $userId,
                'balance' => $balance,
            ]);
    }
}
