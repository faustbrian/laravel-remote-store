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

final class InfuraStore implements Store
{
    private PendingRequest $client;

    public function __construct()
    {
        $this->client = Http::baseUrl('https://ipfs.infura.io:5001/api/v0');
    }

    public function read(FileDataTransferObject $file): array
    {
        return $this->client
            ->get('cat', ['arg' => $file->hash])
            ->throw()
            ->json();
    }

    public function create(FileDataTransferObject $file): string
    {
        $hash = $this->client
            ->asMultipart()
            ->attach($file->name, \json_encode($file->data))
            ->post('add')
            ->throw()
            ->json()['Hash'];

        $this->client
            ->post('pin/add', ['arg' => $file->hash])
            ->throw();

        return $hash;
    }

    public function update(FileDataTransferObject $file): string
    {
        return $this->create($file);
    }

    public function delete(FileDataTransferObject $file): void
    {
        $this->client
            ->post('pin/rm', ['arg' => $file->hash])
            ->throw();
    }
}
