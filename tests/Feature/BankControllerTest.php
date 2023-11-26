<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Service;
use Mockery;
use Mockery\MockInterface;
use App\Models\User;
use App\Models\Balance;
use App\Models\Detail;
use Tests\TestCase;

class BankControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testHomePageWithoutLogin()
    {
        $response = $this->get('/bank');

        $response->assertStatus(302);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLoginWithFakeUser()
    {
        $user = User::factory()->count(1)->create();
        $uid = $user[0]->id;
        Balance::factory()->count(1)->create(['user_id' => $uid]);

        $response = $this->actingAs(User::find($uid))
            ->withSession(['id' => $uid])
            ->get('/bank');

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAddBalancePage()
    {
        $user = User::factory()->count(1)->create();
        $uid = $user[0]->id;
        Balance::factory()->count(1)->create(['user_id' => $uid]);

        $response = $this->actingAs(User::find($uid))
            ->withSession(['id' => $uid])
            ->get('/bank/balance');

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDetailPage()
    {
        $user = User::factory()->count(1)->create();
        $uid = $user[0]->id;
        Balance::factory()->count(1)->create(['user_id' => $uid]);

        $response = $this->actingAs(User::find($uid))
            ->withSession(['id' => $uid])
            ->get('/bank/detail');

        $response->assertStatus(200);
    }

    public function testAddBalanceWithLogin()
    {
        $user = User::factory()->count(1)->create();
        $uid = $user[0]->id;
        Balance::factory()->count(1)->create(['user_id' => $uid]);

        $response = $this->actingAs(User::find($uid))
            ->withSession(['id' => $uid])
            ->call('POST', 'bank/balance', ['user_id' => $uid, 'balance' => 1]);

        $response->assertStatus(302);
    }

    public function testAddBalanceWithoutEnoughBalanceLogin()
    {
        $user = User::factory()->count(1)->create();
        $uid = $user[0]->id;
        Balance::factory()->count(1)->create(['user_id' => $uid]);

        $response = $this->actingAs(User::find($uid))
            ->withSession(['id' => $uid])
            ->call('POST', 'bank/balance', ['user_id' => $uid, 'balance' => -100]);

        $response->assertStatus(302);
    }

    /**
     * test mocked add balance API with failed to get detail
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testMockAddBalanceWithFailedDetail()
    {
        $user = User::factory()->count(1)->create();
        $uid = $user[0]->id;
        Balance::factory()->count(1)->create(['user_id' => $uid]);

        $client_mock = \Mockery::mock('overload:App\Models\Detail');
        $client_mock->shouldReceive('where')->once()->andThrow(new \Exception('FakeException', 20210001));
        $this->instance('App\Models\Detail', $client_mock);

        $response = $this->call('POST', 'bank/balance', ['user_id' => $uid, 'balance' => 1]);

        $response->assertStatus(302);
    }
}
