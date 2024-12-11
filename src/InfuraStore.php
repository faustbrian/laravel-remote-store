<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\RemoteStore;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

final readonly class InfuraStore implements Store
{
    private PendingRequest $pendingRequest;

    public function __construct()
    {
        $this->pendingRequest = Http::baseUrl('https://ipfs.infura.io:5001/api/v0');
    }

    #[\Override()]
    public function read(FileDataTransferObject $fileDataTransferObject): array
    {
        return $this->pendingRequest
            ->get('cat', ['arg' => $fileDataTransferObject->hash])
            ->throw()
            ->json();
    }

    #[\Override()]
    public function create(FileDataTransferObject $fileDataTransferObject): string
    {
        $hash = $this->pendingRequest
            ->asMultipart()
            ->attach($fileDataTransferObject->name, \json_encode($fileDataTransferObject->data))
            ->post('add')
            ->throw()
            ->json()['Hash'];

        $this->pendingRequest
            ->post('pin/add', ['arg' => $fileDataTransferObject->hash])
            ->throw();

        return $hash;
    }

    #[\Override()]
    public function update(FileDataTransferObject $fileDataTransferObject): string
    {
        return $this->create($fileDataTransferObject);
    }

    #[\Override()]
    public function delete(FileDataTransferObject $fileDataTransferObject): void
    {
        $this->pendingRequest
            ->post('pin/rm', ['arg' => $fileDataTransferObject->hash])
            ->throw();
    }
}
