<ul id="style-4">
    @foreach ($search_list as $search)
        <li>
            <a @if ($search->subCategory)
                    href="{{ route('product.detail', [$search->category->slug, $search->subCategory->slug, $search->slug]) }}"
                @else
                    href="{{ route('product.detail', [$search->category->slug, 'varanasi', $search->slug]) }}" @endif
            >
                <img src="{{ asset('backend/admin/product_images/' . $search->images[0]) }}">
                {{ $search->name }}
            </a>
        </li>
    @endforeach
</ul>
