<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Detail;
use App\Models\User;
use App\Services\BalanceService;
use App\Services\DetailService;

class BankController extends Controller
{
    protected $balanceService;
    protected $detailService;

    public function __construct(
        BalanceService $balanceService,
        DetailService $detailService
    ){
        $this->balanceService = $balanceService;
        $this->detailService = $detailService;
    }

    public function index(Request $request)
    {
        $userId = Auth::user()->id;
        $request->merge([
            'user_id' => $userId,
        ]);

        $balance = $this->balanceService->getBalanceByUserId($userId);
        $user = User::where('id', $userId)->first();
        $username = $user->name;
        $createdAt = $user->created_at;

        return view('home', compact('balance', 'username', 'createdAt'));
    }

    public function create()
    {
        return view('addBalance');
    }

    public function detail(Request $request)
    {
        $detailList = $this->detailService->getDetail(Auth::user()->id);
        $links = $detailList['links'];
        $details = $detailList['data'];
        $page = $request->get('page', 1);

        return view('detail', compact('details', 'links', 'page'));
    }

    public function addBalance(Request $request)
    {
        $balance = $request->input('balance', 0);
        $userId = $request->input('user_id', 0);
        $status = 0;
        $message = '';

        try {
            $result = $this->balanceService->addBalance($userId, $balance);
            $message = $result['msg'];
            $status = $result['status'];
        } catch (\Throwable $err) {
            $message = $err->getMessage();
        }

        return redirect('/bank')->with(compact('status', 'message'));
    }
}

