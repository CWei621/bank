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

        $userId = Auth::user()->id;
        $request->merge([
            'user_id' => $userId,
        ]);

        $balance = $this->getAccount($request)->getData()->balance;
        $username = User::where('id', $userId)->first()->name;

        return view('home', compact('balance', 'username'));
    }

    public function create()
    {
        return view('addBalance');
    }

    public function detail(Request $request)
    {
        $request->merge([
            'user_id' => Auth::user()->id,
        ]);
    
        $response = $this->getDetail($request)->getData();
        $details = $response->data;
        $links = $response->links;

        return view('detail', compact('details', 'links'));
    }

    public function getAccount(Request $request)
    {
        $userId = $request->query('user_id');
        
        if (!isset($userId)) {
            return response()->json([
                'result' => 'error',
                'msg' => 'Invalid user',
            ]);
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
            if ($balance != 0) {
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
            }
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
        $userId = $request->query('user_id');

        return response()
            ->json(Detail::where('user_id', $userId)->orderBy('id', 'desc')->paginate(5)->toArray());
    }
}

