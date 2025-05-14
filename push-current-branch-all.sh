#!/bin/bash

# Get current branch name
current_branch=$(git rev-parse --abbrev-ref HEAD)

echo "📤 Pushing branch '$current_branch' to all remotes..."

# Loop through all remotes and push current branch
for remote in $(git remote); do
  echo "➡️  Pushing to '$remote'..."
  git push "$remote" "$current_branch"
done

echo "✅ Done pushing '$current_branch' to all remotes."