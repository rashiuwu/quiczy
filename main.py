import json
import os
import random
import signal
import time

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
QUESTIONS_FILE = os.path.join(BASE_DIR, "questions.json")
SCORES_FILE = os.path.join(BASE_DIR, "scores.json")
TIME_LIMIT = 15
QUESTIONS_PER_QUIZ = 10


class TimeUp(Exception):
    pass


def timeout_handler(signum, frame):
    raise TimeUp


def load_questions():
    """Load quiz questions from questions.json."""
    try:
        with open(QUESTIONS_FILE, "r", encoding="utf-8") as file:
            data = json.load(file)
        return data if isinstance(data, list) else []
    except (FileNotFoundError, json.JSONDecodeError):
        return []


def load_scores():
    """Load saved scores from scores.json."""
    try:
        with open(SCORES_FILE, "r", encoding="utf-8") as file:
            data = json.load(file)
        return data if isinstance(data, list) else []
    except (FileNotFoundError, json.JSONDecodeError):
        return []


def save_score(name, score, total, category, difficulty):
    """Append one result to scores.json."""
    scores = load_scores()
    percentage = round((score / total) * 100, 2) if total else 0
    scores.append({
        "name": name,
        "score": score,
        "total": total,
        "percentage": percentage,
        "category": category,
        "difficulty": difficulty,
        "date": time.strftime("%Y-%m-%d %H:%M:%S")
    })
    with open(SCORES_FILE, "w", encoding="utf-8") as file:
        json.dump(scores, file, indent=4)


def clear_screen():
    os.system("clear")


def header(title):
    print("\033[96m╔════════════════════════════════════════════════════════════╗\033[0m")
    print("\033[96m║\033[0m                  \033[1;95mQUICZY\033[0m                         \033[96m║\033[0m")
    print("\033[96m║\033[0m             Linux Quiz Application                    \033[96m║\033[0m")
    print("\033[96m╠════════════════════════════════════════════════════════════╣\033[0m")
    print("\033[96m║\033[0m  " + title.center(56) + "\033[96m║\033[0m")
    print("\033[96m╚════════════════════════════════════════════════════════════╝\033[0m")
    print()


def choose_from_menu(title, options):
    while True:
        print("\033[1;93m" + title + "\033[0m")
        for index, option in enumerate(options, 1):
            print(f"  \033[96m{index}.\033[0m {option}")
        choice = input("\nEnter choice: ").strip()
        if choice.isdigit() and 1 <= int(choice) <= len(options):
            return options[int(choice) - 1]
        print("\033[91mInvalid choice. Please try again.\033[0m\n")


def choose_category():
    return choose_from_menu(
        "Choose Category",
        ["Linux", "Python", "Java", "SQL", "General IT", "All"]
    )


def choose_difficulty():
    return choose_from_menu(
        "Choose Difficulty",
        ["Easy", "Medium", "Hard", "All"]
    )


def get_questions(questions, category, difficulty):
    """Filter by category/difficulty and return up to 10 random questions."""
    filtered = []
    for question in questions:
        category_ok = category == "All" or question.get("category") == category
        difficulty_ok = difficulty == "All" or question.get("difficulty") == difficulty
        if category_ok and difficulty_ok:
            filtered.append(question)
    random.shuffle(filtered)
    return filtered[:QUESTIONS_PER_QUIZ]


def ask_question(question, number, total):
    """Display one question and accept an answer within 15 seconds."""
    print(f"\033[1;95mQuestion {number}/{total}\033[0m  |  Category: {question['category']}  |  Difficulty: {question['difficulty']}")
    print(f"\n\033[1;97m{question['question']}\033[0m\n")

    for index, option in enumerate(question["options"], 1):
        print(f"  \033[96m{index}.\033[0m {option}")

    print(f"\n\033[93m⏱ You have {TIME_LIMIT} seconds.\033[0m")
    signal.signal(signal.SIGALRM, timeout_handler)
    signal.alarm(TIME_LIMIT)

    try:
        answer = input("\nYour answer (1-4): ").strip()
        signal.alarm(0)
    except TimeUp:
        signal.alarm(0)
        print("\n\033[91m⏰ Time's up!\033[0m")
        answer = ""

    correct_index = question["answer"]
    try:
        selected_index = int(answer) if answer else 0
    except ValueError:
        selected_index = 0

    if selected_index == correct_index:
        print("\033[92m✓ Correct! +1 point\033[0m")
        is_correct = True
    else:
        print("\033[91m✗ Incorrect!\033[0m")
        is_correct = False

    print(f"\033[93mCorrect answer: {correct_index}. {question['options'][correct_index - 1]}\033[0m")
    time.sleep(1.2)
    return is_correct


def result_screen(name, score, total, category, difficulty):
    percentage = (score / total) * 100 if total else 0
    clear_screen()
    header("QUIZ COMPLETE")
    print("\033[1;97m                    RESULTS\033[0m\n")
    print(f"  Player       : \033[96m{name}\033[0m")
    print(f"  Category     : {category}")
    print(f"  Difficulty   : {difficulty}")
    print(f"  Score        : \033[1;92m{score}/{total}\033[0m")
    print(f"  Percentage   : \033[1;95m{percentage:.2f}%\033[0m")
    print()
    if percentage >= 80:
        message = "Excellent! You're a Quiczy champion!"
    elif percentage >= 60:
        message = "Great job! Keep sharpening your skills."
    elif percentage >= 40:
        message = "Good effort! A little more practice will help."
    else:
        message = "Keep learning and try again!"
    print("  " + message)
    print("\n  Score saved to scores.json.")
    input("\nPress Enter to return to the main menu...")


def run_quiz(name, questions):
    category = choose_category()
    difficulty = choose_difficulty()
    quiz_questions = get_questions(questions, category, difficulty)

    if not quiz_questions:
        print("\n\033[91mNo questions found for this category/difficulty combination.\033[0m")
        time.sleep(2)
        return

    if len(quiz_questions) < QUESTIONS_PER_QUIZ:
        print(f"\n\033[93mOnly {len(quiz_questions)} matching question(s) are available.\033[0m")
        print("The quiz will use all available matching questions.")
        time.sleep(2)

    score = 0
    clear_screen()
    header("QUIZ IN PROGRESS")
    print(f"Player: \033[96m{name}\033[0m | Category: {category} | Difficulty: {difficulty}")
    input("\nPress Enter to begin...")
    clear_screen()

    for number, question in enumerate(quiz_questions, 1):
        header("QUIZ IN PROGRESS")
        if ask_question(question, number, len(quiz_questions)):
            score += 1
        clear_screen()

    save_score(name, score, len(quiz_questions), category, difficulty)
    result_screen(name, score, len(quiz_questions), category, difficulty)


def show_leaderboard():
    clear_screen()
    header("LEADERBOARD - TOP 10")
    scores = load_scores()
    if not scores:
        print("\033[93mNo scores yet. Be the first to play!\033[0m")
        input("\nPress Enter to return...")
        return

    scores.sort(key=lambda item: (item.get("percentage", 0), item.get("score", 0)), reverse=True)
    print("  Rank  Player                 Score       %       Category")
    print("  " + "─" * 56)
    for rank, entry in enumerate(scores[:10], 1):
        name = str(entry.get("name", "Unknown"))[:20]
        score_text = f"{entry.get('score', 0)}/{entry.get('total', 0)}"
        percentage = f"{entry.get('percentage', 0):.2f}%"
        category = str(entry.get("category", "All"))[:12]
        print(f"  {rank:<5} {name:<20} {score_text:<11} {percentage:<8} {category}")

    input("\nPress Enter to return...")


def main():
    if os.name != "posix":
        print("Quiczy is designed for Linux/Unix terminals.")
        return

    questions = load_questions()
    if not questions:
        print("Unable to load questions.json. Please check the file.")
        return

    clear_screen()
    header("WELCOME")
    name = input("Enter your name: ").strip()
    while not name:
        name = input("Name cannot be empty. Enter your name: ").strip()

    while True:
        clear_screen()
        header(f"WELCOME, {name.upper()}")
        print("  \033[96m1.\033[0m Start Quiz")
        print("  \033[96m2.\033[0m Leaderboard")
        print("  \033[96m3.\033[0m Exit")
        choice = input("\nEnter choice: ").strip()

        if choice == "1":
            clear_screen()
            run_quiz(name, questions)
        elif choice == "2":
            show_leaderboard()
        elif choice == "3":
            clear_screen()
            header("GOODBYE")
            print(f"Thanks for playing, {name}! Keep learning.\n")
            break
        else:
            print("\033[91mInvalid choice. Enter 1, 2 or 3.\033[0m")
            time.sleep(1)


if __name__ == "__main__":
    main()
