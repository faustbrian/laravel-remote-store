<?php

declare(strict_types=1);

namespace BaseCodeOy\RemoteStore;

final class StoreFactory
{
    public static function make(string $name): Store
    {
        return match ($name) {
            'gist' => new GistStore(),
            'github' => new GitHubStore(),
            'infura' => new InfuraStore(),
            'ipfs' => new IpfsStore(),
            'pinata' => new PinataStore(),
        };
    }
}
