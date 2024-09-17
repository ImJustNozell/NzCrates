<?php

declare(strict_types=1);

namespace Nozell\Crates\libs\muqsit\invmenu\session;

use Nozell\Crates\libs\muqsit\invmenu\InvMenu;
use Nozell\Crates\libs\muqsit\invmenu\type\graphic\InvMenuGraphic;

final class InvMenuInfo{

	public function __construct(
		readonly public InvMenu $menu,
		readonly public InvMenuGraphic $graphic,
		readonly public ?string $graphic_name
	){}
}