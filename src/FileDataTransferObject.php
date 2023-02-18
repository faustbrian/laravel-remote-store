<?php

declare(strict_types=1);

namespace PreemStudio\RemoteStore;

use Spatie\DataTransferObject\DataTransferObject;

final class FileDataTransferObject extends DataTransferObject
{
    public string $name;

    public array $data;

    public ?string $hash = null;
}
