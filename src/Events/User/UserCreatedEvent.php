<?php

namespace Ht7FfhsBsct\Common\Events\User;

use Ht7FfhsBsct\Common\DataTransferObjects\User\UserData;
use Ht7FfhsBsct\Common\Enums\Events;
use Ht7FfhsBsct\Common\Events\Event;

class UserCreatedEvent extends Event
{
    public string $type = Events::USER_CREATED;

    public function __construct(public readonly UserData $data)
    {

    }
}
