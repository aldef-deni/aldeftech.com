{{-- Service Form Fields --}}
<x-admin.form.input label="Title" name="title" :value="$service->title ?? ''" required placeholder="e.g. Custom Software Development" />
<x-admin.form.textarea label="Short Description" name="short_description" :value="$service->short_description ?? ''" required placeholder="Brief description (shown in cards)" :rows="2" />
<x-admin.form.textarea label="Full Description" name="description" :value="$service->description ?? ''" placeholder="Detailed description" :rows="5" />
<x-admin.form.input label="Icon" name="icon" :value="$service->icon ?? ''" placeholder="Icon class or emoji" help="Optional icon identifier" />
<x-admin.form.input label="Sort Order" name="sort_order" type="number" :value="$service->sort_order ?? 0" />
<x-admin.form.input label="Meta Title" name="meta_title" :value="$service->meta_title ?? ''" placeholder="SEO title" />
<x-admin.form.textarea label="Meta Description" name="meta_description" :value="$service->meta_description ?? ''" placeholder="SEO description" :rows="2" />

<div class="mb-4">
    <label class="block text-sm font-medium text-text-secondary mb-1.5">Features (one per line)</label>
    <textarea name="features[]" rows="4"
              class="w-full bg-brand-surface-2 border border-brand-border rounded-xl px-4 py-2.5 text-text-primary text-sm focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/20 transition-colors resize-y"
              placeholder="Feature 1&#10;Feature 2&#10;Feature 3">{{ is_array($service->features ?? null) ? implode("\n", $service->features) : '' }}</textarea>
</div>

<div class="flex items-center gap-2">
    <input type="checkbox" name="is_published" value="1" id="is_published"
           {{ ($service->is_published ?? true) ? 'checked' : '' }}
           class="w-4 h-4 rounded border-brand-border bg-brand-surface-2 text-accent focus:ring-accent/20">
    <label for="is_published" class="text-sm text-text-secondary">Published</label>
</div>
