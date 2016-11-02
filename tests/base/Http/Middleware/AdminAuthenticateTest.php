<?php

use Mockery as m;

use Ohio\Core\Base\Testing\OhioTestCase;
use Ohio\Core\Base\Http\Middleware\AdminAuthenticate;
use Ohio\Core\User\User;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;

class AdminAuthenticateTest extends OhioTestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * @covers \Ohio\Core\Base\Http\Middleware\AdminAuthenticate::__construct()
     * @covers \Ohio\Core\Base\Http\Middleware\AdminAuthenticate::handle()
     */
    public function test()
    {

        $next = function ($request) {
            if ($request instanceof Request) {
                return true;
            }
            return false;
        };

        # reject guest via ajax
        $guestGuard = m::mock(Guard::class);
        $guestGuard->shouldReceive('guest')->andReturn(true);
        $ajaxRequest = m::mock(Request::class);
        $ajaxRequest->shouldReceive('ajax')->andReturn(true);
        $middleware = new AdminAuthenticate($guestGuard);
        $response = $middleware->handle($ajaxRequest, $next);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals($response->getStatusCode(), 401);

        # redirect non-ajax guest to login
        $nonAjaxRequest = m::mock(Request::class);
        $nonAjaxRequest->shouldReceive('ajax')->andReturn(false);
        $nonAjaxRequest->shouldReceive('fullUrl')->andReturn('/protected-url');
        $response = $middleware->handle($nonAjaxRequest, $next);
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertTrue(str_contains($response->getTargetUrl(), '/login'));
        $this->assertEquals($response->getSession()->get('_redirect'), '/protected-url');
        $this->assertNotEmpty($response->getSession()->get('warning'));

        # authenticated super user
        $superUser = m::mock(User::class);
        $superUser->shouldReceive('hasRole')->andReturn(true);
        $superGuard = m::mock(Guard::class);
        $superGuard->shouldReceive('guest')->andReturn(false);
        $superGuard->shouldReceive('user')->andReturn($superUser);
        $middleware = new AdminAuthenticate($superGuard);
        $response = $middleware->handle($nonAjaxRequest, $next);
        $this->assertTrue($response);

        # authenticated super user
        $lameUser = m::mock(User::class);
        $lameUser->shouldReceive('hasRole')->andReturn(false);
        $lameGuard = m::mock(Guard::class);
        $lameGuard->shouldReceive('guest')->andReturn(false);
        $lameGuard->shouldReceive('user')->andReturn($lameUser);
        $lameGuard->shouldReceive('logout');
        $middleware = new AdminAuthenticate($lameGuard);
        $response = $middleware->handle($nonAjaxRequest, $next);
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertNotEmpty($response->getSession()->get('warning'));
    }

}