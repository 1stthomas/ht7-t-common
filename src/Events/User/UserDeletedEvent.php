<?php

namespace Ht7FfhsBsct\Common\Events\User;

use Ht7FfhsBsct\Common\DataTransferObjects\User\UserData;
use Ht7FfhsBsct\Common\Enums\Events;
use Ht7FfhsBsct\Common\Events\Event;

class UserDeletedEvent extends Event
{
    public string $type;

//    public string $type = Events::USER_DELETED;

    public function __construct(public readonly UserData $data)
    {
        $this->type = Events::USER_DELETED->value;
    }
}
