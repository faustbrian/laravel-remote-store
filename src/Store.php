<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\RemoteStore;

interface Store
{
    public function read(FileDataTransferObject $fileDataTransferObject): array;

    public function create(FileDataTransferObject $fileDataTransferObject): string;

    public function update(FileDataTransferObject $fileDataTransferObject): string;

    public function delete(FileDataTransferObject $fileDataTransferObject): void;
}
