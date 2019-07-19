# lumen-hangman
Lumen Hangman Game


You get 7 guesses, random word is generated after each round.

`{url}/guess/{single letter or full word guess}`


Example API output:

``` 
{

    "status": "Incorrect",
    "game": {
        "guessed_letters": "a",
        "guesses": 1,
        "hangman": " '''\n  +---+\n  |   |\n  O   |\n      |\n      |\n      |\n=========",
        "unguessed": " _  _  _  _  _  _ "
    }

} 

```
