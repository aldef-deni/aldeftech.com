{{-- Portfolio Form Fields --}}
<x-admin.form.input label="Title" name="title" :value="$portfolio->title ?? ''" required placeholder="Project title" />
<x-admin.form.textarea label="Short Description" name="short_description" :value="$portfolio->short_description ?? ''" required placeholder="Brief project description" :rows="2" />
<x-admin.form.textarea label="Full Description" name="description" :value="$portfolio->description ?? ''" placeholder="Detailed description" :rows="5" />

<div class="grid grid-cols-2 gap-4">
    <div class="mb-4">
        <label class="block text-sm font-medium text-text-secondary mb-1.5">Category</label>
        <select name="category_id" class="w-full bg-brand-surface-2 border border-brand-border rounded-xl px-4 py-2.5 text-text-primary text-sm focus:outline-none focus:border-accent">
            <option value="">None</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ ($portfolio->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <x-admin.form.input label="Client" name="client" :value="$portfolio->client ?? ''" placeholder="Client name or Demo" />
</div>

<div class="grid grid-cols-2 gap-4">
    <x-admin.form.input label="Year" name="year" :value="$portfolio->year ?? ''" placeholder="e.g. 2024" />
    <x-admin.form.input label="Project URL" name="project_url" type="url" :value="$portfolio->project_url ?? ''" placeholder="https://..." />
</div>

<x-admin.form.input label="Featured Image Path" name="featured_image" :value="$portfolio->featured_image ?? ''" placeholder="images/portfolio/..." />

<div class="mb-4">
    <label class="block text-sm font-medium text-text-secondary mb-1.5">Technologies (one per line)</label>
    <textarea name="technologies[]" rows="3" class="w-full bg-brand-surface-2 border border-brand-border rounded-xl px-4 py-2.5 text-text-primary text-sm focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/20 transition-colors resize-y" placeholder="Laravel&#10;Vue.js&#10;MySQL">{{ is_array($portfolio->technologies ?? null) ? implode("\n", $portfolio->technologies) : '' }}</textarea>
</div>

<x-admin.form.textarea label="The Challenge" name="challenge" :value="$portfolio->challenge ?? ''" :rows="3" />
<x-admin.form.textarea label="The Approach" name="approach" :value="$portfolio->approach ?? ''" :rows="3" />
<x-admin.form.textarea label="The Solution" name="solution" :value="$portfolio->solution ?? ''" :rows="3" />
<x-admin.form.textarea label="The Results" name="results" :value="$portfolio->results ?? ''" :rows="3" />

<div class="grid grid-cols-2 gap-4">
    <x-admin.form.input label="Published At" name="published_at" type="datetime-local" :value="$portfolio->published_at ? $portfolio->published_at->format('Y-m-d\TH:i') : ''" />
    <x-admin.form.input label="Sort Order" name="sort_order" type="number" :value="$portfolio->sort_order ?? 0" />
</div>

<x-admin.form.input label="Meta Title" name="meta_title" :value="$portfolio->meta_title ?? ''" />
<x-admin.form.textarea label="Meta Description" name="meta_description" :value="$portfolio->meta_description ?? ''" :rows="2" />

<div class="flex items-center gap-6 mt-2">
    <div class="flex items-center gap-2">
        <input type="checkbox" name="is_featured" value="1" id="is_featured" {{ ($portfolio->is_featured ?? false) ? 'checked' : '' }} class="w-4 h-4 rounded border-brand-border bg-brand-surface-2 text-accent">
        <label for="is_featured" class="text-sm text-text-secondary">Featured</label>
    </div>
    <div class="flex items-center gap-2">
        <input type="checkbox" name="is_published" value="1" id="is_published" {{ ($portfolio->is_published ?? true) ? 'checked' : '' }} class="w-4 h-4 rounded border-brand-border bg-brand-surface-2 text-accent">
        <label for="is_published" class="text-sm text-text-secondary">Published</label>
    </div>
</div>
