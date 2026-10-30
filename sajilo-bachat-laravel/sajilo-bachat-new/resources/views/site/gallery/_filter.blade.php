@if ($categories->isNotEmpty())
    <div class="mb-8 flex flex-wrap gap-2">
        <a href="{{ request()->url() }}" @class(['badge px-4 py-1.5 text-sm', 'badge-green !bg-brand-600 !text-white' => ! request('category'), 'badge-gray hover:bg-brand-100' => request('category')])>{{ ui('common.all') }}</a>
        @foreach ($categories as $c)
            <a href="{{ request()->url() }}?category={{ $c->id }}" @class(['badge px-4 py-1.5 text-sm', '!bg-brand-600 !text-white' => request('category') == $c->id, 'badge-gray hover:bg-brand-100' => request('category') != $c->id])>{{ $c->name() }}</a>
        @endforeach
    </div>
@endif
