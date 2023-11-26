<?php

namespace App\Repositories;

use App\Models\Balance;

class BalanceRepository
{
    protected $model;

    public function __construct(Balance $model)
    {
        $this->model = $model;
    }

    public function create($userId)
    {
        return $this->model->create(['user_id' => $userId, 'balance' => 0]);
    }

    public function updateBalance(int $id, int $newBalance)
    {
        $record = $this->model->query()->where('user_id', $id)->first();
        $newBalance = number_format($newBalance, 4, '.', '');

        if ($record) {
            $record->update(['balance' => number_format($newBalance, 4, '.', '')]);

            return $record;
        }

        return null;
    }

    public function getBalanceByUserid($id)
    {
        $balanceUser = $this->model->query()->where('user_id', $id)->first();

        if ($balanceUser) {
            return $balanceUser->balance;
        }

        return null;
    }
}
