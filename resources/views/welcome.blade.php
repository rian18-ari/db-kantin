<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - RFID Kantin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
        }

        .stat-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>

<body class="bg-[#F8FAFC] text-slate-900">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-900 text-white hidden md:flex flex-col">
            <div class="p-6">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight">KantinPay</span>
                </div>
            </div>

            <nav class="flex-1 px-4 py-4 space-y-2">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl bg-indigo-600 text-white font-medium transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.rfid.registration') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all group">
                    <svg class="w-5 h-5 group-hover:text-indigo-400 transition-colors" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                        </path>
                    </svg>
                    <span>Registrasi RFID</span>
                </a>
            </nav>

            <div class="p-6 border-t border-slate-800">
                <p class="text-xs text-slate-500 font-medium uppercase tracking-widest">v1.2.0 Stable</p>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 md:p-10">
            <!-- Header -->
            <header class="flex flex-col md:flex-row md:items-center md:justify-between mb-10 gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Ringkasan Sistem</h2>
                    <p class="text-slate-500 mt-1">Selamat datang kembali di panel administrasi KantinPay.</p>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.rfid.registration') }}"
                        class="group inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-2xl shadow-lg shadow-indigo-600/20 transition-all hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Daftarkan Kartu Baru
                    </a>
                </div>
            </header>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <!-- Total User -->
                <div
                    class="stat-card bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center space-x-4">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354l1.102 1.102a.5.5 0 00.707 0L15.414 4a2 2 0 112.828 2.828l-1.102 1.102a.5.5 0 000 .707l7.071 7.071a2 2 0 11-2.828 2.828l-7.071-7.071a.5.5 0 00-.707 0l-1.102 1.102a2 2 0 11-2.828-2.828l1.102-1.102a.5.5 0 000-.707L4 4a2 2 0 112.828-2.828l1.102 1.102a.5.5 0 00.707 0l1.102-1.102z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-500">Total User</p>
                        <h4 class="text-2xl font-bold mt-1">{{ number_format($stats['total_users']) }}</h4>
                    </div>
                </div>

                <!-- Kartu Aktif -->
                <div
                    class="stat-card bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center space-x-4">
                    <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-500">Kartu Aktif</p>
                        <h4 class="text-2xl font-bold mt-1 text-indigo-600">{{ number_format($stats['total_cards']) }}
                        </h4>
                    </div>
                </div>

                <!-- Total Saldo -->
                <div
                    class="stat-card bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center space-x-4">
                    <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-500">Total Saldo Terpasang</p>
                        <h4 class="text-2xl font-bold mt-1 text-emerald-600">Rp
                            {{ number_format($stats['total_balance'], 0, ',', '.') }}</h4>
                    </div>
                </div>

                <!-- Transaksi Hari Ini -->
                <div
                    class="stat-card bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center space-x-4">
                    <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-500">Transaksi Hari Ini</p>
                        <h4 class="text-2xl font-bold mt-1 text-amber-600">
                            {{ number_format($stats['today_transactions']) }}</h4>
                    </div>
                </div>
            </div>

            <!-- Dashboard Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Recent Transactions -->
                <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-slate-900">Transaksi Terbaru</h3>
                        <span
                            class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full uppercase tracking-wider">Live
                            Updates</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th
                                        class="px-8 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest text-center">
                                        User</th>
                                    <th
                                        class="px-8 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">
                                        Tipe</th>
                                    <th
                                        class="px-8 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest text-right">
                                        Jumlah</th>
                                    <th
                                        class="px-8 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest text-right">
                                        Waktu</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($stats['recent_transactions'] as $tx)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-8 py-4">
                                            <div class="flex items-center space-x-3">
                                                <div
                                                    class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-xs font-bold text-slate-600">
                                                    {{ strtoupper(substr($tx->user->name, 0, 1)) }}
                                                </div>
                                                <span class="font-semibold text-slate-700">{{ $tx->user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-4">
                                            @if($tx->type === 'payment')
                                                <span
                                                    class="px-3 py-1 text-[11px] font-bold bg-amber-50 text-amber-600 rounded-lg uppercase tracking-wider">Pembayaran</span>
                                            @else
                                                <span
                                                    class="px-3 py-1 text-[11px] font-bold bg-emerald-50 text-emerald-600 rounded-lg uppercase tracking-wider">Top
                                                    Up</span>
                                            @endif
                                        </td>
                                        <td
                                            class="px-8 py-4 text-right font-bold {{ $tx->type === 'payment' ? 'text-slate-900' : 'text-emerald-600' }}">
                                            {{ $tx->type === 'payment' ? '-' : '+' }}
                                            Rp{{ number_format($tx->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-8 py-4 text-right text-sm text-slate-400">
                                            {{ $tx->created_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-8 py-12 text-center text-slate-400">Belum ada transaksi
                                            terekam.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Registration Mode Tooltip/Card -->
                <div class="space-y-6">
                    <div
                        class="bg-gradient-to-br from-indigo-600 to-indigo-800 p-8 rounded-3xl text-white shadow-xl shadow-indigo-600/20 relative overflow-hidden group">
                        <div
                            class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-all duration-700">
                        </div>
                        <h3 class="text-xl font-bold mb-3 relative z-10">Mode Registrasi</h3>
                        <p class="text-indigo-100 mb-6 text-sm leading-relaxed relative z-10">Ada
                            <strong>{{ $stats['pending_cards'] }} kartu</strong> baru yang menunggu untuk dipasangkan ke
                            user.</p>
                        <a href="{{ route('admin.rfid.registration') }}"
                            class="inline-flex items-center justify-center w-full py-4 bg-white text-indigo-600 font-bold rounded-2xl hover:bg-indigo-50 transition-all shadow-lg active:scale-95 relative z-10">
                            Kelola Pendaftaran
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>

                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                        <h3 class="font-bold text-slate-900 mb-4">Butuh Bantuan?</h3>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 text-xs mt-0.5 font-bold">
                                    1</div>
                                <p class="text-sm text-slate-500">Scan kartu di terminal fisik saat mode registrasi
                                    menyala.</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 text-xs mt-0.5 font-bold">
                                    2</div>
                                <p class="text-sm text-slate-500">Pilih user dari daftar yang belum memiliki kartu.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>