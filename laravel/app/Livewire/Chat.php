<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\Chat as ChatModel;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Chat extends Component
{
    public $chats;
    public $chat_id = 0;
    public $messages = [];
    public $question;

    public function mount(): void
    {
        $this->chats = ChatModel::with('messages')->latest()->get();
    }

    public function getMessagesByChatId($chat_id): void
    {
        $this->chat_id = $chat_id;
        $this->messages = Message::where('chat_id', $chat_id)->get();
        $this->chats = ChatModel::with('messages')->latest()->get();
    }

    public function sendQuestion(): void
    {
        if ($this->chat_id == 0) {
            $chat = ChatModel::create(['user_id' => auth()->id()]);
            $this->chat_id = $chat->id;
            $userMessage = Message::create([
                'chat_id' => $chat->id,
                'sender_type' => 'user',
                'message' => $this->question
            ]);
        } else {
            if (ChatModel::find($this->chat_id)) {
                $userMessage = Message::create([
                    'chat_id' => $this->chat_id,
                    'sender_type' => 'user',
                    'message' => $this->question
                ]);
            } else {
                $chat = ChatModel::create(['user_id' => auth()->id()]);
                $this->chat_id = $chat->id;
                $userMessage = Message::create([
                    'chat_id' => $chat->id,
                    'sender_type' => 'user',
                    'message' => $this->question
                ]);
            }
        }
        $resp = Http::post('http://flask:5000/ask-pdf', ['query' => $userMessage->message]);
        $response_result = json_decode($resp->body(), 1);
        $aiMessage = Message::create([
            'chat_id' => $this->chat_id,
            'sender_type' => 'ai',
            'message' => $response_result['answer'] != 0 ? $response_result['answer'] : 'Ничего не найдено',
        ]);
        $this->getMessagesByChatId($this->chat_id);
        $this->question = '';
    }

    public function generateNewChat(): void
    {
        $this->chat_id = 0;
        $chat = ChatModel::create(['user_id' => auth()->id()]);
        $this->chat_id = $chat->id;
        $this->getMessagesByChatId($this->chat_id);
    }

    public function removeChat($chatId): void
    {
        if ($chatId == $this->chat_id) {
            $this->chat_id = 0;
        }
        $chat = ChatModel::find($chatId);
        $chat->delete();
        $this->chats = ChatModel::with('messages')->latest()->get();
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
