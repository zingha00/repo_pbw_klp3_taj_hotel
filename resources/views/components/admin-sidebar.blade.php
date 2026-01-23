<aside id="sidebar" class="w-64 bg-slate-900 text-white flex-shrink-0 flex flex-col h-full overflow-y-auto">
    <!-- Logo -->
    <div class="p-5 border-b border-slate-700">
        <div class="flex items-center gap-3">
      <img 
    src="{{ asset('assets/images/grand-azure-logo-gold.png') }}" 
    alt="Logo Hotel"
    class="
        w-12 h-12 
        md:w-14 md:h-14
        object-contain 
        rounded-xl
        transition-transform
        duration-200
    "
/>


            <div>
                <h1 class="font-bold text-lg">{{ config('app.name', 'Grand Azure') }}</h1>
                <p class="text-xs text-slate-400">Admin Panel</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-3">
        <ul class="space-y-1">
            <li>
                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} w-full flex items-center gap-3 px-4 py-3 rounded-lg">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-amber-400' : 'text-slate-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.profile.index') }}"
                    class="sidebar-item {{ request()->routeIs('admin.profile') ? 'active' : '' }} w-full flex items-center gap-3 px-4 py-3 rounded-lg">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.profile') ? 'text-amber-400' : 'text-slate-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Profil Admin</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.rooms.index') }}"
                    class="sidebar-item {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }} w-full flex items-center gap-3 px-4 py-3 rounded-lg">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.rooms.*') ? 'text-amber-400' : 'text-slate-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                    <span>Manajemen Kamar</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.payments.index') }}"
                    class="sidebar-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }} w-full flex items-center gap-3 px-4 py-3 rounded-lg">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.payments.*') ? 'text-amber-400' : 'text-slate-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <span>Pembayaran</span>
                    @if($pendingPayments ?? 0 > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $pendingPayments }}</span>
                    @endif
                </a>
            </li>

            <li>
                <a href="{{ route('admin.bookings.index') }}"
                    class="sidebar-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }} w-full flex items-center gap-3 px-4 py-3 rounded-lg">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.bookings.*') ? 'text-amber-400' : 'text-slate-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Pemesanan</span>
                    @if($pendingBookings ?? 0 > 0)
                        <span
                            class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $pendingBookings }}</span>
                    @endif
                </a>
            </li>

            <li>
                <a href="{{ route('admin.users.index') }}"
                    class="sidebar-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }} w-full flex items-center gap-3 px-4 py-3 rounded-lg">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.users.*') ? 'text-amber-400' : 'text-slate-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Manajemen User</span>
                </a>
            </li>


            <li>
                <a href="{{ route('admin.reports.index') }}"
                    class="sidebar-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }} w-full flex items-center gap-3 px-4 py-3 rounded-lg">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.reports.*') ? 'text-amber-400' : 'text-slate-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Laporan</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- User Info -->
    <!-- User Info -->
<div class="p-4 border-t border-slate-700">
    <div class="flex items-center gap-3">
        <!-- Ganti div inisial dengan img avatar -->
        <img 
            src="{{ Auth::user()->avatar_url }}" 
            alt="{{ Auth::user()->name }}"
            class="w-10 h-10 rounded-full object-cover border-2 border-slate-600"
            onerror="this.onerror=null; this.src='{{ asset('assets/images/default-avatar.png') }}';"
        >
        <div class="flex-1 min-w-0">
            <p class="font-medium text-sm truncate">{{ Auth::user()->name ?? 'Admin Hotel' }}</p>
            <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email ?? 'admin@hotel.com' }}</p>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="p-2 hover:bg-slate-700 rounded-lg transition-colors" title="Logout">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </button>
        </form>
    </div>
</div>
</aside>