<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

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
