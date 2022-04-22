<?php

namespace App\Transformer;

use App\Attachment;

class AttachmentTransformer extends BaseTransformer
{
    public function transform(Attachment $attachment)
    {
        return [
            'filename' => $attachment->filename,
            'title' => $attachment->title,
            'description' => $attachment->description,
            'link' => $attachment->link,
        ];
    }
}
