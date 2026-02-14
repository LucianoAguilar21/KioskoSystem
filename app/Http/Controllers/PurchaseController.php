<?php
// app/Http/Controllers/PurchaseController.php
namespace App\Http\Controllers;

use App\Http\Requests\CreatePurchaseRequest;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Supplier;

use App\Services\PurchaseService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;

class PurchaseController extends Controller
{
    use AuthorizesRequests;
    public function __construct(
        private PurchaseService $purchaseService
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Purchase::class);

        $purchases = Purchase::with(['supplier', 'user'])
            ->orderByDesc('purchase_date')
            ->paginate(20);

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $this->authorize('create', Purchase::class);

        $suppliers = Supplier::active()->orderBy('name')->get();
        $products = Product::active()->orderBy('name')->get();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(CreatePurchaseRequest $request): RedirectResponse
    {
        try {
            $supplier = Supplier::findOrFail($request->validated('supplier_id'));

            $this->purchaseService->createPurchase(
                $request->user(),
                $supplier,
                $request->validated('items'),
                $request->validated('invoice_number'),
                $request->validated('purchase_date') 
                    ? new \DateTime($request->validated('purchase_date')) 
                    : null,
                $request->validated('notes')
            );

            return redirect()
                ->route('purchases.index')
                ->with('success', 'Compra registrada correctamente');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(Purchase $purchase)
    {
        $this->authorize('viewAny', Purchase::class);
        $purchase->load(['supplier', 'user', 'items.product']);

        return view('purchases.show', compact('purchase'));
    }
}