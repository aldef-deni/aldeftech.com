# ALDEFTECH Development Instructions

This is the production Laravel project for aldeftech.com.

## General Rules

- Inspect existing implementation before modifying files.
- Preserve the current Laravel architecture.
- Do not duplicate models, controllers, routes, or views that already exist.
- Never expose secrets from .env.
- Never print GEMINI_API_KEY.
- Avoid destructive database operations.
- Do not run migrations that delete or reset production data.
- Run syntax checks after PHP changes.
- Prefer small, reviewable changes.

## AI Article Generator

The project already has:

- app/Services/GeminiService.php
- app/Services/ArticleAIService.php
- app/Models/BlogPost.php
- app/Models/BlogCategory.php
- app/Models/BlogTag.php
- app/Http/Controllers/Admin/BlogPostController.php

Gemini API connectivity has already been tested successfully.

The ArticleAIService already generates:

- title
- slug
- excerpt
- HTML content
- meta_title
- meta_description

## AI Article Publication Rules

An AI generated article may be published automatically, but only when every
quality validation passes. Those validations live in
app/Services/ArticlePublishGuard.php and are shared by the admin generator and
the scheduler. Never bypass them.

- All validations pass: the article may be saved as status = published with
  published_at set, so it appears on /blog, in the homepage Insight block and
  in the sitemap.
- Any validation fails: the article must stay status = draft with
  published_at = null, and the reason must be recorded.
- Never publish: provider/API error responses, empty content, duplicate title
  or slug, incomplete metadata, raw JSON, or malformed content.

Reuse the existing BlogPost system.

Before continuing AI Article Generator development, inspect:

- app/Services/GeminiService.php
- app/Services/ArticleAIService.php
- app/Models/BlogPost.php
- app/Http/Controllers/Admin/BlogPostController.php
- routes/admin.php
- resources/views/admin/blog/

## Next Development Goal

Continue implementing the AI Article Generator:

1. Review existing GeminiService and ArticleAIService.
2. Prevent unsupported statistics and fabricated research claims.
3. Create AIArticleController.
4. Create AI generator admin page.
5. Add routes inside the existing authenticated admin group.
6. Add "Generate Artikel dengan AI" button to the blog admin page.
7. Generate a BlogPost as draft.
8. Redirect admin to existing blog edit page after generation.
9. Preserve the existing blog editor and workflow.

## Git Identity and Attribution

All commits must use:

Author:
Deni Afrizal <deniafrizal2904@gmail.com>

Committer:
Deni Afrizal <deniafrizal2904@gmail.com>

Never add:
- Co-authored-by
- Co-Authored-By
- Contributor
- Contributed-by
- Generated-by
- Assisted-by
- Codex attribution
- ChatGPT attribution
- OpenAI attribution
- Claude attribution
- Anthropic attribution
- any AI attribution

No additional author, co-author, contributor, or AI attribution
may be added to commit messages, source files, README files,
changelogs, pull requests, or deployment notes.

Before every commit, verify:

git config --get user.name
git config --get user.email

Expected:

Deni Afrizal
deniafrizal2904@gmail.com

After every commit, verify:

git log -1 --format='%an <%ae>'
git log -1 --format='%B'

## Required Git Workflow

- Every completed code or content change must be committed.
- After the commit is verified, push it to the current remote branch.
- Do not run `git pull` as part of this workflow.
- All commits and pushes must use only the author `Deni Afrizal <deniafrizal2904@gmail.com>`.
- Never add co-author, contributor, or AI attribution metadata.
