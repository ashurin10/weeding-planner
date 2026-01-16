<div class="space-y-6">
    <!-- Header / Progress -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
        <div>
           <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Wedding Checklist</h2>
           <p class="text-sm text-gray-500 dark:text-gray-400">Progress: {{ $progress }}%</p>
        </div>
        <div class="text-3xl font-extrabold text-pink-600">
            Total: Rp {{ number_format($grandTotal, 0, ',', '.') }}
        </div>
    </div>

    <!-- Progress Bar Visual -->
    <div class="w-full bg-gray-200 rounded-full h-3 dark:bg-gray-700 mb-6 overflow-hidden">
        <div class="bg-pink-600 h-3 rounded-full transition-all duration-500 ease-in-out" style="width: {{ $progress }}%"></div>
    </div>

    <!-- Categories -->
    <div class="grid gap-6">
        @foreach($categories as $category)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-100 dark:border-gray-700">
                <!-- Category Header -->
                <div class="bg-gradient-to-r from-pink-50 to-white dark:from-pink-900/30 dark:to-gray-800 px-4 py-3 flex justify-between items-center border-b border-gray-100 dark:border-gray-700">
                    <input type="text"
                           wire:change="updateCategory({{ $category->id }}, $event.target.value)"
                           value="{{ $category->title }}"
                           class="bg-transparent border-none font-bold text-lg focus:ring-0 text-gray-800 dark:text-gray-100 w-full hover:bg-white/50 dark:hover:bg-gray-700/50 rounded transition px-2" 
                           placeholder="Category Name" />
                    <button wire:click="deleteCategory({{ $category->id }})" 
                            wire:confirm="Are you sure you want to delete this category?"
                            class="text-gray-400 hover:text-red-500 transition-colors p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <!-- Items Table -->
                <div class="p-0">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-3 w-10">Done</th>
                                <th class="px-4 py-3">Item</th>
                                <th class="px-4 py-3 text-right w-40">Price</th>
                                <th class="px-4 py-3 w-10"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @php $subtotal = 0; @endphp
                            @foreach($category->items as $item)
                                @php
                                    $subtotal += $item->getRawOriginal('price');
                                @endphp
                                <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ $item->is_completed ? 'bg-green-50/50 dark:bg-green-900/10' : '' }}">
                                    <td class="px-4 py-2 text-center">
                                        <input type="checkbox"
                                               wire:click="toggleItem({{ $item->id }})"
                                               {{ $item->is_completed ? 'checked' : '' }}
                                               class="rounded text-pink-600 focus:ring-pink-500 w-5 h-5 cursor-pointer border-gray-300 dark:border-gray-600" />
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="text"
                                               wire:blur="updateItem({{ $item->id }}, 'name', $event.target.value)"
                                               value="{{ $item->name }}"
                                               class="w-full bg-transparent border-none focus:ring-0 p-0 text-sm text-gray-800 dark:text-gray-200 {{ $item->is_completed ? 'line-through text-gray-400' : '' }}" />
                                    </td>
                                    <td class="px-4 py-2 text-right">
                                        <input type="text"
                                               wire:blur="updateItem({{ $item->id }}, 'price', $event.target.value)"
                                               value="{{ $item->price }}"
                                               class="w-full bg-transparent border-none focus:ring-0 p-0 text-sm text-right text-gray-800 dark:text-gray-200 font-mono" />
                                    </td>
                                    <td class="px-4 py-2 text-right opacity-0 group-hover:opacity-100 transition-opacity">
                                         <button wire:click="deleteItem({{ $item->id }})" class="text-gray-400 hover:text-red-500 transition-colors">
                                            &times;
                                         </button>
                                    </td>
                                </tr>
                            @endforeach
                            <!-- Subtotal Row -->
                             <tr class="bg-gray-50 dark:bg-gray-800/80 font-semibold text-sm text-gray-700 dark:text-gray-300">
                                <td colspan="2" class="px-4 py-3 text-right">Subtotal</td>
                                <td class="px-4 py-3 text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-700">
                         <button wire:click="addItem({{ $category->id }})" class="w-full py-2 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-500 hover:text-pink-600 hover:border-pink-300 transition-colors flex justify-center items-center gap-2">
                            <span>+ Add Item</span>
                         </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Add Category Floating Button or Bottom -->
    <div class="mt-8 text-center pb-12">
        <button wire:click="addCategory" class="group relative inline-flex items-center justify-center px-8 py-3 text-base font-medium text-white transition-all duration-200 bg-pink-600 rounded-full shadow-lg hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
            <span class="mr-2 text-xl">+</span> Add Category
        </button>
    </div>
</div>
