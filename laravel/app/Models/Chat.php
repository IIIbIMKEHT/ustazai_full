<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Chat
 *
 * @property int $id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property User $user
 * @property Collection|Message[] $messages
 *
 * @package App\Models
 */
class Chat extends Model
{
	protected $table = 'chats';

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'user_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function messages()
	{
		return $this->hasMany(Message::class, 'chat_id', 'id');
	}
}
