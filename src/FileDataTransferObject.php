<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\RemoteStore;

use Spatie\DataTransferObject\DataTransferObject;

final class FileDataTransferObject extends DataTransferObject
{
    public string $name;

    public array $data;

    public ?string $hash = null;
}
