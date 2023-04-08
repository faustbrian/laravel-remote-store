<?php

declare(strict_types=1);

namespace PreemStudio\RemoteStore;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

final class PinataStore implements Store
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

    public function read(FileDataTransferObject $file): array
    {
        return $this->gateway
            ->get("ipfs/{$file->hash}")
            ->throw()
            ->json();
    }

    public function create(FileDataTransferObject $file): string
    {
        return $this->api
            ->post('pinning/pinJSONToIPFS', $file->data)
            ->throw()
            ->json()['IpfsHash'];
    }

    public function update(FileDataTransferObject $file): string
    {
        return $this->create($file);
    }

    public function delete(FileDataTransferObject $file): void
    {
        $this->api
            ->post("pinning/unpin/{$file->hash}")
            ->throw();
    }
}
