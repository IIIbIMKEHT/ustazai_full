<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Message
 * 
 * @property int $id
 * @property int $chat_id
 * @property string $sender_type
 * @property string $message
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Chat $chat
 *
 * @package App\Models
 */
class Message extends Model
{
	protected $table = 'messages';

	protected $casts = [
		'chat_id' => 'int'
	];

	protected $fillable = [
		'chat_id',
		'sender_type',
		'message'
	];

	public function chat()
	{
		return $this->belongsTo(Chat::class);
	}
}
