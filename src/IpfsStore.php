<?php

declare(strict_types=1);

namespace BombenProdukt\RemoteStore;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

final class IpfsStore implements Store
{
    private PendingRequest $gateway;

    private PendingRequest $api;

    public function __construct()
    {
        $this->gateway = Http::baseUrl(config('services.ipfs.gateway'));
        $this->api = Http::baseUrl(config('services.ipfs.api'));
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
        $hash = $this->api
            ->asMultipart()
            ->attach($file->name, \json_encode($file->data))
            ->post('add')
            ->throw()
            ->json()['Hash'];

        $this->api
            ->post("pin/add/{$hash}")
            ->throw();

        return $hash;
    }

    public function update(FileDataTransferObject $file): string
    {
        return $this->create($file);
    }

    public function delete(FileDataTransferObject $file): void
    {
        $this->api
            ->post("pin/rm/{$file->hash}")
            ->throw();
    }
}
