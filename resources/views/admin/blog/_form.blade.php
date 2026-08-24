{{-- Blog Form Fields --}}
<x-admin.form.input label="Title" name="title" :value="$post->title ?? ''" required placeholder="Blog post title" />
<x-admin.form.textarea label="Excerpt" name="excerpt" :value="$post->excerpt ?? ''" placeholder="Short summary" :rows="2" />

<div class="mb-4">
    <label class="block text-sm font-medium text-text-secondary mb-1.5">Content <span class="text-danger">*</span></label>
    <textarea name="content" rows="12"
              class="w-full bg-brand-surface-2 border border-brand-border rounded-xl px-4 py-2.5 text-text-primary text-sm focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/20 transition-colors resize-y"
              required>{{ old('content', $post->content ?? '') }}</textarea>
    @error('content')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
    <p class="text-text-dark text-xs mt-1">Supports HTML content</p>
</div>

<div class="grid grid-cols-2 gap-4">
    <div class="mb-4">
        <label class="block text-sm font-medium text-text-secondary mb-1.5">Category</label>
        <select name="category_id" class="w-full bg-brand-surface-2 border border-brand-border rounded-xl px-4 py-2.5 text-text-primary text-sm focus:outline-none focus:border-accent">
            <option value="">None</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ ($post->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-4">
        <label class="block text-sm font-medium text-text-secondary mb-1.5">Status</label>
        <select name="status" class="w-full bg-brand-surface-2 border border-brand-border rounded-xl px-4 py-2.5 text-text-primary text-sm focus:outline-none focus:border-accent">
            <option value="draft" {{ ($post->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="published" {{ ($post->status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
            <option value="scheduled" {{ ($post->status ?? '') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
        </select>
    </div>
</div>

<x-admin.form.input label="Featured Image" name="featured_image" :value="$post->featured_image ?? ''" placeholder="images/blog/..." />
<x-admin.form.input label="Published At" name="published_at" type="datetime-local" :value="$post->published_at ? $post->published_at->format('Y-m-d\TH:i') : ''" />

<div class="mb-4">
    <label class="block text-sm font-medium text-text-secondary mb-1.5">Tags</label>
    <div class="flex flex-wrap gap-2">
        @foreach($tags as $tag)
        <label class="flex items-center gap-1.5 bg-brand-surface-2 border border-brand-border rounded-lg px-3 py-1.5 cursor-pointer hover:border-accent/30 transition-colors">
            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                   {{ in_array($tag->id, ($post->tags->pluck('id')->toArray() ?? [])) ? 'checked' : '' }}
                   class="w-3.5 h-3.5 rounded border-brand-border bg-brand-surface text-accent">
            <span class="text-xs text-text-secondary">{{ $tag->name }}</span>
        </label>
        @endforeach
    </div>
</div>

<x-admin.form.input label="Meta Title" name="meta_title" :value="$post->meta_title ?? ''" />
<x-admin.form.textarea label="Meta Description" name="meta_description" :value="$post->meta_description ?? ''" :rows="2" />
<x-admin.form.input label="Canonical URL" name="canonical_url" type="url" :value="$post->canonical_url ?? ''" />
