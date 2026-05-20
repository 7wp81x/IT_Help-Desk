<div id="emailVerificationModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm px-4 py-8">
    <div class="w-full max-w-2xl bg-white dark:bg-slate-950 rounded-[32px] shadow-2xl ring-1 ring-black/10 dark:ring-white/10 border border-gray-200/80 dark:border-slate-800 overflow-hidden">
        <div class="relative overflow-hidden px-8 py-7 bg-gradient-to-r from-yellow-500 via-orange-500 to-rose-500 text-white">
            <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_top_left,_rgba(255,255,255,0.45),_transparent_35%)]"></div>
            <div class="relative flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.24em] text-white/75 mb-2">Email verification</p>
                    <h2 class="text-2xl font-semibold">Enter your verification code</h2>
                    <p class="mt-2 text-sm text-white/85">Use the 6-digit code from your email to verify your account.</p>
                </div>
                <button type="button" onclick="closeVerifyModal()" class="rounded-full bg-white/15 p-3 text-white transition hover:bg-white/25">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
        </div>
        <div class="p-8">
            @if (session('success'))
                <div class="mb-4 rounded-2xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 p-4 text-sm text-green-700 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('info'))
                <div class="mb-4 rounded-2xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 p-4 text-sm text-blue-700 dark:text-blue-300">
                    {{ session('info') }}
                </div>
            @endif
            <form method="POST" action="{{ route('verification.verify-code') }}" id="verifyCodeModalForm">
                @csrf
                <input type="hidden" name="return_url" value="{{ url()->current() }}">
                <div class="mb-6">
                    <label for="modalVerificationCode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Verification Code</label>
                    <input
                        type="text"
                        id="modalVerificationCode"
                        name="code"
                        maxlength="6"
                        inputmode="numeric"
                        placeholder="000000"
                        class="w-full px-4 py-3 text-center text-2xl tracking-widest border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition"
                        value="{{ old('code') }}"
                        required
                        autofocus
                    >
                    @error('code')
                        <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <button type="submit" class="w-full sm:w-auto px-4 py-3 bg-yellow-600 hover:bg-yellow-700 text-white rounded-xl font-medium transition duration-200 flex items-center justify-center gap-2">
                        <i class="bi bi-check-lg"></i>
                        Verify
                    </button>
                    <button type="button" onclick="closeVerifyModal()" class="w-full sm:w-auto px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition duration-200">
                        Cancel
                    </button>
                </div>
            </form>
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Didn&rsquo;t receive the code?</p>
                <form method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-200 flex items-center justify-center gap-2">
                        <i class="bi bi-arrow-repeat"></i>
                        Request New Code
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
