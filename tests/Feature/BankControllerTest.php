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
    use WithoutMiddleware;



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
            ->get('/bank/create');

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

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetAccountBalanceWithLogin()
    {
        $user = User::factory()->count(1)->create();
        $uid = $user[0]->id;
        Balance::factory()->count(1)->create(['user_id' => $uid]);

        $response = $this->call('GET', "api/bank/account", ['user_id' => $uid]);
        $data = $response->getData();

        $response->assertStatus(200)
            ->assertJson([
                'user_id' => $uid,
                'balance' => 0.0000,
            ]);
    }

    public function testGetAccountBalanceWithoutUserId()
    {
        $response = $this->get('api/bank/account');

        $response->assertStatus(200)
            ->assertJson([
                'result' => 'error',
                'msg' => 'Invalid user',
                'code' => '20210600001',
            ]);
    }

    public function testAddBalanceWithLogin()
    {
        $user = User::factory()->count(1)->create();
        $uid = $user[0]->id;
        Balance::factory()->count(1)->create(['user_id' => $uid]);

        $response = $this->actingAs(User::find($uid))
            ->withSession(['id' => $uid])
            ->call('POST', 'bank/create', ['user_id' => $uid, 'balance' => 1]);

        $response->assertStatus(200);
    }

    public function testAddBalanceWithoutLogin()
    {
        $user = User::factory()->count(1)->create();
        $uid = $user[0]->id;
        Balance::factory()->count(1)->create(['user_id' => $uid]);

        $response = $this->call('POST', 'api/bank/add/balance', ['user_id' => $uid, 'balance' => 1]);

        $response->assertStatus(200)
            ->assertJson([
                'result' => 'ok',
                'msg' => 'Account balance add successed!',
                'status' => 1,
            ]);
    }

    public function testAddBalanceWithoutEnoughBalanceLogin()
    {
        $user = User::factory()->count(1)->create();
        $uid = $user[0]->id;
        Balance::factory()->count(1)->create(['user_id' => $uid]);

        $response = $this->actingAs(User::find($uid))
            ->withSession(['id' => $uid])
            ->call('POST', 'api/bank/add/balance', ['user_id' => $uid, 'balance' => -100]);

        $response->assertStatus(302);
    }

    public function testAddBalanceWithoutEnoughBalanceNotLogin()
    {
        $user = User::factory()->count(1)->create();
        $uid = $user[0]->id;
        Balance::factory()->count(1)->create(['user_id' => $uid]);

        $response = $this->call('POST', 'api/bank/add/balance', ['user_id' => $uid, 'balance' => -100]);

        $response->assertStatus(200)
            ->assertJson([
                'result' => 'error',
                'msg' => 'Account balance is not enough!',
                'status' => 0,
            ]);
    }

    public function testGetDetail()
    {
        $user = User::factory()->count(1)->create();
        $uid = $user[0]->id;
        Balance::factory()->count(1)->create(['user_id' => $uid]);

        $response = $this->call('GET', 'api/bank/detail');
        $response->assertStatus(200);
    }

    /**
     * test mocked get detail API
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testMockGetDetail()
    {
        $client_mock = \Mockery::mock('overload:App\Models\Detail');
        $client_mock->shouldReceive('where')->once()->andThrow(new \Exception('FakeException', 20210001));
        $this->instance('App\Models\Detail', $client_mock);

        $response = $this->call('GET', 'api/bank/detail');

        $response->assertStatus(200)
            ->assertJson([
                'msg' => 'FakeException',
                'code' => 20210001,
            ]);
    }

    /**
     * test mocked get account API
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testMockGetAccount()
    {
        $user = User::factory()->count(1)->create();
        $uid = $user[0]->id;

        $client_mock = \Mockery::mock('overload:App\Models\Balance');
        $client_mock->shouldReceive('where')->once()->andThrow(new \Exception('FakeException', 20210001));
        $this->instance('App\Models\Detail', $client_mock);

        $response = $this->call('GET', "api/bank/account", ['user_id' => $uid]);
        $response->assertStatus(200)
            ->assertJson([
                'msg' => 'FakeException',
                'code' => 20210001,
            ]);
    }

    /**
     * test mocked add balance API with failed to get balance
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testMockAddBalanceWithFailedBalance()
    {
        $user = User::factory()->count(1)->create();
        $uid = $user[0]->id;

        $client_mock = \Mockery::mock('overload:App\Models\Balance');
        $client_mock->shouldReceive('where')->once()->andThrow(new \Exception('FakeException', 20210001));
        $this->instance('App\Models\Detail', $client_mock);

        $response = $this->actingAs(User::find($uid))
            ->withSession(['id' => $uid])
            ->call('POST', 'bank/create', ['user_id' => $uid, 'balance' => 1]);

        $response->assertStatus(200);
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

        $response = $this->call('POST', 'api/bank/add/balance', ['user_id' => $uid, 'balance' => 1]);

        $response->assertStatus(302);
    }
}
