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

final readonly class IpfsStore implements Store
{
    private PendingRequest $gateway;

    private PendingRequest $api;

    public function __construct()
    {
        $this->gateway = Http::baseUrl(config('services.ipfs.gateway'));
        $this->api = Http::baseUrl(config('services.ipfs.api'));
    }

    #[\Override()]
    public function read(FileDataTransferObject $fileDataTransferObject): array
    {
        return $this->gateway
            ->get('ipfs/'.$fileDataTransferObject->hash)
            ->throw()
            ->json();
    }

    #[\Override()]
    public function create(FileDataTransferObject $fileDataTransferObject): string
    {
        $hash = $this->api
            ->asMultipart()
            ->attach($fileDataTransferObject->name, \json_encode($fileDataTransferObject->data))
            ->post('add')
            ->throw()
            ->json()['Hash'];

        $this->api
            ->post('pin/add/'.$hash)
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
        $this->api
            ->post('pin/rm/'.$fileDataTransferObject->hash)
            ->throw();
    }
}
