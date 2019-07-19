<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Str;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;


class Game extends Model
{

    protected $fillable = ['word'];

    protected $hidden = ['word', 'active', 'success', 'created_at', 'updated_at', 'id'];

    protected $appends = ['hangman', 'unguessed','remaining'];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function getRemainingAttribute(): int
    {
        return 9 - $this->guesses;
    }

    public function getUnguessedAttribute(): string
    {
        $word = $this->word;
        $guessed_letters = $this->guessed_letters;
        $unguessed = '';

        for ($i = 0; $i < strlen($word); $i++) {

            if (Str::contains($guessed_letters, $word[$i])) {
                $unguessed .= ' ' . $word[$i] . ' ';
            } else {
                $unguessed .= ' _ ';
            }

        }

        return $unguessed;

    }

    public function getHangmanAttribute(): string
    {
        $mans = explode("''',", file_get_contents('../files/hangman.txt'));
        return $mans[$this->guesses];
    }

    public static function createNewGame()
    {

        $client = new Client();
        $response = $client->get('https://raw.githubusercontent.com/zeisler/scrabble/master/db/dictionary.csv');

        $words = explode("\n", str_replace("\r", '', $response->getBody()->getContents()));
        $word = $words[random_int(0, count($words) - 1)];

        $game = Game::create(
            ['word' => $word]
        );

        return $game;

    }
}
