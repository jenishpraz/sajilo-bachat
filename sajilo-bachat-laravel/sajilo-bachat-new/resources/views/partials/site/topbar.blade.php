<div class="topbar">
    <div class="container-x topbar__inner">
        <ul class="topbar__contact">
            @if (setting('phone'))
                <li>
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', setting('phone')) }}" class="topbar__item topbar__phone">
                        <x-icon name="phone" class="topbar__icon" />
                        <span>{{ setting('phone') }}</span>
                    </a>
                </li>
            @endif
            @if (setting('email'))
                <li>
                    <a href="mailto:{{ setting('email') }}" class="topbar__item">
                        <x-icon name="mail" class="topbar__icon" />
                        <span>{{ setting('email') }}</span>
                    </a>
                </li>
            @endif
            @if (setting_t('hours'))
                <li class="topbar__item topbar__hours">
                    <x-icon name="clock" class="topbar__icon" />
                    <span>{{ setting_t('hours') }}</span>
                </li>
            @endif
        </ul>
        <div class="topbar__right">
            <div class="topbar__language" role="group" aria-label="{{ ui('common.language') }}">
                <a href="{{ route('locale', 'en') }}" lang="en" @class([
                    'topbar__language-link',
                    'topbar__language-link--active' => ! is_ne(),
                ]) @if(! is_ne()) aria-current="true" @endif>EN</a>
                <a href="{{ route('locale', 'ne') }}" lang="ne" @class([
                    'topbar__language-link',
                    'topbar__language-link--active' => is_ne(),
                ]) @if(is_ne()) aria-current="true" @endif>नेपाली</a>
            </div>
        </div>
    </div>
</div>
