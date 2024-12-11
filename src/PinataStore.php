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

final readonly class PinataStore implements Store
{
    private PendingRequest $gateway;

    private PendingRequest $api;

    public function __construct()
    {
        $this->gateway = Http::baseUrl('https://gateway.pinata.cloud/');

        $this->api = Http::baseUrl('https://api.pinata.cloud/')->withHeaders([
            'pinata_api_key' => config('services.pinata.key'),
            'pinata_secret_api_key' => config('services.pinata.secret'),
        ]);
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
        return $this->api
            ->post('pinning/pinJSONToIPFS', $fileDataTransferObject->data)
            ->throw()
            ->json()['IpfsHash'];
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
            ->post('pinning/unpin/'.$fileDataTransferObject->hash)
            ->throw();
    }
}
