<?php

namespace App\Http\Controllers;

use App\Models\Post;
use DB;
use Gate;
use Illuminate\Http\Request;

class PostReactionController
{
    public function index(Request $request, Post $post)
    {
        Gate::authorize('view', $post);

        $page = $request->integer('page', 1);
        $perPage = $request->integer('per_page', 10);
        $emojiId = $request->filled('emoji_id') ? $request->string('emoji_id') : null;
        $from = $request->filled('from') ? $request->date('from') : null;
        $to = $request->filled('to') ? $request->date('to') : null;

        return DB::table('post_reactions as reaction')
            ->where('reaction.post_id', '=', $post->id)
            ->join('ref_emojis as emoji', 'reaction.emoji_id', '=', 'emoji.id')

            // Emoji filter
            ->when(! empty($emojiId), fn ($query) => $query->where('reaction.emoji_id', $emojiId))

            // Date Range Filter
            ->when(! is_null($from), fn ($query) => $query->where('reaction.created_at', '>=', $from))
            ->when(! is_null($to), fn ($query) => $query->where('reaction.created_at', '<=', $to))

            ->select([
                'reaction.id as reaction_id',
                'emoji.id as emoji_id',
                'emoji.name as emoji_name',
            ])
            ->paginate(perPage: $perPage, page: $page);
    }

    // createOrUpdateOrDelete
    // Handles post reactions:
    // 1. Creates a reaction if none exists.
    // 2. Removes (deletes) the reaction if the same emoji is clicked again.
    // 3. Updates the reaction if a different emoji is selected.
    public function toggle(Request $request, Post $post)
    {
        Gate::authorize('view', $post);

        // Branch 1: Create if no reaction exists yet
        // Branch 2: Delete if clicking the same emoji again (toggle off)
        // Branch 3: Update if selecting a different emoji
    }
}
