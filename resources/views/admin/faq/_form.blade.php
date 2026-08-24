<x-admin.form.input label="Question" name="question" :value="$faq->question ?? ''" required placeholder="Frequently asked question" />
<x-admin.form.textarea label="Answer" name="answer" :value="$faq->answer ?? ''" required :rows="4" />
<x-admin.form.input label="Category" name="category" :value="$faq->category ?? ''" placeholder="e.g. General, Pricing, Process" />
<x-admin.form.input label="Sort Order" name="sort_order" type="number" :value="$faq->sort_order ?? 0" />
<div class="flex items-center gap-2">
    <input type="checkbox" name="is_published" value="1" id="is_published" {{ ($faq->is_published ?? true) ? 'checked' : '' }} class="w-4 h-4 rounded border-brand-border bg-brand-surface-2 text-accent">
    <label for="is_published" class="text-sm text-text-secondary">Published</label>
</div>
