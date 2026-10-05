<?php

namespace Tests\Unit;

use App\Http\Middleware\CanonicalRedirect;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CanonicalCampaignTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['app.url' => 'https://aldeftech.com']);
        $this->app->detectEnvironment(fn () => 'production');
    }

    public function test_campaign_query_order_does_not_redirect_an_existing_canonical_url(): void
    {
        foreach (['/', '/contact', '/en/contact', '/blog'] as $path) {
            $request = Request::create('https://aldeftech.com'.$path.'?utm_source=linkedin&utm_medium=social&utm_campaign=launch%20week');
            $next = new Response('ok');

            $response = (new CanonicalRedirect)->handle($request, fn () => $next);

            $this->assertSame($next, $response);
        }
    }

    public function test_existing_host_and_slash_redirects_keep_the_raw_campaign_query(): void
    {
        $query = '?utm_source=linkedin&utm_medium=social&utm_campaign=launch%20week';

        foreach (['http://aldeftech.com/contact', 'https://www.aldeftech.com/contact', 'https://aldeftech.com/contact/'] as $url) {
            $request = Request::create($url.$query);
            $response = (new CanonicalRedirect)->handle($request, fn () => new Response('ok'));

            $this->assertSame(301, $response->getStatusCode());
            $this->assertSame('https://aldeftech.com/contact'.$query, $response->headers->get('Location'));
        }
    }
}
