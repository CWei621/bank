<?php

namespace App\Services;

use App\Repositories\BalanceRepository;
use App\Repositories\DetailRepository;

class BalanceService
{
    protected $balanceRepository;
    protected $detailRepository;

    public function __construct(
        BalanceRepository $balanceRepository,
        DetailRepository $detailRepository
    ) {
        $this->balanceRepository = $balanceRepository;
        $this->detailRepository = $detailRepository;
    }

    public function create($userId)
    {
        return $this->balanceRepository->create($userId);
    }

    public function addBalance($userId, $balance)
    {
        $currentBalance = $this->balanceRepository->getBalanceByUserid($userId);

        if ($currentBalance === null) {
            return null ;
        }

        $amount = Intval($balance);

        $newBalance = $this->balanceCheck($currentBalance, $amount);

        if (!$newBalance) {
            return [
                'result' => 'error',
                'msg' => 'Account balance is not enough!',
                'code' => 20210600002,
                'status' => 0,
            ];
        }

        $balanceResult = $this->balanceRepository->updateBalance($userId, $newBalance);
        $detailResult = $this->detailRepository->create($userId, $amount, $currentBalance, $newBalance);

        return [
            'result' => 'ok',
            'msg' => 'Account balance add successed!',
            'code' => 0,
            'status' => 1,
        ];
    }

    public function getBalanceByUserId(int $userId) {
        return $this->balanceRepository->getBalanceByUserId($userId);
    }

    private function balanceCheck(Int $currentBalance, Int $amount)
    {
        $newBalance = $currentBalance + intval($amount);

        if ($newBalance < 0) {
            return null;
        }

        return $newBalance;
    }
}
