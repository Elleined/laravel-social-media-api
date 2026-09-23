<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentReactionController
{
    public function index(Request $request, Comment $comment)
    {
        $page = $request->integer('page', 1);
        $perPage = $request->integer('per_page', 10);
        $emojiId = $request->string('emoji_id');
        $from = $request->date('from');
        $to = $request->date('to');

        return DB::table('comment_reactions as reaction')
            ->join('ref_emojis as emoji', 'reaction.emoji_id', '=', 'emoji.id')
            ->join('users as user', 'reaction.reactor_id', '=', 'user.id')

            // Comment filter
            ->where('reaction.comment_id', '=', $comment->id)

            // Emoji filter
            ->when($request->filled('emoji_id'), fn ($query) => $query->where('reaction.emoji_id', $emojiId))

            // Date Range Filter
            ->when($request->filled('from'), fn ($query) => $query->where('reaction.created_at', '>=', $from))
            ->when($request->filled('to'), fn ($query) => $query->where('reaction.created_at', '<=', $to))

            ->select([
                'emoji.name as emoji_name',
                DB::raw("CONCAT_WS(' ', user.first_name, user.last_name) as reactor_full_name"),
                'user.attachment as reactor_attachment',
            ])

            ->paginate(perPage: $perPage, page: $page);
    }

    // createOrUpdateOrDelete
    // Handles comment reactions:
    // 1. Creates a reaction if none exists.
    // 2. Removes (deletes) the reaction if the same emoji is clicked again.
    // 3. Updates the reaction if a different emoji is selected.
    public function toggle(Request $request, Comment $comment)
    {
        // allow only current user for update or delete

        $emojiId = $request->string('emoji_id')->value();
        $commentId = $comment->id;
        $reactorId = $request->user()->id;

        Validator::make(
            [
                'emoji_id' => $emojiId,
            ],
            [
                'emoji_id' => ['required', 'string', 'exists:ref_emojis,id'],
            ],
            [
                'exists' => ':attribute does not exists',
            ]
        )->validate();

        // Fetch the record
        $existingRecord = DB::table('comment_reactions')
            ->where('reactor_id', $reactorId)
            ->where('comment_id', $commentId)
            ->first();

        // Branch 1: Create if no reaction exists yet
        if (is_null($existingRecord)) {
            DB::table('comment_reactions')
                ->insert([
                    'comment_id' => $commentId,
                    'reactor_id' => $reactorId,
                    'emoji_id' => $emojiId,
                ]);

            return response()->json([
                'message' => 'Comment reaction created successfully',
            ]);
        }

        // Branch 2: Update if selecting a different emoji
        if ($existingRecord->emoji_id !== $emojiId) {
            DB::table('comment_reactions')
                ->where('reactor_id', $reactorId)
                ->where('comment_id', $commentId)
                ->update([
                    'emoji_id' => $emojiId,
                ]);

            return response()->json([
                'message' => 'Comment reaction updated successfully',
            ]);
        }

        // Branch 3: Delete if clicking the same emoji again (toggle off)
        if ($existingRecord->emoji_id === $emojiId) {
            DB::table('comment_reactions')
                ->where('reactor_id', $reactorId)
                ->where('comment_id', $commentId)
                ->delete();

            return response()->noContent();
        }
    }
}
