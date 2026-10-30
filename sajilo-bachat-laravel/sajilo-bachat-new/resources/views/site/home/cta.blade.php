<section class="px-4 py-14 sm:py-20">
    <div class="container-x">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-brand-800 to-brand-600 px-6 py-12 text-center text-white sm:px-14 sm:py-16">
            <div class="absolute -left-10 -top-10 h-48 w-48 rounded-full border-[24px] border-white/5"></div>
            <div class="absolute -bottom-16 -right-10 h-64 w-64 rounded-full border-[32px] border-white/5"></div>
            <div class="relative mx-auto max-w-2xl">
                <h2 class="text-2xl font-extrabold !text-white sm:text-4xl">{{ ui('home.cta_title') }}</h2>
                <p class="mt-3 text-brand-100">{{ ui('home.cta_text') }}</p>
                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('membership.create') }}" class="btn btn-accent px-7 py-3">{{ ui('nav.member') }}</a>
                    <a href="{{ route('contact') }}" class="btn btn-ghost-light px-7 py-3">{{ ui('nav.contact') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>
