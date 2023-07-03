<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    public function testLogin()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }
}