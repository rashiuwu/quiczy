#!/bin/bash
# Quiczy - PHP Linux/WSL launcher
cd "$(dirname "$0")" || exit 1
echo "✦ Starting Quiczy PHP Quiz..."
echo "Open http://localhost:8000 in your browser."
php -S localhost:8000
