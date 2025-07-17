<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class Dshop extends Window
{
    public $products;

    public function mount(...$params)
    {
        parent::mount(...$params);
        $this->products = Product::all();
    }

    public function close()
    {
        $this->dispatch('closeWindow', id: $this->windowId ?? null);
    }

    public function render()
    {
        Log::info('Dshop - Rendering shop with products count: ' . $this->products->count());
        if ($this->products->isEmpty()) {
            Log::warning('Dshop - No products found in the shop.');
        } else {
            Log::info('Dshop - Products loaded successfully.');
        }

         // Return the view with products
        return view('livewire.dshop', [
                'products' => $this->products,
            ]);    
    }
}
