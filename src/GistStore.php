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
    #[\Override()]
    public function read(FileDataTransferObject $fileDataTransferObject): array
    {
        return GitHub::gists()->show($fileDataTransferObject->hash);
    }

    #[\Override()]
    public function create(FileDataTransferObject $fileDataTransferObject): string
    {
        return GitHub::gists()->create([
            'public' => false,
            'files' => [
                $fileDataTransferObject->name => ['content' => \json_encode($fileDataTransferObject->data)],
            ],
        ])['id'];
    }

    #[\Override()]
    public function update(FileDataTransferObject $fileDataTransferObject): string
    {
        return GitHub::gists()->update($fileDataTransferObject['hash'], [
            'public' => false,
            'files' => [
                $fileDataTransferObject->name => ['content' => \json_encode($fileDataTransferObject->data)],
            ],
        ])['id'];
    }

    #[\Override()]
    public function delete(FileDataTransferObject $fileDataTransferObject): void
    {
        GitHub::gists()->remove($fileDataTransferObject->hash);
    }
}
