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

AI generated articles must always be created as:

status = draft

Never automatically publish AI generated content.

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
aldef-deni <deniafrizal2904@gmail.com>

Do not change git user.name or git user.email.

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

aldef-deni
deniafrizal2904@gmail.com

After every commit, verify:

git log -1 --format='%an <%ae>'
git log -1 --format='%B'
