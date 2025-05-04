<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite('resources/css/app.css')
    <title>Catatan keuangan pribadi apps</title>
</head>
<body class="custom-bg text-gray-800">
    <div class="max-w-md mx-auto p-4 md:max-w-4xl md:p-6 lg:max-w-6xl lg:p-8 xl:max-w-8xl xl:p-10">
        <!-- Header -->
        <header class="mb-6">
            <h1 class="text-2xl font-bold text-center text-white">Catatan Keuangan Apps</h1>
        </header>

        <!-- Balance Summary -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-4 md:hidden">
            <div class="mb-3">
                <h2 class="font-bold text-black">Saldo Anda </h2>
                <p class="text-2xl font-bold">Rp{{ number_format($balance, 0, ',', '.') }}</p>
            </div>
            <div class="flex justify-between border-t pt-3">
                <div>
                    <h3 class="font-semibold text-income">Pendapatan <i class="bi bi-arrow-up"></i></h3>
                    <p class="text-lg font-semibold text-income">Rp {{ number_format($income, 0, ',', '.') }}</p>
                </div>
                <div>
                    <h3 class="font-semibold text-expense">Pengeluaran <i class="bi bi-arrow-down"></i></h3>
                    <p class="text-lg font-semibold text-expense">- Rp {{ number_format($expense, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="hidden md:grid grid-cols-3 gap-4 mb-4">
            <div class="col-span-1 bg-white rounded-xl shadow-sm p-4">
                <h2 class="text-xl font-bold text-black">Saldo Anda </h2>
                <p class="text-xl font-bold">Rp {{ number_format($balance, 0, ',', '.') }}</p>
            </div>
            <div class="col-span-1 bg-white rounded-xl shadow-sm p-4">
                <h2 class="text-xl font-bold text-income">Pendapatan <i class="bi bi-arrow-up"></i></h2>
                <p class="text-xl font-bold text-income">Rp {{ number_format($income, 0, ',', '.') }}</p>
            </div>
            <div class="col-span-1 bg-white rounded-xl shadow-sm p-4">
                <h2 class="text-xl font-bold text-expense">Pengeluaran <i class="bi bi-arrow-down"></i></h2>
                <p class="text-xl font-bold text-expense">- Rp {{ number_format($expense, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Transaction List -->
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Daftar transaksi</h2>
                <button class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium" id="add-modal-toggle" type="button">
                    <span class="hidden md:inline">Tambah Transaksi</span>
                    <i class="bi bi-plus-lg md:ml-1"></i>
                </button>
            </div>
            @if ($transactions->isEmpty())
                <div class="w-full flex justify-center items-center">
                    <p class="text-gray-500">Tidak ada transaksi</p>
                </div>
            @else
               <!-- Filter/Sort Controls -->
            <form action="/" method="GET" id="filter-sort-form">
                <div class="grid grid-cols-2 gap-2 mb-4">
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Urutkan</label>
                        <select name="sort" class="w-full border rounded-lg p-2 text-sm" onchange="this.form.submit()">
                            @if (empty(request('sort')))
                                <option value="">Nominal dan tanggal</option>
                                <option value="smallest">Nominal (Terkecil)</option>
                                <option value="biggest">Nominal (Terbesar)</option>
                                <option value="latest">Tanggal (Terbaru)</option>
                                <option value="oldest">Tanggal (Terlama)</option>
                            @elseif (request('sort') == 'smallest')
                                <option value="smallest" selected>Nominal (Terkecil)</option>
                                <option value="">Nominal dan tanggal</option>
                                <option value="biggest">Nominal (Terbesar)</option>
                                <option value="latest">Tanggal (Terbaru)</option>
                                <option value="oldest">Tanggal (Terlama)</option>
                            @elseif (request('sort') == 'biggest')
                                <option value="biggest" selected>Nominal (Terbesar)</option>
                                <option value="">Nominal dan tanggal</option>
                                <option value="smallest">Nominal (Terkecil)</option>
                                <option value="latest">Tanggal (Terbaru)</option>
                                <option value="oldest">Tanggal (Terlama)</option>
                            @elseif (request('sort') == 'latest')
                                <option value="latest" selected>Tanggal (Terbaru)</option>
                                <option value="">Nominal dan tanggal</option>
                                <option value="smallest">Nominal (Terkecil)</option>
                                <option value="biggest">Nominal (Terbesar)</option>
                                <option value="oldest">Tanggal (Terlama)</option>
                            @elseif (request('sort') == 'oldest')
                                <option value="oldest" selected>Tanggal (Terlama)</option>
                                <option value="">Nominal dan tanggal</option>
                                <option value="smallest">Nominal (Terkecil)</option>
                                <option value="biggest">Nominal (Terbesar)</option>
                                <option value="latest">Tanggal (Terbaru)</option>
                            @endif
                        </select>
                    </div>
                    <div>
                        <label for="filter" class="block text-sm font-medium text-gray-700 mb-1">Filter</label>
                        <select name="type" class="w-full border rounded-lg p-2 text-sm" onchange="this.form.submit()">
                            @if (empty(request('type')))
                                <option value="">Semua transaski</option>
                                <option value="Income">Pendapatan</option>
                                <option value="Expense">Pengeluaran</option>
                            @elseif (request('type') == 'Income')
                                <option value="Income" selected>Pendapatan</option>
                                <option value="">Semua transaski</option>
                                <option value="Expense">Pengeluaran</option>
                            @elseif (request('type') == 'Expense')
                                <option value="Expense" selected>Pengeluaran</option>
                                <option value="">Semua transaski</option>
                                <option value="Income">Pendapatan</option>
                            @endif
                        </select>
                    </div>
                </div>
            </form>
            
            <!-- Transactions -->
            <div class="space-y-3">
                @foreach ($transactions as $item )
                <div class="border-b pb-3">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($item->date)->translatedFormat('l, d F Y') }}</div>
                        
                        <div class="relative inline-block text-left">
                            <button type="button" class="font-semibold p-2 rounded hover:bg-gray-100 dropdown-toggle" data-transaction-id="{{ $item->id }}"><i class="bi bi-list"></i></button>

                            <div class="hidden z-10 absolute right-0  bg-white divide-y divide-gray-100 rounded-lg shadow w-36 dropdown-menu" data-target-id="{{ $item->id }}" id="dropdown-menu">
                                <ul class="py-1 text-sm text-gray-700" aria-labelledby="dropdownButton-{{ $item->id }}">
                                    <li><button type="button" class="block px-2 py-1 hover:bg-gray-100 w-full text-left" data-modal-toggle="update-modal-{{ $item->id }}">Edit</button></li>
                                    <li>
                                        <form action="/transactions/{{ $item->id }}" method="POST" onsubmit="return confirm('Apakah anda yakin ingin menghapus transaksi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="block px-2 py-1 hover:bg-gray-100 w-full text-left">Delete</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-semibold">{{ $item->description }}</h3>
                        </div>
                        @if ($item->type == 'Income')
                            <p class="font-semibold text-income">+ Rp {{ number_format($item->amount, 0, ',', '.') }}</p>
                        @else
                            <p class="font-semibold text-expense">- Rp {{ number_format($item->amount, 0, ',', '.') }}</p>
                        @endif
                    </div>
                    @if ($item->type == 'Income')
                    <span class="text-xs px-2 py-1 rounded-full bg-income font-semibold text-white">Pendapatan</span>
                    @else
                    <span class="text-xs px-2 py-1 rounded-full bg-expense font-semibold text-white">Pengeluaran</span>
                    @endif
                </div>
                @endforeach
            </div>

            {{ $transactions->links('vendor.pagination.custom') }}
            @endif
        </div>
    </div>

    <!-- Add Transaction Modal (Hidden by default) -->
    <div class="fixed inset-0 bg-black/50 hidden flex items-center justify-center p-4" id="add-modal">
        <div class="bg-white rounded-xl p-6 w-full max-w-sm">
            <h3 class="text-lg font-bold mb-4">Tambah Transaksi</h3>
            <form method="POST" action="/transactions" id="add-transaction">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Tanggal</label>
                    <input type="date" name="date" class="w-full border rounded-lg p-2" value="{{ date('Y-m-d') }}">
                    @if ($errors->has('date'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('date') }}</p>
                    @endif
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Dekripsi</label>
                    <input type="text" name="description" class="w-full border rounded-lg p-2" placeholder="Misal: Gaji, Jajan">
                    @if ($errors->has('description'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('description') }}</p>
                    @endif
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Tipe</label>
                    <div class="flex gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="type" class="mr-2" value="Income" checked>
                            <span>Pendapatan</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="type" value="Expense" class="mr-2">
                            <span>Pengeluaran</span>
                        </label>
                        @if ($errors->has('type'))
                            <p class="text-red-500 text-xs mt-1">{{ $errors->first('type') }}</p>
                        @endif
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Jumlah (Rp)</label>
                    <input type="number" name="amount" class="w-full border rounded-lg p-2" placeholder="0">
                    @if ($errors->has('amount'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('amount') }}</p>
                    @endif
                </div>
                <div class="flex gap-2">
                    <button type="button" class="flex-1 border rounded-lg p-2" id="batal">Batal</button>
                    <button type="submit" class="flex-1 bg-primary text-white rounded-lg p-2" id="submit">Kirim</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Update Transaction Modal (Hidden by default) -->
    @foreach ($transactions as $item)
    <div class="fixed inset-0 bg-black/50 hidden flex items-center justify-center p-4" id="update-modal-{{ $item->id }}">
        <div class="bg-white rounded-xl p-6 w-full max-w-sm">
            <h3 class="text-lg font-bold mb-4">Update Transaksi</h3>
            <form method="POST" action="/transactions/{{ $item->id }}" id="update-transaction-{{ $item->id }}">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Tanggal</label>
                    <input type="date" name="date" class="w-full border rounded-lg p-2" value="{{ $item->date }}">
                    @if ($errors->has('date'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('date') }}</p>
                    @endif
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Dekripsi</label>
                    <input type="text" name="description" class="w-full border rounded-lg p-2" value="{{ $item->description }}" placeholder="Misal: Gaji, Jajan">
                    @if ($errors->has('description'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('description') }}</p>
                    @endif
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Tipe</label>
                    <div class="flex gap-4">
                        @if ($item->type == "Income")
                            <label class="flex items-center">
                                <input type="radio" name="type" class="mr-2" value="Income" checked>
                                <span>Pendapatan</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="type" value="Expense" class="mr-2">
                                <span>Pengeluaran</span>
                            </label>
                        @else
                        <label class="flex items-center">
                            <input type="radio" name="type" class="mr-2" value="Income">
                            <span>Pendapatan</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="type" value="Expense" class="mr-2" checked>
                            <span>Pengeluaran</span>
                        </label>
                        @endif
                        
                        @if ($errors->has('type'))
                            <p class="text-red-500 text-xs mt-1">{{ $errors->first('type') }}</p>
                        @endif
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Jumlah (Rp)</label>
                    <input type="number" name="amount" class="w-full border rounded-lg p-2" value="{{ $item->amount }}" placeholder="0">
                    @if ($errors->has('amount'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('amount') }}</p>
                    @endif
                </div>
                <div class="flex gap-2">
                    <button type="button" class="flex-1 border rounded-lg p-2" data-modal-cancel="update-modal-{{ $item->id }}">Batal</button>
                    <button type="submit" class="flex-1 bg-primary text-white rounded-lg p-2" data-model-submit="update-modal-{{ $item->id }}">Kirim</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach
    


    <script>
        // Simple interactivity for the modal
        document.addEventListener('DOMContentLoaded', function() {
            const addButton = document.getElementById('add-modal-toggle');
            const modal = document.getElementById('add-modal');
            const cancelButton = document.getElementById('batal');
            
            document.getElementById('add-transaction').addEventListener('submit', function() {
                document.getElementById('batal').disabled = true;
                document.getElementById('submit').disabled = true;
            })

            addButton.addEventListener('click', () => {
                document.querySelectorAll('.dropdown-menu:not(.hidden)').forEach(menu => {
                    menu.classList.add('hidden');
                });
                modal.classList.remove('hidden');
            });
            
            cancelButton.addEventListener('click', () => {
                modal.classList.add('hidden');
            });

            document.querySelectorAll('form[id^="update-transaction-"]').forEach(form => {
                form.addEventListener('submit', function(e) {
                    const modalId = this.closest('[id^="update-modal-"]')?.id;

                    if (!modalId) return;

                    // Find the submit and cancel buttons using data attributes
                    const submitBtn = document.querySelector(`[data-model-submit="${modalId}"]`);
                    const cancelBtn = document.querySelector(`[data-modal-cancel="${modalId}"]`);

                    if (submitBtn) submitBtn.disabled = true;
                    if (cancelBtn) cancelBtn.disabled = true;
                });
            });

            // Single event listener for all dropdowns
            document.addEventListener('click', function(e) {
                // Handle dropdown toggles
                if (e.target.closest('.dropdown-toggle')) {
                    const button = e.target.closest('.dropdown-toggle');
                    const transactionId = button.dataset.transactionId;
                    const dropdown = document.querySelector(`.dropdown-menu[data-target-id="${transactionId}"]`);
                    
                    // Close all other open dropdowns first
                    document.querySelectorAll('.dropdown-menu:not([hidden])').forEach(menu => {
                        if (menu !== dropdown) menu.classList.add('hidden');
                    });
                    
                    // Toggle current dropdown
                    dropdown?.classList.toggle('hidden');
                }
                
                // Handle modal toggles
                if (e.target.closest('[data-modal-toggle]')) {
                    const modalId = e.target.closest('[data-modal-toggle]').dataset.modalToggle;

                    // Close all dropdowns when opening a modal
                    document.querySelectorAll('.dropdown-menu:not(.hidden)').forEach(menu => {
                        menu.classList.add('hidden');
                    });

                    document.getElementById(modalId)?.classList.remove('hidden');
                }
                
                // Handle cancel buttons 
                if (e.target.closest('[data-modal-cancel]')) {
                    const modalId = e.target.closest('[data-modal-cancel]').dataset.modalCancel;
                    document.getElementById(modalId)?.classList.add('hidden');
                }
                
                // Close modal when clicking outside
                if (e.target.classList.contains('fixed')) {
                    e.target.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>