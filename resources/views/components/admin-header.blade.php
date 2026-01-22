<header class="bg-white border-b border-slate-200 px-6 py-4 flex-shrink-0">
    <div class="flex items-center justify-between">
        <div>
            <nav class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                <span>Admin</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-slate-900 font-medium">@yield('breadcrumb', 'Dashboard')</span>
            </nav>
            <h2 class="text-2xl font-bold text-slate-900">@yield('page-title', 'Dashboard')</h2>
        </div>

        <div class="flex items-center gap-4">
        </div>
    </div>
</header>