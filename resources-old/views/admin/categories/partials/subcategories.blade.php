

@foreach ($categories as $category)
    <option value="{{ $category->id }}" {{ $category->id == old('category') ? 'selected' : '' }}>

     {{ str_repeat('- ', $category->depth) }}{{ $category->name }}

    </option>
    @if ($category->children->isNotEmpty())
    @include('admin.categories.partials.subcategories', ['categories' => $category->children])
    @endif
@endforeach

