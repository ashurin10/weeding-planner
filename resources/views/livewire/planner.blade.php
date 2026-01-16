<div class="space-y-8 max-w-4xl mx-auto">
    <!-- Header / Progress -->
    <div class="flex flex-col items-center space-y-4">
        <h2 class="text-4xl font-serif text-stone-800 tracking-wide text-center">Wedding Checklist</h2>
        <div class="flex items-center gap-4 text-sm text-stone-500 font-medium tracking-widest uppercase">
            <span>Progress: {{ $progress }}%</span>
            <span class="w-1 h-1 bg-stone-300 rounded-full"></span>
            <span>Total: Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Progress Bar Visual -->
    <div class="w-full bg-stone-200 h-1 mb-10 max-w-lg mx-auto rounded-full overflow-hidden">
        <div class="bg-rose-400 h-1 rounded-full transition-all duration-700 ease-in-out" style="width: {{ $progress }}%"></div>
    </div>

    <!-- Categories -->
    <div class="grid gap-10">
        @foreach($categories as $category)
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-stone-100 overflow-hidden">
                <!-- Category Header -->
                <div class="px-6 py-4 flex justify-between items-center bg-stone-50/50">
                    <input type="text"
                           wire:change="updateCategory({{ $category->id }}, $event.target.value)"
                           wire:keydown.enter="$refresh"
                           value="{{ $category->title }}"
                           class="bg-transparent border-none font-serif text-2xl text-stone-700 w-full focus:ring-0 placeholder-stone-300" 
                           placeholder="Category Name" />
                    <button wire:click="deleteCategory({{ $category->id }})" 
                            wire:confirm="Are you sure you want to delete this category?"
                            class="text-stone-300 hover:text-rose-400 transition-colors p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <!-- Items List -->
                <div class="p-4 sm:p-6">
                    <ul class="space-y-3">
                        @foreach($category->items as $item)
                        <li class="group flex flex-col sm:flex-row sm:items-center gap-3 p-3 rounded-lg hover:bg-stone-50 transition-colors {{ $item->is_completed ? 'opacity-60' : '' }}">
                                <!-- Checkbox -->
                                <div class="flex items-center gap-3">
                                    <input type="checkbox"
                                           wire:click="toggleItem({{ $item->id }})"
                                           {{ $item->is_completed ? 'checked' : '' }}
                                           class="rounded-full text-rose-400 focus:ring-rose-300 w-6 h-6 cursor-pointer border-stone-300" />
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0 grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
                                    <div class="col-span-12 sm:col-span-8">
                                        @if($editingItemId === $item->id)
                                            <div class="space-y-2">
                                                <input type="text"
                                                       wire:blur="updateItem({{ $item->id }}, 'name', $event.target.value)"
                                                       wire:keydown.enter="toggleEdit({{ $item->id }})"
                                                       value="{{ $item->name }}"
                                                       class="w-full bg-white border border-stone-200 rounded px-3 py-2 text-base text-stone-700 focus:ring-rose-200 focus:border-rose-300 shadow-sm"
                                                       placeholder="Item Name" autofocus />
                                                <input type="text"
                                                       wire:blur="updateItem({{ $item->id }}, 'link', $event.target.value)"
                                                       wire:keydown.enter="toggleEdit({{ $item->id }})"
                                                       value="{{ $item->link }}"
                                                       class="w-full bg-white border border-stone-200 rounded px-3 py-2 text-sm text-stone-500 focus:ring-rose-200 focus:border-rose-300 shadow-sm"
                                                       placeholder="Purchase Link URL..." />
                                            </div>
                                        @else
                                            <div class="flex flex-col">
                                                <span class="text-base text-stone-700 font-medium {{ $item->is_completed ? 'line-through decoration-stone-400' : '' }}">
                                                    {{ $item->name }}
                                                </span>
                                                @if($item->link)
                                                    <a href="{{ $item->link }}" target="_blank" class="text-xs text-rose-400 hover:text-rose-600 hover:underline flex items-center gap-1 mt-0.5">
                                                        Visit Link &rarr;
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Price -->
                                    <div class="col-span-12 sm:col-span-4 text-left sm:text-right">
                                        @if($editingItemId === $item->id)
                                             <input type="text"
                                                   wire:blur="updateItem({{ $item->id }}, 'price', $event.target.value)"
                                                   wire:keydown.enter="toggleEdit({{ $item->id }})"
                                                   value="{{ $item->price }}"
                                                   class="w-full sm:w-32 bg-white border border-stone-200 rounded px-3 py-2 text-sm text-right font-mono focus:ring-rose-200 focus:border-rose-300 shadow-sm" />
                                        @else
                                            <span class="text-sm text-stone-500 font-mono tracking-tight">
                                                {{ $item->price }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex justify-end sm:opacity-0 group-hover:opacity-100 transition-opacity gap-2 min-w-[50px]">
                                    @if($editingItemId === $item->id)
                                        <button wire:click="toggleEdit({{ $item->id }})" class="text-emerald-500 hover:bg-emerald-50 p-1.5 rounded-full transition-colors" title="Save">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    @else
                                        <button wire:click="toggleEdit({{ $item->id }})" class="text-stone-400 hover:text-stone-600 hover:bg-stone-100 p-1.5 rounded-full transition-colors" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </button>
                                    @endif
                                    
                                    <button wire:click="deleteItem({{ $item->id }})" class="text-stone-300 hover:text-rose-500 hover:bg-rose-50 p-1.5 rounded-full transition-colors" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                        </li>
                        @endforeach
                    </ul>
                    
                     <!-- Subtotal -->
                     <div class="mt-6 pt-4 border-t border-stone-100 flex justify-between items-center text-sm text-stone-500">
                        <span class="font-medium uppercase tracking-wider text-xs">Subtotal</span>
                        <span class="font-mono">{{ number_format($category->items->sum(fn($i) => $i->getRawOriginal('price')), 0, ',', '.') }}</span>
                    </div>

                    <div class="mt-4">
                         <button wire:click="addItem({{ $category->id }})" class="w-full py-2.5 border border-dashed border-stone-300 rounded-lg text-sm text-stone-500 hover:text-rose-500 hover:border-rose-300 hover:bg-rose-50/30 transition-all flex justify-center items-center gap-2">
                            <span>Add Item</span>
                         </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Add Category Floating Button or Bottom -->
    <div class="mt-12 text-center pb-20">
        <button wire:click="addCategory" class="px-8 py-3 bg-stone-800 text-stone-50 font-serif text-lg rounded-full shadow-lg hover:bg-stone-700 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            + New Category
        </button>
    </div>
</div>
