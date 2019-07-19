<?php

namespace App\Http\Controllers;

use App\Game;
use http\Env\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class GameController extends Controller
{
    public function guess($guess): Response
    {

        $game = Game::active()->first();

        if (!$game) {
            $game = Game::createNewGame();
        }

        if ($guess === $game->word) {
            $game->success = true;
            $game->active = false;
            $game->save();
            return response(['status' => 'Complete!', 'word' => $guess]);
        }

        if ($game->guesses >= 6) {
            $game->success = false;
            $game->active = false;
            $game->save();
            return response(['status' => 'Failed', 'word' => $game->word, 'hangman' => $game->hangman]);
        }

        $guess = $guess[0];

        if (Str::contains($game->guessed_letters, $guess)) {
            return response(['status' => 'Already guessed', 'game' => $game]);
        }

        $game->guessed_letters .= $guess;
        $game->save();

        if (Str::contains($game->word, $guess)) {
            return response(['status' => 'Letter found!', 'word' => $game->unguessed, 'game' => $game]);
        }

        ++$game->guesses;
        $game->save();

        return response(['status' => 'Incorrect', 'game' => $game]);

    }
}
