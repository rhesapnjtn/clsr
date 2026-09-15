<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin — CLSR Academy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' rx='20' fill='%230ea5e9'/%3E%3Ctext x='50' y='68' font-size='52' font-family='Arial' font-weight='bold' fill='white' text-anchor='middle'%3EC%3C/text%3E%3C/svg%3E">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        .login-bg {
            background: linear-gradient(135deg, #0c1929 0%, #1e3a5f 40%, #0e4a8a 100%);
        }
        .glow {
            box-shadow: 0 0 80px 20px rgba(56, 189, 248, 0.15);
        }
    </style>
</head>
<body class="login-bg min-h-screen flex items-center justify-center px-4 antialiased">

    {{-- floating orbs --}}
    <div class="pointer-events-none fixed -top-20 -right-20 h-96 w-96 rounded-full bg-sky-400/10 blur-[100px]"></div>
    <div class="pointer-events-none fixed -bottom-32 -left-20 h-96 w-96 rounded-full bg-blue-500/10 blur-[100px]"></div>

    <div class="relative z-10 w-full max-w-md">

        {{-- logo --}}
        <div class="mb-8 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-sky-400 to-blue-600 text-2xl font-extrabold text-white shadow-2xl shadow-sky-500/40 glow">
                C
            </div>
            <h1 class="text-2xl font-extrabold text-white">CLSR Academy</h1>
            <p class="mt-1 text-sm text-sky-200/60">Admin Panel — Manajemen Pendaftaran</p>
        </div>

        {{-- card login --}}
        <div class="rounded-3xl border border-white/10 bg-white/[0.07] p-8 shadow-2xl backdrop-blur-xl sm:p-10">

            {{-- error --}}
            @if ($errors->any() || session('error'))
                <div class="mb-6 rounded-xl border border-rose-400/30 bg-rose-500/10 px-4 py-3 text-sm font-medium text-rose-200 flex items-start gap-2">
                    <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>{{ $errors->first('email', session('error')) }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}" autocomplete="on">
                @csrf

                {{-- email --}}
                <div class="mb-5">
                    <label for="email" class="mb-2 block text-sm font-semibold text-sky-100">Email</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                            <svg class="h-5 w-5 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        </div>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            placeholder="admin@clsr.local"
                            class="w-full rounded-xl border border-white/15 bg-white/10 py-3 pl-11 pr-4 text-sm text-white placeholder-white/30 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-400/20"
                        />
                    </div>
                </div>

                {{-- password --}}
                <div class="mb-6">
                    <label for="password" class="mb-2 block text-sm font-semibold text-sky-100">Password</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                            <svg class="h-5 w-5 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        </div>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            placeholder="••••••••"
                            class="w-full rounded-xl border border-white/15 bg-white/10 py-3 pl-11 pr-4 text-sm text-white placeholder-white/30 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-400/20"
                        />
                    </div>
                </div>

                {{-- remember --}}
                <div class="mb-6 flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember"
                        class="h-4 w-4 rounded border-white/20 bg-white/10 text-sky-500 focus:ring-sky-400/20">
                    <label for="remember" class="text-sm text-white/60">Ingat saya</label>
                </div>

                {{-- submit --}}
                <button
                    type="submit"
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-sky-500 to-blue-600 py-3 text-sm font-bold text-white shadow-lg shadow-sky-500/30 transition hover:from-sky-400 hover:to-blue-500 active:scale-[0.98]"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                    Masuk
                </button>
            </form>
        </div>

        {{-- back to public --}}
        <div class="mt-6 text-center">
            <a href="/" class="inline-flex items-center gap-1.5 text-sm font-medium text-sky-300/50 transition hover:text-sky-200">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Pendaftaran Publik
            </a>
        </div>
    </div>
</body>
</html>