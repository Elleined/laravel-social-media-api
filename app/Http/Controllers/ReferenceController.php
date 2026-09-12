<?php

namespace App\Http\Controllers;

use DB;

class ReferenceController
{
    public function emojis()
    {
        return DB::table('ref_emojis')
            ->select(['id', 'name'])
            ->get();
    }

    public function providerTypes()
    {
        return DB::table('ref_provider_types')
            ->select(['id', 'name'])
            ->get();
    }
}
