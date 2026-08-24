<x-admin.form.input label="Client Name" name="client_name" :value="$testimonial->client_name ?? ''" required />
<x-admin.form.input label="Company" name="company" :value="$testimonial->company ?? ''" />
<x-admin.form.input label="Position" name="position" :value="$testimonial->position ?? ''" />
<x-admin.form.input label="Photo Path" name="photo" :value="$testimonial->photo ?? ''" placeholder="images/testimonials/..." />
<x-admin.form.textarea label="Testimonial" name="testimonial" :value="$testimonial->testimonial ?? ''" required :rows="3" />
<div class="mb-4">
    <label class="block text-sm font-medium text-text-secondary mb-1.5">Rating</label>
    <select name="rating" class="w-full bg-brand-surface-2 border border-brand-border rounded-xl px-4 py-2.5 text-text-primary text-sm focus:outline-none focus:border-accent">
        @for($i = 5; $i >= 1; $i--)
        <option value="{{ $i }}" {{ ($testimonial->rating ?? 5) == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
        @endfor
    </select>
</div>
<x-admin.form.input label="Sort Order" name="sort_order" type="number" :value="$testimonial->sort_order ?? 0" />
<div class="flex items-center gap-2">
    <input type="checkbox" name="is_published" value="1" id="is_published" {{ ($testimonial->is_published ?? true) ? 'checked' : '' }} class="w-4 h-4 rounded border-brand-border bg-brand-surface-2 text-accent">
    <label for="is_published" class="text-sm text-text-secondary">Published</label>
</div>
