<?php

namespace App\Transformer;

use App\PipedriveFile;

class PipedriveFileTransformer extends BaseTransformer
{
    public function transform(PipedriveFile $file)
    {
        return $file->toArray();
    }
}
