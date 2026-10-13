<tr>
    <td>
        <div class="d-flex align-items-start gap-2 {{ $child ? 'ps-4' : '' }}">
            @if($child)
                <i class="icon-base ti tabler-corner-down-right text-body-secondary mt-1"></i>
            @endif
            <div>
                <a href="{{ route('admin.store.categories.edit', $category) }}" class="fw-medium text-body">{{ $category->name }}</a>
                @if($child)<span class="badge bg-label-secondary ms-1">Subkategori</span>@endif
                @if($category->description)<small class="text-body-secondary d-block text-truncate" style="max-width: 24rem;">{{ $category->description }}</small>@endif
            </div>
        </div>
    </td>
    <td class="d-none d-md-table-cell"><code>{{ $category->slug }}</code></td>
    <td class="text-center">{{ $category->products_count }}</td>
    <td class="text-center d-none d-md-table-cell">{{ $category->sort_order }}</td>
    <td class="text-end text-nowrap">
        @unless($child)
            <a href="{{ route('admin.store.categories.create', ['parent' => $category->id]) }}" class="btn btn-sm btn-icon btn-text-secondary" title="Tambah subkategori">
                <i class="icon-base ti tabler-plus"></i>
            </a>
        @endunless
        <a href="{{ route('admin.store.categories.edit', $category) }}" class="btn btn-sm btn-icon btn-text-secondary" title="Ubah">
            <i class="icon-base ti tabler-pencil"></i>
        </a>
        <x-admin.delete
            :action="route('admin.store.categories.destroy', $category)"
            :confirm="$child
                ? 'Hapus subkategori \'' . $category->name . '\'? Produk di dalamnya tetap ada, hanya tanpa kategori.'
                : 'Hapus kategori \'' . $category->name . '\'? Produk di dalamnya tetap ada tanpa kategori, dan subkategorinya menjadi kategori utama.'" />
    </td>
</tr>
