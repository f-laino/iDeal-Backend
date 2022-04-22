<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Attachment
 * @package App
 * @property integer $id
 * @property string $type
 * @property integer $entity_id
 * @property string $name
 * @property string|null $description
 * @property string|null $path
 * @property boolean $optional
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 */
class Attachment extends Model
{
    protected $table = 'attachments';
    protected $guarded = [];

    const TYPE_QUOTATION = 'QUOTATION';
    const TYPE_CUSTOMER = 'CUSTOMER';
}
