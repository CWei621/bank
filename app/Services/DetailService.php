<?php

namespace App\Services;

use App\Repositories\DetailRepository;

class DetailService
{
    protected $detailRepository;

    public function __construct(DetailRepository $detailRepository)
    {
        $this->detailRepository = $detailRepository;
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
        return $this->detailRepository->create(
            $userId,
            $amount,
            $currentBalance,
            $newBalance,
        );
    }

    public function getDetail($userId)
    {
        return $this->detailRepository->getDetail($userId);
    }
}
