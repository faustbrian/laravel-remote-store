<?php

declare(strict_types=1);

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

    public function read(FileDataTransferObject $file): array
    {
        return GitHub::repo()->contents()->show(
            $this->username,
            $this->repository,
            $file,
        );
    }

    public function create(FileDataTransferObject $file): string
    {
        return GitHub::repo()->contents()->create(
            $this->username,
            $this->repository,
            $file->name,
            \json_encode($file->data),
            'Apollo',
            'main',
            ['name' => 'Apollo', 'email' => 'noreply@apollo.blog'],
        )['content']['sha'];
    }

    public function update(FileDataTransferObject $file): string
    {
        return GitHub::repo()->contents()->update(
            $this->username,
            $this->repository,
            $file->name,
            \json_encode($file->data),
            'Apollo',
            $file->hash,
            'main',
            ['name' => 'Apollo', 'email' => 'noreply@apollo.blog'],
        );
    }

    public function delete(FileDataTransferObject $file): void
    {
        GitHub::repo()->contents()->rm(
            $this->username,
            $this->repository,
            $file->name,
            'Apollo',
            $file->hash,
            'main',
            ['name' => 'Apollo', 'email' => 'noreply@apollo.blog'],
        );
    }
}
