<?php declare(strict_types=1);

namespace MAS\Toaster;

interface Collector
{
    public function collect(Toast $toast): void;

    /** @internal */
    public function release(): array;
}
