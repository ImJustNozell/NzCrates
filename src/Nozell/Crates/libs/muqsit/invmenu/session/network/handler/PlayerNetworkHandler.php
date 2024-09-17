<?php

declare(strict_types=1);

namespace Nozell\Crates\libs\muqsit\invmenu\session\network\handler;

use Closure;
use Nozell\Crates\libs\muqsit\invmenu\session\network\NetworkStackLatencyEntry;

interface PlayerNetworkHandler{

	public function createNetworkStackLatencyEntry(Closure $then) : NetworkStackLatencyEntry;
}