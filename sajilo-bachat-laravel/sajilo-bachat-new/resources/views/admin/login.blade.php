<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin login — {{ setting('site_name_en', 'Sajilo Bachat') }}</title>
    <link rel="icon" href="{{ site_favicon() }}">
    @vite(['resources/css/app.css', 'resources/js/admin.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-gradient-to-br from-brand-900 to-brand-600 p-4">
    <div class="w-full max-w-md">
        <div class="mb-6 text-center text-white">
            <img src="{{ site_logo() }}" alt="" class="mx-auto h-20 w-20 rounded-full bg-white/95 object-contain p-1.5 shadow-lg">
            <h1 class="mt-4 text-xl font-extrabold !text-white">{{ setting('site_name_en', 'Sajilo Bachat') }}</h1>
            <p class="text-sm text-brand-100">Administrator sign-in</p>
        </div>
        <form method="POST" action="{{ route('admin.login.attempt') }}" class="card space-y-5 p-6 shadow-2xl sm:p-8">
            @csrf
            <div>
                <label class="label" for="email">Email address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" @class(['field', 'field-error' => $errors->has('email')])>
                @error('email')<p class="err" role="alert">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="label" for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" class="field">
            </div>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-brand-600"> Keep me signed in</label>
            <button class="btn btn-primary w-full py-3">Sign in</button>
            <a href="{{ route('home') }}" class="block text-center text-sm font-semibold text-brand-700 hover:underline">← Back to website</a>
        </form>
    </div>
</body>
</html>
