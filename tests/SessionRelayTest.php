<?php declare(strict_types=1);

namespace Tests;

use MAS\Toaster\SessionRelay;

final class SessionRelayTest extends TestCase
{
    use CollectorFactoryMethods;
    use ToastFactoryMethods;

    /** @test */
    public function it_relays_toasts_to_the_session_if_available(): void
    {
        $collector = $this->aCollector();
        $session = $this->app['session']->driver('null');
        $relay = new SessionRelay($session, $collector);

        $relay->handle();

        $this->assertFalse($session->exists(SessionRelay::NAME));

        $collector->collect($this->aToast());
        $collector->collect($this->aToast());

        $relay->handle();

        $this->assertTrue($session->exists(SessionRelay::NAME));
        $this->assertCount(2, $toasts = $session->get(SessionRelay::NAME));
        $this->assertEmpty($collector->release());
        $this->assertIsArray($toast = $toasts[0]);
        $this->assertArrayHasKey('duration', $toast);
        $this->assertArrayHasKey('message', $toast);
        $this->assertArrayHasKey('type', $toast);
    }
}
