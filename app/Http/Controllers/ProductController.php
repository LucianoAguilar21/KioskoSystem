<?php
// app/Http/Controllers/ProductController.php
namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\PriceHistory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
{
    $query = Product::with(['category', 'line', 'brand']);

    if ($search = $request->input('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%");
        });
    }

    if ($categoryId = $request->input('category_id')) {
        $query->where('category_id', $categoryId);
    }

    if ($lineId = $request->input('line_id')) {
        $query->where('line_id', $lineId);
    }

    if ($brandId = $request->input('brand_id')) {
        $query->where('brand_id', $brandId);
    }

    if ($request->input('low_stock')) {
        $query->lowStock();
    }

    $products = $query->orderBy('name')->paginate(50);
    $filters = $request->only(['search', 'category_id', 'line_id', 'brand_id', 'low_stock']);

    return view('products.index', compact('products', 'filters'));
}

    public function create()
    {
        $this->authorize('create', Product::class);
        return view('products.create');
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        Product::create($request->validated());

        return redirect()
            ->route('products.index')
            ->with('success', 'Producto "'. $request->input('name') .'" creado correctamente');
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        $product->load('priceHistories.user');

        return view('products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $oldCostPrice = $product->cost_price;
        $oldSalePrice = $product->sale_price;

        $product->update($request->validated());

        if ($oldCostPrice != $product->cost_price || $oldSalePrice != $product->sale_price) {
            PriceHistory::create([
                'product_id' => $product->id,
                'user_id' => $request->user()->id,
                'old_cost_price' => $oldCostPrice,
                'new_cost_price' => $product->cost_price,
                'old_sale_price' => $oldSalePrice,
                'new_sale_price' => $product->sale_price,
            ]);
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Producto actualizado correctamente');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorize('delete', $product);
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Producto eliminado correctamente');
    }
}
