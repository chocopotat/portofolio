# 📝 Git Workflow & Version Control Guide

Complete guide for managing portfolio project with Git and GitHub.

---

## Initial Git Setup

### 1. Clone or Initialize Repository

**If cloning existing repo:**
```bash
git clone https://github.com/your-username/portfolio.git
cd portfolio
```

**If starting fresh:**
```bash
cd portfolio
git init
git remote add origin https://github.com/your-username/portfolio.git
```

### 2. Configure Git (First Time Only)

```bash
git config --global user.name "Your Name"
git config --global user.email "your-email@example.com"

# Verify configuration
git config --list
```

### 3. Create .gitignore

```bash
# .gitignore file already exists in Laravel
# Verify it includes:
.env              # Environment variables (NEVER commit!)
/vendor/          # PHP dependencies
/node_modules/    # Node packages
/storage/         # Log files and temporary files
/bootstrap/cache/ # Cache files
.DS_Store         # macOS files
*.swp             # Editor temp files
```

---

## Typical Development Workflow

### 1. Create Feature Branch

```bash
# Create and switch to new branch
git checkout -b feature/add-blog-section

# Branch naming conventions:
# feature/   → New feature
# bugfix/    → Bug fix
# docs/      → Documentation
# refactor/  → Code refactoring
# chore/     → Maintenance tasks
```

### 2. Make Changes

```bash
# Edit files as needed
# Example:
# - Add new component to resources/views/
# - Update controller logic
# - Modify CSS styles
```

### 3. Check Status

```bash
# See what files changed
git status

# See detailed changes
git diff

# See changes for specific file
git diff resources/views/portfolio.blade.php
```

### 4. Stage Changes

```bash
# Stage specific file
git add resources/views/portfolio.blade.php

# Stage all changes
git add .

# Stage files matching pattern
git add "*.blade.php"
```

### 5. Commit Changes

```bash
# Commit with message
git commit -m "Add blog section to portfolio page"

# Detailed commit message (opens editor)
git commit

# Best practices for commit messages:
# - Start with verb: "Add", "Fix", "Update", "Remove"
# - Be specific: "Fix form validation" not "Fix bug"
# - Keep under 50 characters for first line
# - Reference issues if applicable: "Fix #123"
```

### 6. Push to Remote

```bash
# Push branch to GitHub
git push origin feature/add-blog-section

# Set upstream tracking (first time only)
git push -u origin feature/add-blog-section

# Push all branches
git push origin --all
```

### 7. Create Pull Request

**On GitHub:**
1. Go to repository
2. Click "Compare & pull request" button
3. Add PR title and description
4. Reference related issues (#123)
5. Request review from team members
6. Wait for CI checks to pass
7. Merge when approved

---

## Commit Message Guidelines

### Format

```
<type>: <subject>

<body>

<footer>
```

### Example

```
feat: Add blog section to portfolio

- Create new BlogPost model with Laravel
- Add blog index and show views
- Implement search and filtering
- Add migration for blog posts table

Closes #45
```

### Types

- **feat**: New feature
- **fix**: Bug fix
- **docs**: Documentation
- **style**: Code style (formatting, missing semicolons)
- **refactor**: Code refactoring without feature changes
- **perf**: Performance improvements
- **test**: Adding or updating tests
- **chore**: Build tasks, dependencies, tooling

---

## Working with Branches

### List Branches

```bash
# Local branches
git branch

# All branches (local + remote)
git branch -a

# Branches with last commit
git branch -v
```

### Switch Branches

```bash
# Switch to existing branch
git checkout main
git checkout feature/add-blog

# Switch and create new (shorthand)
git switch -c feature/new-feature

# Alternative (newer syntax)
git switch main
git switch -c feature/new-section
```

### Delete Branches

```bash
# Delete local branch (after merging)
git branch -d feature/old-feature

# Force delete (even if not merged)
git branch -D feature/old-feature

# Delete remote branch
git push origin --delete feature/old-feature
```

### Merge Branches

```bash
# Switch to main branch
git checkout main

# Pull latest changes
git pull origin main

# Merge feature branch
git merge feature/add-blog

# Handle merge conflicts if any
# Edit conflicted files
# git add conflicted-files
# git commit
```

---

## Undoing Changes

### Before Committing

```bash
# View unstaged changes
git diff

# View staged changes
git diff --cached

# Discard all unstaged changes in working directory
git checkout -- .

# Discard specific file changes
git checkout -- resources/css/app.css

# Unstage specific file
git reset resources/views/portfolio.blade.php

# Unstage all
git reset
```

### After Committing

```bash
# View commit history
git log
git log --oneline
git log --graph --oneline --all

# Amend last commit (before pushing)
git commit --amend

# Revert specific commit (creates new commit)
git revert abc123

# Undo last commit (keep changes unstaged)
git reset HEAD~1

# Undo last commit (discard changes)
git reset --hard HEAD~1

# Go back to specific commit
git checkout abc123

# Reset to specific commit (discard everything after)
git reset --hard abc123
```

---

## Syncing with Remote

### Pull Changes

```bash
# Fetch and merge (pull = fetch + merge)
git pull origin main

# Just fetch (preview changes)
git fetch origin

# Pull with rebase instead of merge
git pull --rebase origin main
```

### Push Changes

```bash
# Push current branch
git push origin feature/my-feature

# Push all branches
git push origin --all

# Push with tags
git push origin --all --tags

# Force push (careful!)
git push origin --force
```

### Stashing Work

```bash
# Save uncommitted changes temporarily
git stash

# List stashed changes
git stash list

# Apply latest stash
git stash pop

# Apply specific stash
git stash apply stash@{0}

# Drop stash
git stash drop stash@{0}
```

---

## Managing Tags (Releases)

### Create Tags

```bash
# Lightweight tag
git tag v1.0.0

# Annotated tag with message
git tag -a v1.0.0 -m "Version 1.0.0 - Production release"

# Tag specific commit
git tag v1.0.0 abc123
```

### Push Tags

```bash
# Push specific tag
git push origin v1.0.0

# Push all tags
git push origin --tags
```

### List Tags

```bash
git tag
git tag -l "v*"
git show v1.0.0
```

---

## Handling Merge Conflicts

### When Conflicts Occur

```
<<<<<<< HEAD
Your current code
=======
Incoming code from branch
>>>>>>> feature/other-branch
```

### Resolution Steps

1. **Identify conflicts:**
   ```bash
   git status
   # Shows "both modified" files
   ```

2. **Open conflicted file and resolve:**
   ```
   Choose one version or combine both
   Remove conflict markers (<<<<<<, ======, >>>>>>)
   ```

3. **Stage resolved files:**
   ```bash
   git add resolved-file.php
   ```

4. **Complete merge:**
   ```bash
   git commit -m "Merge branch 'feature/other-branch' - resolved conflicts"
   ```

### Using Merge Tools

```bash
# Open visual merge tool
git mergetool

# Abort merge if needed
git merge --abort
```

---

## Collaborative Workflow

### Before Pushing

```bash
# Update local main from remote
git fetch origin
git rebase origin/main

# Or merge (if prefer merge over rebase)
git merge origin/main
```

### Code Review Process

```bash
# 1. Create feature branch
git checkout -b feature/new-feature

# 2. Make commits
git add .
git commit -m "Add new feature"

# 3. Push to remote
git push -u origin feature/new-feature

# 4. Create Pull Request on GitHub
# 5. Team reviews and requests changes
# 6. Make requested changes
git add .
git commit -m "Address review comments"
git push origin feature/new-feature

# 7. Once approved, merge on GitHub
# 8. Sync local repository
git checkout main
git pull origin main

# 9. Delete local feature branch
git branch -d feature/new-feature
```

---

## Useful Commands

### View History

```bash
# One-line log
git log --oneline

# Graph view
git log --graph --oneline --all

# Show specific commits
git show abc123

# Blame (see who changed what)
git blame resources/views/portfolio.blade.php
```

### Search

```bash
# Search commits by message
git log --grep="contact form"

# Search code changes
git log -S "specific code" -p

# Find who deleted something
git log -- deleted-file.php
```

### Cleanup

```bash
# Remove untracked files (dangerous!)
git clean -fd

# Remove untracked files (preview first)
git clean -fdn

# Prune remote tracking branches
git remote prune origin
```

---

## GitHub-Specific Workflow

### Fork & Clone (Contributing to Others' Projects)

```bash
# 1. Fork on GitHub (click "Fork" button)

# 2. Clone your fork
git clone https://github.com/YOUR-USERNAME/portfolio.git
cd portfolio

# 3. Add upstream (original repo)
git remote add upstream https://github.com/ORIGINAL-OWNER/portfolio.git

# 4. Create feature branch
git checkout -b feature/improvement

# 5. Make changes and commit
git add .
git commit -m "Add improvement"

# 6. Keep in sync with upstream
git fetch upstream
git rebase upstream/main

# 7. Push to your fork
git push origin feature/improvement

# 8. Create Pull Request from GitHub UI
```

### Managing Remotes

```bash
# List remotes
git remote -v

# Add remote
git remote add upstream https://github.com/original/repo.git

# Rename remote
git remote rename origin github

# Remove remote
git remote remove origin

# Change remote URL
git remote set-url origin https://github.com/new-url.git
```

---

## Best Practices

### ✅ DO

- ✅ Commit often with clear messages
- ✅ Pull before pushing to avoid conflicts
- ✅ Create feature branches for all work
- ✅ Keep commits focused and small
- ✅ Review your changes before committing
- ✅ Use meaningful branch names
- ✅ Delete merged branches to keep clean
- ✅ Write descriptive PR descriptions
- ✅ Request code reviews before merging

### ❌ DON'T

- ❌ Commit sensitive data (.env files)
- ❌ Force push to shared branches
- ❌ Commit directly to main
- ❌ Create huge commits with mixed changes
- ❌ Leave merge conflicts unresolved
- ❌ Use vague commit messages
- ❌ Ignore .gitignore rules
- ❌ Skip code review process
- ❌ Merge without testing locally first

---

## Troubleshooting

### "fatal: origin already exists"

```bash
# Remove existing remote
git remote remove origin

# Add new remote
git remote add origin https://github.com/username/repo.git
```

### "Your branch is ahead of 'origin/main' by X commits"

```bash
# Push your commits
git push origin main

# Or if you want to discard local commits
git reset --hard origin/main
```

### "Your branch has diverged from 'origin/main'"

```bash
# Rebase to fix divergence
git fetch origin
git rebase origin/main

# Or merge to combine
git fetch origin
git merge origin/main
```

### "Detached HEAD state"

```bash
# You've checked out a specific commit instead of a branch
# Switch back to main
git checkout main

# Or create new branch from current position
git checkout -b recovery-branch
```

---

## Deployment Workflow

### Tagging for Release

```bash
# 1. On main branch, ensure all code is pushed
git checkout main
git pull origin main

# 2. Create version tag
git tag -a v1.2.3 -m "Release version 1.2.3"

# 3. Push tag
git push origin v1.2.3

# 4. Deploy (CI/CD automatically triggers)
```

### Production Deployment Strategy

```bash
main (stable)
 ↓
 └─ feature/new-feature (develop here)
     ↓
     └─ create PR
         ↓
         └─ code review
             ↓
             └─ merge to main
                 ↓
                 └─ tag release
                     ↓
                     └─ deploy to production
```

---

## Useful Aliases

Add to `.git/config` or global config:

```bash
git config --global alias.co checkout
git config --global alias.br branch
git config --global alias.ci commit
git config --global alias.st status
git config --global alias.lg "log --graph --oneline --all"
git config --global alias.unstage "reset HEAD --"
git config --global alias.last "log -1 HEAD"
git config --global alias.visual "log --graph --oneline --all"

# Usage:
git co main        # instead of git checkout main
git br             # instead of git branch
git lg             # instead of git log --graph --oneline --all
```

---

## Resources

- [Git Official Documentation](https://git-scm.com/doc)
- [GitHub Docs](https://docs.github.com)
- [Pro Git Book (Free)](https://git-scm.com/book/en/v2)
- [Interactive Git Learning](https://learngitbranching.js.org)
- [GitHub Desktop (GUI)](https://desktop.github.com)

---

**Last Updated**: 2024
**Status**: Ready for Use ✅
