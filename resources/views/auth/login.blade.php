<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <div class="space-y-10">
        <div>
            <p class="text-sm uppercase tracking-[0.3em] text-white/60">Welcome Back</p>
            <h1 class="mt-3 text-3xl font-semibold">Sign in to eBASA</h1>
            <p class="mt-1 text-sm text-white/70">Centralized access to every BASA module and branch.</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div class="space-y-2">
                <label for="email" class="text-sm font-medium text-white/80">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    class="w-full rounded-2xl border border-white/15 bg-white/5 px-4 py-3 text-base text-white placeholder-white/30 focus:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-300/50" />
                <x-input-error :messages="$errors->get('email')" class="text-sm text-rose-300" />
            </div>

            <div class="space-y-2">
                <label for="password" class="text-sm font-medium text-white/80">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="w-full rounded-2xl border border-white/15 bg-white/5 px-4 py-3 text-base text-white placeholder-white/30 focus:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-300/50" />
                <x-input-error :messages="$errors->get('password')" class="text-sm text-rose-300" />
            </div>

            <div class="flex items-center justify-between text-sm text-white/70">
                <label for="remember_me" class="inline-flex items-center gap-2">
                    <input id="remember_me" type="checkbox" class="rounded border-white/30 bg-transparent text-amber-300 focus:ring-amber-300" name="remember">
                    <span>Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-amber-200 hover:text-amber-100" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>

            <button type="submit"
                class="w-full rounded-2xl bg-amber-300 py-3 text-base font-semibold text-slate-950 shadow-lg shadow-amber-300/30 transition hover:bg-amber-200">
                Log in
            </button>
        </form>
    </div>
</x-guest-layout>
