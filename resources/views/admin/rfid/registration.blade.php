<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RFID Registration Mode - Admin</title>
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
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 space-y-4 md:space-y-0">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">RFID Registration Mode</h1>
                <p class="mt-2 text-sm text-gray-600">Kelola pendaftaran kartu RFID baru dengan menghubungkannya ke
                    pengguna.</p>
            </div>

            <div class="flex items-center space-x-4">
                <form action="{{ route('admin.rfid.toggle-mode') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white transition-all transform hover:scale-105 active:scale-95 {{ $isRegistrationMode ? 'bg-red-600 hover:bg-red-700' : 'bg-indigo-600 hover:bg-indigo-700' }}">
                        <span class="mr-2">
                            @if($isRegistrationMode)
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 10a1 1 0 011-1v4a1 1 0 11-2 0V10a1 1 0 011-1zm6 0a1 1 0 011 1v4a1 1 0 11-2 0V10a1 1 0 011-1z">
                                    </path>
                                </svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                        </span>
                        {{ $isRegistrationMode ? 'Matikan Mode Registrasi' : 'Aktifkan Mode Registrasi' }}
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-xl bg-green-50 p-4 border border-green-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Status Card -->
        <div class="mb-10 bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
            <div class="px-6 py-6 sm:p-8 flex items-center">
                <div class="flex-shrink-0 p-3 rounded-full {{ $isRegistrationMode ? 'bg-red-100' : 'bg-gray-100' }}">
                    <div
                        class="w-4 h-4 rounded-full {{ $isRegistrationMode ? 'bg-red-500 animate-pulse' : 'bg-gray-400' }}">
                    </div>
                </div>
                <div class="ml-5">
                    <h3 class="text-lg leading-6 font-semibold text-gray-900">Status Scanner</h3>
                    <p class="text-sm text-gray-500">
                        {{ $isRegistrationMode ? 'Scanner saat ini sedang menunggu kartu baru untuk didaftarkan.' : 'Scanner sedang dalam mode pembayaran normal.' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Quick Add User Section -->
        <div class="mb-10 bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Tambah User Cepat</h3>
                <p class="text-sm text-gray-500">Gunakan formulir ini untuk menambah user baru yang belum ada di sistem.
                </p>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.user.store') }}" method="POST"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Nama
                            Lengkap</label>
                        <input type="text" name="name" required placeholder="Contoh: Ari Chii"
                            class="block w-full px-4 py-2 text-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg bg-gray-50">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Email</label>
                        <input type="email" name="email" required placeholder="ari@example.com"
                            class="block w-full px-4 py-2 text-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg bg-gray-50">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Saldo Awal
                            (Rp)</label>
                        <input type="number" name="balance" required value="0" min="0"
                            class="block w-full px-4 py-2 text-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg bg-gray-50">
                    </div>
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center px-6 py-2 border border-transparent text-sm font-bold rounded-lg shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 transition-all">
                        Simpan User
                    </button>
                </form>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-xl bg-red-50 p-4 border border-red-200">
                <ul class="list-disc ml-5 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Pending Cards Table -->
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg leading-6 font-bold text-gray-900 uppercase tracking-wider">Antrian Kartu Baru</h3>
                <span
                    class="px-3 py-1 text-xs font-bold rounded-full bg-indigo-100 text-indigo-700">{{ $pendingCards->count() }}
                    Pending</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">UID
                                Kartu</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Scan Terakhir</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Hubungkan ke User</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($pendingCards as $card)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <code
                                        class="text-sm font-mono font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded">{{ $card->rfid_uid }}</code>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $card->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('admin.rfid.assign', $card) }}" method="POST"
                                        class="flex items-center space-x-2">
                                        @csrf
                                        <div class="relative">
                                            <select name="user_id" required
                                                class="block w-64 pl-3 pr-10 py-2 text-sm border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm bg-white">
                                                <option value="">Pilih User...</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}
                                                        (Rp{{ number_format($user->balance, 0, ',', '.') }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-semibold rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                                            Assign
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <p class="text-lg font-medium">Tidak ada kartu baru terdeteksi.</p>
                                        <p class="text-sm">Aktifkan mode registrasi dan scan kartu di alat.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>