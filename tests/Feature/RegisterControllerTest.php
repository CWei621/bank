<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    /**
     * register test
     */
    public function testRegister()
    {
        $response = $this->post('register', [ 
            '_token' => 'uHnWvEQI4jUTX8YMb6UxCWDhxRJmSWSCuu08OIVq',
            'name'=> 'test2',
            'email' => 'test2@test2.com',
            'password' => '123qweasdZXC',
            'password_confirmation' => '123qweasdZXC',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * Google redirector login page test
     *
     * @return void
     */
    public function testGoogleRedirect()
    {
        $response = $this->get("google/auth");
        $this->assertEquals($response->getStatusCode(), 302);
        $response->assertRedirectContains('https://accounts.google.com/o/oauth2/auth');
    }

    /**
     * Google register test
     *
     * @return void
     */
    public function testGoogleRegister()
    {
        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser
            ->shouldReceive('getId')
            ->andReturn(rand())
            ->shouldReceive('getName')
            ->andReturn('test user')
            ->shouldReceive('getEmail')
            ->andReturn('test.user' . '@gmail.com');
    
        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider
            ->shouldReceive('user')
            ->andReturn($abstractUser);
    
        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($provider);

        $response = $this->get("google/auth/callback");
        $this->assertEquals($response->getStatusCode(), 302);
        $response->assertRedirectContains('/bank');
    }

    /**
     * Google login test
     *
     * @return void
     */
    public function testGoogleLogin()
    {
        $user = User::factory()->count(1)->create();
        $user = \App\Models\User::find(1);

        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser
            ->shouldReceive('getId')
            ->andReturn(rand())
            ->shouldReceive('getName')
            ->andReturn($user->name)
            ->shouldReceive('getEmail')
            ->andReturn($user->email);
    
        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider
            ->shouldReceive('user')
            ->andReturn($abstractUser);
    
        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($provider);

        $response = $this->get("google/auth/callback");
        $this->assertEquals($response->getStatusCode(), 302);
        $response->assertRedirectContains('/bank');

    }
}
