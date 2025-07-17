<div
    id="dshop-window"
    class="window"
    style="position: absolute; width: 800px; height: 600px; background: white; border-radius: 0.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.2); overflow: hidden;"
>
    <div class="window-header" style="cursor: move; background: #f1f5f9; padding: 0.5rem;">
        <span>D-Shop</span>
        <button style="float: right;" wire:click="close">✕</button>
    </div>
    <div class="window-content" style="padding: 1rem; overflow-y: auto; height: calc(100% - 3rem); box-sizing: border-box;">
        <h2 class="text-xl font-semibold mb-4">Available Products</h2>
        <div class="grid grid-cols-3 gap-4">
            @foreach($products ?? [] as $product)
                <div class="border border-gray-300 rounded p-4 flex flex-col items-center">
                    @if(!empty($product->pictures) && count($product->pictures) > 0)
                        <img src="{{ $product->pictures[0] }}" alt="{{ $product->name }}" class="w-full h-40 object-cover mb-2 rounded" />
                    @else
                        <div class="w-full h-40 bg-gray-200 flex items-center justify-center mb-2 rounded">
                            <span class="text-gray-500">No Image</span>
                        </div>
                    @endif
                    <div class="text-lg font-semibold mb-1">${{ number_format($product->price, 2) }}</div>
                    <div class="mb-2">{{ $product->name }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>
