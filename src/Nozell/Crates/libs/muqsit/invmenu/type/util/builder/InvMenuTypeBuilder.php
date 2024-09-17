<?php

declare(strict_types=1);

namespace Nozell\Crates\libs\muqsit\invmenu\type\util\builder;

use Nozell\Crates\libs\muqsit\invmenu\type\InvMenuType;

interface InvMenuTypeBuilder{

	public function build() : InvMenuType;
}