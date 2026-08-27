# ✦ Quiczy — PHP IT Quiz Application

**Student:** Rashi Sawardekar  
**Class:** TYIT – B  
**Roll No.:** 31010924093

## About

Quiczy is a PHP-based quiz web application for Linux/WSL. It keeps the original quiz requirements:

- Player name
- Categories: Linux, Python, Java, SQL, General IT, All
- Difficulties: Easy, Medium, Hard, All
- Four-option MCQs
- 15-second timer
- Automatic timeout
- Correct/incorrect feedback
- Up to 10 random questions
- Score and percentage
- JSON score storage
- Top 10 leaderboard

The original question bank is stored in `questions.json`.

## Technology

- PHP
- HTML5
- CSS3
- JavaScript
- JSON
- Linux / WSL

No database or external PHP package is required.

## Project Structure

```text
Quiczy/
├── index.php
├── quiz.php
├── feedback.php
├── result.php
├── leaderboard.php
├── style.css
├── questions.json
├── scores.json
├── main.py
├── run_quiz.sh
└── README.md
```

For the PHP submission, the important files are the `.php`, `style.css`, `questions.json` and `scores.json` files.

## Run on WSL

From WSL:

```bash
cd /mnt/c/Users/Rashi/Downloads/Quiczy
php -v
php -S localhost:8000
```

Open Windows Chrome:

```text
http://localhost:8000
```

Stop the server with:

```text
Ctrl + C
```

## Linux Commands Demonstrated

```bash
cd
ls
chmod
php
```

The application itself can be served directly from the Linux/WSL terminal.

## How It Works

1. `index.php` loads the question bank and lets the player choose a category and difficulty.
2. PHP filters and shuffles matching questions.
3. The quiz stores the selected questions and score in a PHP session.
4. `quiz.php` displays one question at a time.
5. JavaScript counts down from 15 seconds.
6. PHP checks the submitted answer.
7. `feedback.php` shows correct/incorrect feedback.
8. `result.php` calculates the final percentage and saves the result to `scores.json`.
9. `leaderboard.php` sorts results by percentage and score and displays the top 10.

## Viva Points

### Why PHP?

PHP provides server-side processing for the quiz, answer validation, session management and score storage.

### Why JSON?

JSON is lightweight, human-readable and easy to edit. It stores the question bank and saved scores without requiring a database.

### Why JavaScript?

JavaScript provides the client-side 15-second countdown and automatically submits the quiz when time expires.

### Why Sessions?

PHP sessions keep the current player, selected quiz questions, current question number and score between pages.

### Why CSS?

CSS creates the graphical user interface with responsive cards, buttons, progress bars, timer styling and leaderboard design.

## Important Note

The PHP version is the submission version. The older Python files remain in the folder as the earlier prototype, but they are not required to run the PHP application.
