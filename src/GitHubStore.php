<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\RemoteStore;

use GrahamCampbell\GitHub\Facades\GitHub;

final class GitHubStore implements Store
{
    public string $username;

    public string $repository;

    public function __construct()
    {
        $this->username = config('services.github.username');
        $this->repository = config('services.github.repository');
    }

    #[\Override()]
    public function read(FileDataTransferObject $fileDataTransferObject): array
    {
        return GitHub::repo()->contents()->show(
            $this->username,
            $this->repository,
            $fileDataTransferObject,
        );
    }

    #[\Override()]
    public function create(FileDataTransferObject $fileDataTransferObject): string
    {
        return GitHub::repo()->contents()->create(
            $this->username,
            $this->repository,
            $fileDataTransferObject->name,
            \json_encode($fileDataTransferObject->data),
            'Apollo',
            'main',
            ['name' => 'Apollo', 'email' => 'noreply@apollo.blog'],
        )['content']['sha'];
    }

    #[\Override()]
    public function update(FileDataTransferObject $fileDataTransferObject): string
    {
        return GitHub::repo()->contents()->update(
            $this->username,
            $this->repository,
            $fileDataTransferObject->name,
            \json_encode($fileDataTransferObject->data),
            'Apollo',
            $fileDataTransferObject->hash,
            'main',
            ['name' => 'Apollo', 'email' => 'noreply@apollo.blog'],
        );
    }

    #[\Override()]
    public function delete(FileDataTransferObject $fileDataTransferObject): void
    {
        GitHub::repo()->contents()->rm(
            $this->username,
            $this->repository,
            $fileDataTransferObject->name,
            'Apollo',
            $fileDataTransferObject->hash,
            'main',
            ['name' => 'Apollo', 'email' => 'noreply@apollo.blog'],
        );
    }
}
