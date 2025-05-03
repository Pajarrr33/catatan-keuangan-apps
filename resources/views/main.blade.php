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
            <h1 class="text-2xl font-bold text-center text-white">Financial records Apps</h1>
        </header>

        <!-- Balance Summary -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-4 md:hidden">
            <div class="mb-3">
                <h2 class="font-bold text-black">Balance </h2>
                <p class="text-2xl font-bold">Rp{{ number_format($balance, 0, ',', '.') }}</p>
            </div>
            <div class="flex justify-between border-t pt-3">
                <div>
                    <h3 class="font-semibold text-income">Income <i class="bi bi-arrow-up"></i></h3>
                    <p class="text-lg font-semibold text-income">Rp {{ number_format($income, 0, ',', '.') }}</p>
                </div>
                <div>
                    <h3 class="font-semibold text-expense">Expense <i class="bi bi-arrow-down"></i></h3>
                    <p class="text-lg font-semibold text-expense">- Rp {{ number_format($expense, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="hidden md:grid grid-cols-3 gap-4 mb-4">
            <div class="col-span-1 bg-white rounded-xl shadow-sm p-4">
                <h2 class="text-xl font-bold text-black">Balance </h2>
                <p class="text-xl font-bold">Rp {{ number_format($balance, 0, ',', '.') }}</p>
            </div>
            <div class="col-span-1 bg-white rounded-xl shadow-sm p-4">
                <h2 class="text-xl font-bold text-income">Income <i class="bi bi-arrow-up"></i></h2>
                <p class="text-xl font-bold text-income">Rp {{ number_format($income, 0, ',', '.') }}</p>
            </div>
            <div class="col-span-1 bg-white rounded-xl shadow-sm p-4">
                <h2 class="text-xl font-bold text-expense">Expense <i class="bi bi-arrow-down"></i></h2>
                <p class="text-xl font-bold text-expense">- Rp {{ number_format($expense, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Transaction List -->
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Transaction list</h2>
                <button class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium" id="open-modal" type="button">
                    <span class="hidden md:inline">Add Transaction</span>
                    <i class="bi bi-plus-lg md:ml-1"></i>
                </button>
            </div>
            @if ($transactions->isEmpty())
                <div class="w-full flex justify-center items-center">
                    <p class="text-gray-500">No transactions found.</p>
                </div>
            @else
               <!-- Filter/Sort Controls -->
            <form action="/" method="GET" id="filter-sort-form">
                <div class="grid grid-cols-2 gap-2 mb-4">
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort</label>
                        <select name="sort" class="w-full border rounded-lg p-2 text-sm" onchange="this.form.submit()">
                            @if (empty(request('sort')))
                                <option value="">Default</option>
                                <option value="smallest">Amount (Smallest)</option>
                                <option value="biggest">Amount (Biggest)</option>
                                <option value="latest">Date (Latest)</option>
                                <option value="oldest">Date (Oldest)</option>
                            @elseif (request('sort') == 'smallest')
                                <option value="smallest" selected>Amount (Smallest)</option>
                                <option value="">Default</option>
                                <option value="biggest">Amount (Biggest)</option>
                                <option value="latest">Date (Latest)</option>
                                <option value="oldest">Date (Oldest)</option>
                            @elseif (request('sort') == 'biggest')
                                <option value="biggest" selected>Amount (Biggest)</option>
                                <option value="">Default</option>
                                <option value="smallest">Amount (Smallest)</option>
                                <option value="latest">Date (Latest)</option>
                                <option value="oldest">Date (Oldest)</option>
                            @elseif (request('sort') == 'latest')
                                <option value="latest" selected>Date (Latest)</option>
                                <option value="">Default</option>
                                <option value="smallest">Amount (Smallest)</option>
                                <option value="biggest">Amount (Biggest)</option>
                                <option value="oldest">Date (Oldest)</option>
                            @elseif (request('sort') == 'oldest')
                                <option value="oldest" selected>Date (Oldest)</option>
                                <option value="">Default</option>
                                <option value="smallest">Amount (Smallest)</option>
                                <option value="biggest">Amount (Biggest)</option>
                                <option value="latest">Date (Latest)</option>
                            @endif
                        </select>
                    </div>
                    <div>
                        <label for="filter" class="block text-sm font-medium text-gray-700 mb-1">Filter</label>
                        <select name="type" class="w-full border rounded-lg p-2 text-sm" onchange="this.form.submit()">
                            @if (empty(request('type')))
                                <option value="">All Transaction</option>
                                <option value="Income">Income</option>
                                <option value="Expense">Expense</option>
                            @elseif (request('type') == 'Income')
                                <option value="Income" selected>Income</option>
                                <option value="">All Transaction</option>
                                <option value="Expense">Expense</option>
                            @elseif (request('type') == 'Expense')
                                <option value="Expense" selected>Expense</option>
                                <option value="">All Transaction</option>
                                <option value="Income">Income</option>
                            @endif
                        </select>
                    </div>
                </div>
            </form>
            
            <!-- Transactions -->
            <div class="space-y-3">
                @foreach ($transactions as $item )
                <div class="border-b pb-3">
                    <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($item->date)->format('l j F Y') }}</div>
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
                    <span class="text-xs px-2 py-1 rounded-full bg-income font-semibold text-white">Income</span>
                    @else
                    <span class="text-xs px-2 py-1 rounded-full bg-expense font-semibold text-white">Expense</span>
                    @endif
                </div>
                @endforeach
            </div>

            {{ $transactions->links('vendor.pagination.custom') }}
            @endif
        </div>
    </div>

    <!-- Add Transaction Modal (Hidden by default) -->
    <div class="fixed inset-0 bg-black/50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl p-6 w-full max-w-sm">
            <h3 class="text-lg font-bold mb-4">Add Transaction</h3>
            <form method="POST" action="/transactions" id="add-transaction">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Date</label>
                    <input type="date" name="date" class="w-full border rounded-lg p-2" value="{{ date('Y-m-d') }}">
                    @if ($errors->has('date'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('date') }}</p>
                    @endif
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Description</label>
                    <input type="text" name="description" class="w-full border rounded-lg p-2" placeholder="example: Gaji, Jajan">
                    @if ($errors->has('description'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('description') }}</p>
                    @endif
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Type</label>
                    <div class="flex gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="type" class="mr-2" value="Income" checked>
                            <span>Income</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="type" value="Expense" class="mr-2">
                            <span>Expense</span>
                        </label>
                        @if ($errors->has('type'))
                            <p class="text-red-500 text-xs mt-1">{{ $errors->first('type') }}</p>
                        @endif
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Amount (Rp)</label>
                    <input type="number" name="amount" class="w-full border rounded-lg p-2" placeholder="0">
                    @if ($errors->has('amount'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('amount') }}</p>
                    @endif
                </div>
                <div class="flex gap-2">
                    <button type="button" class="flex-1 border rounded-lg p-2" id="batal">Cancel</button>
                    <button type="submit" class="flex-1 bg-primary text-white rounded-lg p-2" id="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Simple interactivity for the modal
        document.addEventListener('DOMContentLoaded', function() {
            const addButton = document.getElementById('open-modal');
            const modal = document.querySelector('.fixed');
            const cancelButton = document.getElementById('batal');
            
            document.getElementById('add-transaction').addEventListener('submit', function() {
                document.getElementById('batal').disabled = true;
                document.getElementById('submit').disabled = true;
            })
            addButton.addEventListener('click', () => {
                modal.classList.remove('hidden');
            });
            
            cancelButton.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        });
    </script>
</body>
</html>