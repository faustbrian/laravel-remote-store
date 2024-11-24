<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\RemoteStore;

use GrahamCampbell\GitHub\Facades\GitHub;

final class GistStore implements Store
{
    public function read(FileDataTransferObject $file): array
    {
        return GitHub::gists()->show($file->hash);
    }

    public function create(FileDataTransferObject $file): string
    {
        return GitHub::gists()->create([
            'public' => false,
            'files' => [
                $file->name => ['content' => \json_encode($file->data)],
            ],
        ])['id'];
    }

    public function update(FileDataTransferObject $file): string
    {
        return GitHub::gists()->update($file['hash'], [
            'public' => false,
            'files' => [
                $file->name => ['content' => \json_encode($file->data)],
            ],
        ])['id'];
    }

    public function delete(FileDataTransferObject $file): void
    {
        GitHub::gists()->remove($file->hash);
    }
}
