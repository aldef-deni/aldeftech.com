# ALDEFTECH AI Article Generator - Development Handoff

## Objective

Membangun fitur AI Article Generator pada website ALDEFTECH
menggunakan Gemini API dari Google AI Studio.

AI digunakan untuk menghasilkan artikel teknologi/IT untuk
modul Blog/Insight yang sudah tersedia.

Artikel tidak boleh langsung dipublish.
Workflow:

AI Generate
→ Save Draft
→ Admin Review/Edit
→ Publish


## Existing Stack

Backend:
- Laravel
- PHP 8.5

Website:
- aldeftech.com

Project directory:
- /var/www/aldeftech


## Gemini Configuration

Gemini API menggunakan Google AI Studio.

Environment:

GEMINI_API_KEY=<configured>
GEMINI_MODEL=gemini-3.8-flash
GEMINI_BASE_URL=https://generativelanguage.googleapis.com/v1beta
GEMINI_TIMEOUT=120

IMPORTANT:
Never expose or print GEMINI_API_KEY.


## Laravel Config

config/services.php already contains:

'gemini' => [
    'api_key' => env('GEMINI_API_KEY'),
    'model' => env('GEMINI_MODEL', 'gemini-2.5-flash'),
    'base_url' => env(
        'GEMINI_BASE_URL',
        'https://generativelanguage.googleapis.com/v1beta'
    ),
    'timeout' => (int) env('GEMINI_TIMEOUT', 120),
],

Runtime configuration has already been verified:

config('services.gemini.model')
=> gemini-3.8-flash

!empty(config('services.gemini.api_key'))
=> true


## Gemini Service

Created:

app/Services/GeminiService.php

Gemini connection has been successfully tested with Laravel.

Test result:

"Koneksi Gemini ALDEFTECH berhasil."

GeminiService supports:

generate(string $prompt)

and is being extended/used for:

generateJson(string $prompt, array $schema)


## AI Article Service

Created:

app/Services/ArticleAIService.php

PHP syntax validated successfully:

php -l app/Services/ArticleAIService.php

Result:

No syntax errors detected


## Article Generator Test

ArticleAIService successfully generated structured article data.

Test:

$ai = app(\App\Services\ArticleAIService::class);

$article = $ai->generate([
    'topic' => 'Manfaat AI Agent untuk meningkatkan pelayanan customer service perusahaan',
    'primary_keyword' => 'AI Agent untuk bisnis',
    'secondary_keywords' => 'customer service AI, otomatisasi bisnis',
    'target_words' => 800,
]);

Successful generated fields:

- title
- slug
- excerpt
- content
- meta_title
- meta_description

Generated content is HTML.


## IMPORTANT CONTENT RULE

Current Gemini generation produced an unsupported statistic such as:

"70-80% pertanyaan rutin"

Until Google Search Grounding/source verification is implemented:

DO NOT generate unsupported:
- statistics
- percentages
- research numbers
- savings numbers
- growth claims
- fake studies
- fake sources
- fabricated company examples

Prefer qualitative statements when no source is available.


## Existing Blog System

DO NOT create a new Article/Post model.

Existing model:

app/Models/BlogPost.php

Existing relationships/models:

- BlogPost
- BlogCategory
- BlogTag

Existing controller:

app/Http/Controllers/Admin/BlogPostController.php

Public controller:

app/Http/Controllers/BlogController.php


## Existing Blog Routes

Admin:

Route::resource('blog', AdminBlogPostController::class)

Public:

/blog
/blog/{post:slug}


## BlogPost Existing Fields

BlogPost already supports:

- title
- slug
- excerpt
- content
- featured_image
- category_id
- author_id
- status
- published_at
- meta_title
- meta_description
- canonical_url

Status values currently used:

- draft
- published
- scheduled

AI-generated content MUST initially use:

status = draft


## Existing Admin Views

resources/views/admin/blog/index.blade.php
resources/views/admin/blog/create.blade.php
resources/views/admin/blog/edit.blade.php
resources/views/admin/blog/_form.blade.php


## Existing Blog Form

The existing form already supports:

- Title
- Slug
- Excerpt
- HTML Content
- Meta Title
- Meta Description
- Canonical URL
- Category
- Tags
- Featured Image
- Status
- Published Date

Do not build a second article editor unnecessarily.

After AI generation:

Create BlogPost draft
→ redirect to existing BlogPost edit page.


## Planned Architecture

Admin Blog
    ↓
AI Article Generator
    ↓
AIArticleController
    ↓
ArticleAIService
    ↓
GeminiService
    ↓
Gemini API
    ↓
Structured JSON
    ↓
BlogPost created as Draft
    ↓
Existing Blog Editor
    ↓
Admin Review
    ↓
Publish


## NEXT DEVELOPMENT TASK

Continue implementation from here.

1. Review current GeminiService.php
2. Review ArticleAIService.php
3. Make sure unsupported numerical/statistical claims are prohibited
4. Create:

app/Http/Controllers/Admin/AIArticleController.php

5. Add AI generator routes inside the existing authenticated admin route group
6. Create:

resources/views/admin/blog/ai-generator.blade.php

7. Add button on:

resources/views/admin/blog/index.blade.php

Button:

"Generate Artikel dengan AI"

8. Generator form should contain:

- topic
- primary_keyword
- secondary_keywords
- category_id
- target_words

Suggested target word options:

800
1200
1600
2000
2500

9. When Gemini succeeds:

Create BlogPost with:

status = draft
author_id = authenticated admin user

10. Generate unique slug safely

Check blog_posts including soft deleted records before inserting.

11. Redirect administrator to:

admin.blog.edit

after draft creation.

12. Do NOT automatically publish AI-generated content.


## Future Enhancements

After basic generator works:

- Google Search Grounding
- verified sources/citations
- AI tag recommendation
- AI category recommendation
- regenerate article
- improve selected content
- SEO scoring
- internal link recommendations
- automatic featured-image prompt
- AI featured-image generation
- content scheduler
- automatic topic planning
- LinkedIn/Facebook/Instagram derivative content


## Development Rules

- Preserve existing ALDEFTECH architecture.
- Do not duplicate BlogPost system.
- Do not expose Gemini API key.
- Do not modify production database destructively.
- Reuse existing Blade components/styles.
- Run syntax checks after PHP changes.
- Run relevant Laravel tests if available.
- Inspect existing route groups before modifying admin routes.
- Review existing code before replacing files.
- Keep generated article as draft.
