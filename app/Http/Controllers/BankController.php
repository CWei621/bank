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

    public function create(Request $request)
    {
        if ($request->input('balance') == NUll && $request->input('user_id') == NULL) {
            return view('addBalance');
        }

        $response = $this->addBalance($request);
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
                'code' => 20210600001,
            ]);
        }

        try {
            $balance = Balance::where('user_id', $userId)->first()->balance;
        } catch (\Throwable $err) {
            return response()
                ->json([
                    'result' => 'error',
                    'msg' => $err->getMessage(),
                    'code' => $err->getCode(),
                ]);
        }


        return response()
            ->json([
                'user_id' => $userId,
                'balance' => $balance,
            ]);
    }

    public function addBalance(Request $request)
    {
        $balance = $request->input('balance');
        $userId = $request->input('user_id');

        $status = 0;
        $message = '';

        try {
            $currentBalance = Balance::where('user_id', $userId)->first()->balance;
        } catch (\Throwable $err) {
            $message = $err->getMessage();

            return redirect('/bank')->with(compact('message', 'status'));
        }

        $newBalance = $currentBalance + $balance;

        if ($newBalance < 0) {
            $message = 'Account balance is not enought!';

            if (Auth::check()) {
                return redirect('/bank')->with(compact('message', 'status'));
            }

            return response()
                ->json([
                    'result' => 'error',
                    'msg' => 'Account balance is not enough!',
                    'code' => 20210600002,
                    'status' => $status,
                ]);
        }

        try {
            if ($balance != 0) {
                Balance::where('user_id', $userId)
                    ->update([
                        'balance' => $newBalance,
                    ]);

                Detail::create([
                    'before_balance' => $currentBalance,
                    'amount' => $balance,
                    'balance' => $newBalance,
                    'user_id' => $userId,
                ]);
            }
        } catch (\Throwable $err) {
            $message = $err->getMessage();

            return redirect('/bank')->with(compact('message', 'status', 'err'));
        }

        $status = 1;
        $message = 'Account balance add successed!';

        if (Auth::check()) {
            return redirect('/bank')->with(compact('status', 'message'));
        }

        return response()
        ->json([
            'result' => 'ok',
            'status' => $status,
            'msg' => $message,
        ]);
    }

    public function getDetail(Request $request)
    {
        $userId = $request->query('user_id');
        $status = 0;

        try {
            $res = Detail::where('user_id', $userId)->orderBy('id', 'desc')->paginate(10)->toArray();
        } catch (\Exception $err) {
            return response()
                ->json([
                    'result' => 'error',
                    'msg' => $err->getMessage(),
                    'code' => $err->getCode(),
                    'status' => $status,
                ]);
        }

        return response()
            ->json($res);
    }
}

