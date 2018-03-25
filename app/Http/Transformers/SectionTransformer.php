<?php

namespace App\Http\Transformers;

use App\Models\Section;
use League\Fractal;

/**
 * SectionTransformer handles turning an internal section model 
 * into a safely exposable section json
 */
class SectionTransformer extends Fractal\TransformerAbstract
{
    /**
     * Transform section object into json
     */
    public function transform(Section $section) 
    {
        return [
        'id' => $section->id,
        'name' => $section->name,
        'created' => $section->created_at->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s'),
        ];
    }
}
