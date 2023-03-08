<?php

namespace Ht7FfhsBsct\Common\Events;

abstract class Event
{
    public function toJson(): string
    {
        return json_encode($this);
    }
}
