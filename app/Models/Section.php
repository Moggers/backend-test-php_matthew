<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    protected $guarded = ['id', 'created_at'];

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class);
    }

    /**
     * Takes a $section, and patches it by selecting data from $changeset
     *
     * @param Section $section target of changes
     * @param array $params details
     *
     * @return Section $section Updated section
     /*/
    public function changeset(Section $section, array $params): Section {
      $new_section = $section;
      $new_section->name = $params->name;

      return $new_section;
    }
}
