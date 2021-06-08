<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Detail;
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

    public function detail(Request $request)
    {    
        $details = $this->getDetail($request);

        return view('detail', ['details' => $details]);
    }

    public function getAccount(Request $request)
    {
        $userId = $request->query('user_id');
        
        if (!isset($userId)) {
            throw new \Exception('invalid user id');
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

        try {
            Balance::where('user_id', $uid)
            ->update([
                'balance' => $newBalance,
            ]);
    
            Detail::create([
                'before_balance' => $currentBalance,
                'amount' => $balance,
                'balance' => $newBalance,
                'user_id' => $uid,
            ]);
        } catch (\Throwable $err) {
            $message = $err->getMessage();

            return redirect('/bank')->with(compact('message', 'status', 'err'));
        }

        $status = 1;
        $message = 'Account balance add successed!';

        return redirect('/bank')->with(compact('message', 'status'));
    }

    public function getDetail(Request $request)
    {
        return  Detail::where('user_id', Auth::user()->id)->get()->toArray();
    }
}
