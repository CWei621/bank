<?php

namespace App\Repositories;

use App\Models\Detail;

class DetailRepository
{
    protected $model;

    public function __construct(Detail $model)
    {
        $this->model = $model;
    }

    /**
     * add record
     *
     * @param int $userId user id
     * @param int $amount balance to be add
     * @param int $currentBalance before balance
     * @param int $newBalance after balance
     */
    public function create($userId, $amount, $currentBalance, $newBalance)
    {
        return $this->model->query()->create([
            'before_balance' => $currentBalance,
            'amount' => $amount,
            'balance' => $newBalance,
            'user_id' => $userId,
        ]);
    }

    public function getDetail(int $userId)
    {
        return $this->model->query()->where('user_id', $userId)->orderBy('id', 'desc')->paginate(10)->toArray();
    }
}
