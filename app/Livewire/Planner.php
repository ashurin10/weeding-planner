<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Planner extends Component
{
    public $grandTotal = 0;
    public $progress = 0;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function render()
    {
        $categories = Auth::user()
            ->categories()
            ->with([
                'items' => function ($query) {
                    $query->orderBy('id');
                }
            ])
            ->orderBy('order')
            ->get();

        $this->calculateTotals($categories);

        return view('livewire.planner', [
            'categories' => $categories,
        ]);
    }

    public function calculateTotals($categories)
    {
        $totalItems = 0;
        $completedItems = 0;
        $this->grandTotal = 0;

        foreach ($categories as $category) {
            foreach ($category->items as $item) {
                $this->grandTotal += $item->getRawOriginal('price'); // Use raw value for math
                $totalItems++;
                if ($item->is_completed) {
                    $completedItems++;
                }
            }
        }

        $this->progress = $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;
    }

    public function addCategory()
    {
        Auth::user()->categories()->create([
            'title' => 'New Category',
            'order' => Auth::user()->categories()->max('order') + 1,
        ]);
    }

    public function updateCategory($id, $title)
    {
        $category = Auth::user()->categories()->find($id);
        if ($category) {
            $category->update(['title' => $title]);
        }
    }

    public function deleteCategory($id)
    {
        Auth::user()->categories()->find($id)?->delete();
    }

    public function addItem($categoryId)
    {
        $category = Auth::user()->categories()->find($categoryId);
        if ($category) {
            $category->items()->create([
                'name' => 'New Item',
                'price' => 0,
            ]);
        }
    }

    public function updateItem($itemId, $field, $value)
    {
        $item = Item::whereHas('category', function ($query) {
            $query->where('user_id', Auth::id());
        })->find($itemId);

        if ($item) {
            $item->update([$field => $value]);
        }
    }

    public function deleteItem($itemId)
    {
        Item::whereHas('category', function ($query) {
            $query->where('user_id', Auth::id());
        })->find($itemId)?->delete();
    }

    public function toggleItem($itemId)
    {
        $item = Item::whereHas('category', function ($query) {
            $query->where('user_id', Auth::id());
        })->find($itemId);

        if ($item) {
            $item->update(['is_completed' => !$item->is_completed]);
        }
    }
}
