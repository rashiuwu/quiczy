# Quiczy – Linux Quiz Application

**Student:** Rashi Sawardekar  
**Class:** TYIT – B  
**Roll No.:** 31010924093

## About the Project

Quiczy is a beginner-friendly command-line quiz application built with **Python 3 for Linux**. It demonstrates Python functions, JSON file handling, randomization, timed input, Linux commands, Bash scripting and file permissions.

## Features

- Player name input
- Main menu: Start Quiz, Leaderboard, Exit
- Categories: Linux, Python, Java, SQL, General IT, All
- Difficulties: Easy, Medium, Hard, All
- Four-option multiple-choice questions
- 15-second timer per question
- Automatically advances when time expires
- Correct/incorrect feedback and correct answer display
- 10 random questions per quiz when enough matching questions exist
- Final score and percentage screen
- Scores saved to `scores.json`
- Top 10 leaderboard
- Questions stored separately in `questions.json`
- Linux launcher script: `run_quiz.sh`

## Project Structure

```text
Quiczy/
├── main.py
├── questions.json
├── scores.json
├── run_quiz.sh
├── README.md
└── screenshots/
```

## Requirements

- Linux/Unix terminal
- Python 3
- No external Python packages are required

The application uses only standard modules requested for the project:

- `json`
- `os`
- `random`
- `signal`
- `time`

## How to Run on Linux

Open a terminal and move into the project directory:

```bash
cd Quiczy
```

Make the launcher executable:

```bash
chmod +x run_quiz.sh
```

Run the application:

```bash
./run_quiz.sh
```

You can also run it directly with:

```bash
python3 main.py
```

## How It Works

1. `main.py` loads questions from `questions.json`.
2. The player enters their name.
3. The main menu is displayed.
4. The player selects a category and difficulty.
5. Matching questions are filtered and shuffled using Python's `random` module.
6. Each question has four options and a 15-second time limit.
7. Linux `SIGALRM`/`signal` is used to interrupt input when the timer expires.
8. The score is calculated and stored in `scores.json`.
9. The leaderboard sorts saved results by percentage and score and displays the top 10.

## Adding New Questions

Open `questions.json` and add another object using the same format:

```json
{
  "category": "Python",
  "difficulty": "Easy",
  "question": "Your question here?",
  "options": ["Option 1", "Option 2", "Option 3", "Option 4"],
  "answer": 2
}
```

The `answer` value is the correct option number from **1 to 4**.

## Viva Points

### Why JSON?
JSON is lightweight, human-readable and easy to edit, so questions and scores can be changed without modifying the Python program.

### Why Bash?
The Bash script provides a simple Linux launcher and demonstrates shell scripting and file permissions.

### Why `signal`?
Linux/Unix signals allow the program to interrupt `input()` after 15 seconds and automatically continue to the next question.

### Why `random`?
It prevents the same fixed question order from appearing every time and makes the quiz more engaging.

### Why functions?
Functions divide the application into small reusable modules such as loading data, selecting filters, asking questions and displaying the leaderboard.

## Important Linux Commands Used

```bash
chmod +x run_quiz.sh
./run_quiz.sh
python3 main.py
clear
```

## Notes

- The timer uses Unix/Linux `SIGALRM`, so Quiczy is intended to run in a Linux/Unix terminal.
- `scores.json` starts empty and is populated after completed quizzes.
- If fewer than 10 questions match a selected category/difficulty, Quiczy uses all available matching questions and reports the number used.
"# quiczy" 
