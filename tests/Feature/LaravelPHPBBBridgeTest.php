<?php

namespace Tests\Unit;

use App\User;
use http\Env\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Tests\TestCase;

class LaravelPHPBBBridgeTest extends TestCase
{
    use RefreshDatabase;

    public function testPost()
    {
        $user = factory(User::class)->create();

        $response = $this->json('POST', '/auth-bridge/login', [
            'appkey' => 'yoursecretapikey',
            'username' => $user->username,
            'password' => 'password',
        ]);

        $want = [
            'id' => $user->id,
            'username'=> $user->username,
            'email' => $user->email,
        ];

        $response->assertJson([
            'code' => '200',
            'msg' => 'success',
            'data' => $want,
        ]);
    }

    public function testPost_InvalidPassword()
    {
        $user = factory(User::class)->create();

        $response = $this->json('POST', '/auth-bridge/login', [
            'appkey' => 'yoursecretapikey',
            'username' => $user->username,
            'password' => 'password2',
        ]);

        $response->assertJson([
            'code' => '400',
            'msg' => 'Invalid username or password',
            'data'=> [],
        ]);
    }

    public function testPost_InvalidAppKey()
    {
        $user = factory(User::class)->create();

        $response = $this->json('POST', '/auth-bridge/login', [
            'appkey' => 'badappkey',
            'username' => $user->username,
            'password' => 'password',
        ]);

        $response->assertJson([
            'code' => '400',
            'msg' => 'Invalid API Key',
            'data'=> [],
        ]);
    }

    public function testGet()
    {
        $user = factory(User::class)->create();
        Auth::login($user);

        $want = [
            'username'=> $user->username,
        ];

        $response = $this->json('GET', '/auth-bridge/login');

        $response->assertJson([
            'code' => '200',
            'data' => $want,
        ]);
    }

    public function testDelete()
    {
        $user = factory(User::class)->create();
        Auth::login($user);
        $this->assertNotNull(Auth::user());
        $response = $this->delete('/auth-bridge/login');

        $response->assertStatus(200);
        $this->assertNull(Auth::user());
    }
}
