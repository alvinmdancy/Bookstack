#!/bin/bash

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

show_menu() {
    local LOCAL_TAG="unknown"
    local LATEST_TAG="unknown"
    local UPDATE_AVAILABLE=0
    local APP_URL="${APP_URL:-http://localhost:8085}"

    # =========================
    # Read LOCAL version
    # =========================
    if [ -f "VERSION" ]; then
        LOCAL_TAG=$(tr -d '[:space:]' < VERSION)
    else
        echo "WARNING: VERSION file not found"
    fi

    # =========================
    # Fetch latest tags
    # =========================
    git fetch --tags origin > /dev/null 2>&1
    LATEST_TAG=$(git tag --sort=-v:refname 2>/dev/null | head -1 | tr -d '[:space:]')
    [ -z "$LATEST_TAG" ] && LATEST_TAG="unknown"

    # =========================
    # Compare versions
    # =========================
    if [ "$LOCAL_TAG" != "unknown" ] && [ "$LATEST_TAG" != "unknown" ] && [ "$LOCAL_TAG" != "$LATEST_TAG" ]; then
        UPDATE_AVAILABLE=1
    fi

    clear
    echo "================================"
    echo "    BookStack Control Panel"
    echo "================================"
    echo ""
    echo "Go to $APP_URL in your browser to access BookStack"
    echo ""
    echo "Current Version: $LOCAL_TAG"
    echo "Latest Version : $LATEST_TAG"
    echo ""
    if [ "$UPDATE_AVAILABLE" -eq 1 ]; then
        echo "*** UPDATE AVAILABLE ***"
        echo ""
    fi
    echo "================================"
    echo "Please select an option:"
    echo "================================"
    echo ""
    echo "1. Start BookStack"
    echo "2. Stop BookStack"
    echo "3. Update BookStack"
    echo "4. Restart BookStack"
    echo "5. Exit"
    echo ""
    read -rp "Select an option: " choice

    case "$choice" in
        1) do_start ;;
        2) do_stop ;;
        3) do_update ;;
        4) do_restart ;;
        5) exit 0 ;;
        *) show_menu ;;
    esac
}

do_start() {
    echo "================================"
    echo "    Starting BookStack...."
    echo "================================"
    docker compose up -d
    echo ""
    echo "Go to ${APP_URL:-http://localhost:8085} in your browser to access BookStack"
    echo ""
    read -rp "Press Enter to return to menu..."
    show_menu
}

do_stop() {
    echo "================================"
    echo "    Stopping BookStack...."
    echo "================================"
    docker compose down
    read -rp "Press Enter to return to menu..."
    show_menu
}

do_update() {
    bash "$SCRIPT_DIR/update.sh"
    show_menu
}

do_restart() {
    echo "================================"
    echo "    Restarting BookStack...."
    echo "================================"
    docker compose down
    docker compose up -d
    read -rp "Press Enter to return to menu..."
    show_menu
}

show_menu