@php $vision = $content['vision'] ?? null; $mission = $content['mission'] ?? null; @endphp
@if ($vision || $mission || $objectives->isNotEmpty() || $values->isNotEmpty())
<section class="section section-alt">
    <div class="container-x">
        <x-section-head :eyebrow="ui('home.identity')" :title="ui('home.vmo_title')" :center="true" />
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            @foreach ([[$vision, 'eye', 'about.vision'], [$mission, 'target', 'about.mission']] as [$block, $icon, $label])
                @if ($block)
                    <div class="reveal card p-6 lg:col-span-1">
                        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-600 text-white"><x-icon :name="$icon" class="h-6 w-6" /></span>
                        <h3 class="mt-4 text-lg font-extrabold">{{ ui($label) }}</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ $block->tr('body') }}</p>
                    </div>
                @endif
            @endforeach
            @if ($objectives->isNotEmpty())
                <div class="reveal card p-6">
                    <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-600 text-white"><x-icon name="check-circle" class="h-6 w-6" /></span>
                    <h3 class="mt-4 text-lg font-extrabold">{{ ui('about.objectives') }}</h3>
                    <ul class="mt-2 space-y-2 text-sm text-slate-600">
                        @foreach ($objectives->take(4) as $o)<li class="flex gap-2"><x-icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-brand-600" />{{ $o->tr('title') }}</li>@endforeach
                    </ul>
                </div>
            @endif
            @if ($values->isNotEmpty())
                <div class="reveal card p-6">
                    <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-600 text-white"><x-icon name="heart" class="h-6 w-6" /></span>
                    <h3 class="mt-4 text-lg font-extrabold">{{ ui('about.values') }}</h3>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($values as $v)<span class="badge badge-green">{{ $v->tr('title') }}</span>@endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endif
