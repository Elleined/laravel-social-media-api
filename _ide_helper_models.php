<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property string $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string $content
 * @property string|null $attachment
 * @property string $post_id
 * @property string $author_id
 * @property-read \App\Models\User|null $author
 * @property-read \App\Models\Post|null $post
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CommentReaction> $reactions
 * @property-read int|null $reactions_count
 * @method static \Database\Factories\CommentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereAttachment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment withoutTrashed()
 * @mixin \Eloquent
 */
	class Comment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property \Illuminate\Support\Carbon $created_at
 * @property string $emoji_id
 * @property string $reactor_id
 * @property string $comment_id
 * @method static \Database\Factories\CommentReactionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentReaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentReaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentReaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentReaction whereCommentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentReaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentReaction whereEmojiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentReaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommentReaction whereReactorId($value)
 * @mixin \Eloquent
 */
	class CommentReaction extends \Eloquent {}
}

namespace App\Models{
/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Emoji newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Emoji newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Emoji query()
 * @property string $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Emoji whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Emoji whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Emoji whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Emoji whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Emoji extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string $content
 * @property string|null $attachment
 * @property string $author_id
 * @property string $title
 * @property-read \App\Models\User|null $author
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PostReaction> $reactions
 * @property-read int|null $reactions_count
 * @method static \Database\Factories\PostFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereAttachment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post withoutTrashed()
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Comment> $comments
 * @property-read int|null $comments_count
 * @mixin \Eloquent
 */
	class Post extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property \Illuminate\Support\Carbon $created_at
 * @property string $emoji_id
 * @property string $reactor_id
 * @property string $post_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post> $reactions
 * @property-read int|null $reactions_count
 * @method static \Database\Factories\PostReactionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction whereEmojiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction whereReactorId($value)
 * @mixin \Eloquent
 */
	class PostReaction extends \Eloquent {}
}

namespace App\Models{
/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Emoji newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Emoji newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Emoji query()
 * @property string $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProviderType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProviderType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProviderType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProviderType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class ProviderType extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property \Illuminate\Support\Carbon $created_at
 * @property string $provider_type_id
 * @property string $provider_id
 * @property string $user_id
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\SocialUserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser whereProviderTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser whereUserId($value)
 * @mixin \Eloquent
 */
	class SocialUser extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string|null $password
 * @property string|null $attachment
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post> $posts
 * @property-read int|null $posts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SocialUser> $socials
 * @property-read int|null $socials_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAttachment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}

