<?php
namespace Tests\Services;

use App\Services\BalanceService;
use App\Repositories\BalanceRepository;
use App\Repositories\DetailRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Balance;

class BalanceServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $balanceService;
    protected $balanceRepository;
    protected $detailRepository;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->balanceRepository = $this->app->make(BalanceRepository::class);
        $this->detailRepository = $this->app->make(DetailRepository::class);

        $this->balanceService = new BalanceService(
            $this->balanceRepository,
            $this->detailRepository
        );

        $this->user = User::factory()->count(1)->create()->first();
    }

    /** @test */
    public function testAddBalanceSuccess()
    {
        $amountToAdd = 50;
        $userId = $this->user->id;
        $this->balanceRepository->create($userId);
        $result = $this->balanceService->addBalance($userId, $amountToAdd);

        // Assert that the result is as expected
        $this->assertEquals([
            'result' => 'ok',
            'msg' => 'Account balance add successed!',
            'code' => 0,
            'status' => 1,
        ], $result);
    }

    // Add more test cases for other scenarios, such as insufficient balance, etc.

    public function testGetBalanceByUserId()
    {
        $amountToAdd = 100;

        $userId = $this->user->id;
        $this->balanceRepository->create($userId);
        $this->balanceService->addBalance($userId, $amountToAdd);
        $this->assertEquals(100, $this->balanceService->getBalanceByUserId($userId));
    }

    public function testBalanceCheck()
    {
        $amountToAdd = -100;

        $userId = $this->user->id;
        $this->balanceRepository->create($userId);
        $this->balanceService->addBalance($userId, $amountToAdd);
        $this->assertEquals(0, $this->balanceService->getBalanceByUserId($userId));
    }
}
