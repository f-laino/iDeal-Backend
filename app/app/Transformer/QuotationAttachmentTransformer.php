<?php

namespace App\Transformer;

use App\Transformer\AttachmentTransformer;

class QuotationAttachmentTransformer extends BaseTransformer
{
    protected $defaultIncludes = ['files'];

    public function transform(array $attachments)
    {
        return $attachments;
    }

    public function includeFiles(array $attachmentGroup)
    {
        return $this->collection($attachmentGroup['files'], new AttachmentTransformer);
    }
}
