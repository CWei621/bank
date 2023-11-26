<?php
namespace Tests\Services;

use App\Services\DetailService;
use App\Repositories\DetailRepository;
use App\Repositories\BalanceRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Balance;

class DetailServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $detailService;
    protected $balanceRepository;
    protected $detailRepository;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->balanceRepository = $this->app->make(BalanceRepository::class);
        $this->detailRepository = $this->app->make(DetailRepository::class);
        $this->detailService = new DetailService($this->detailRepository);

        $this->user = User::factory()->count(1)->create()->first();
    }

    /** @test */
    public function testCreateDetail()
    {
        $balance = Balance::factory(Balance::class)->create(['user_id' => $this->user->id])->balance;
        $amountToAdd = 10;
        $newBalance = $balance + $amountToAdd;

        $this->detailService->create($this->user->id, $amountToAdd, $balance, $newBalance);
        $detail = $this->detailService->getDetail($this->user->id)['data'][0];

        $this->assertEquals($detail['before_balance'], $balance);
        $this->assertEquals($detail['amount'], $amountToAdd);
        $this->assertEquals($detail['balance'], $balance + $amountToAdd);
        $this->assertEquals($detail['user_id'], $this->user->id);
    }
}
