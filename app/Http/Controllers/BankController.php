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
            return redirect('/login')->with('message', 'You have to loggin first');
        }
        
        $balance = 0;
        $balance = Balance::where('user_id', Auth::user()->id)->first()->balance;
        $username = User::where('id', Auth::user()->id)->first()->name;

        return view('home', compact('balance', 'username'));
    }

    public function create()
    {
        return view('addBalance');
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

    public function addBalance(Request $request)
    {
        $balance = $request->input('balance');
        $uid = $request->input('uid');

        $status = 0;
        $message = '';

        $currentBalance = Balance::where('user_id', $uid)->first()->balance;
        $newBalance = $currentBalance + $balance;

        if ($newBalance < 0) {
            $message = 'Account balance is not enought!';

            return redirect('/bank')->with(compact('message', 'status'));
        }

        Balance::where('user_id', $uid)
            ->update([
                'balance' => $newBalance,
            ]);

        $status = 1;
        $message = 'Account balance add successed!';

        return redirect('/bank')->with(compact('message', 'status'));
    }
}

