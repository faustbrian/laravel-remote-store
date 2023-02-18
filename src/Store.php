<?php

declare(strict_types=1);

namespace PreemStudio\RemoteStore;

interface Store
{
    public function read(FileDataTransferObject $file): array;

    public function create(FileDataTransferObject $file): string;

    public function update(FileDataTransferObject $file): string;

    public function delete(FileDataTransferObject $file): void;
}
