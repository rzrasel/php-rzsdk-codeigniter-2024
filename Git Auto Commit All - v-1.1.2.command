#!/bin/bash

# Loop until the user provides a non-empty input
while true; do
    printf "Enter Commit Text: "
    read input
    
    # Check if input is empty
    if [[ -z "$input" ]]; then
        echo "You must enter a commit message!"
    else
        break
    fi
done

# Get the current date and time
dateTime=$(date "+%Y-%m-%d %H:%M:%S")

# Prepare the commit message with input and timestamp
commit="git commit -m"
gitcommit="$commit \"$input - $dateTime\""

# Show the commit message
echo "$gitcommit"

# Perform the git operations
git add .
$gitcommit
git pull
git push --all

# Completion message
echo "Process Complete. Press Enter to continue..."
read