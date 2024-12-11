<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\RemoteStore;

use Spatie\LaravelData\Data;

final class FileDataTransferObject extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly array $data,
        public readonly ?string $hash = null,
    ) {}
}
