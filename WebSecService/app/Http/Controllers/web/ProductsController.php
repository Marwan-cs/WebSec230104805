<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use DB;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductsController extends Controller {

    use ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth:web')->except('list');
    }

    public function list(Request $request) {

        $query = Product::select("products.*");

        $query->when($request->keywords, 
        fn($q)=> $q->where("name", "like", "%$request->keywords%"));

        $query->when($request->min_price, 
        fn($q)=> $q->where("price", ">=", $request->min_price));
        
        $query->when($request->max_price, fn($q)=> 
        $q->where("price", "<=", $request->max_price));
        
        $query->when($request->order_by, 
        fn($q)=> $q->orderBy($request->order_by, $request->order_direction??"ASC"));

        $products = $query->get();

        return view('products.list', compact('products'));
        
    }

    public function edit(Request $request, Product $product = null) {

        if(!auth()->user()) return redirect('/');

        $product = $product??new Product();

        return view('products.edit', compact('product'));
    }

    public function save(Request $request, Product $product = null) {

        $this->validate($request, [
            'code' => ['required', 'string', 'max:32'],
            'name' => ['required', 'string', 'max:128'],
            'model' => ['required', 'string', 'max:256'],
            'description' => ['required', 'string', 'max:1024'],
            'price' => ['required', 'numeric'],
        ]);

        $product = $product??new Product();
        $product->fill($request->all());
        $product->save();

        return redirect()->route('products_list');
    }

    public function delete(Request $request, Product $product) {
        if (!auth()->user()->hasPermissionTo('delete_products')) {
            abort(401);
        }
        $product->delete();
        return redirect()->route('products_list');
    }

    public function purchase($id){
    
        $product = Product::findOrFail($id);

        // Check if the product is in stock
        if ($product->amount <= 0) {
            return redirect()->back()->with('error', 'This product is out of stock.');
        }

        // Deduct the amount by 1 (assuming 1 unit is purchased)
        $product->decrement('amount');

        // Add logic for recording the purchase (e.g., create an order record)
        // Example:
        // Order::create([
        //     'user_id' => auth()->id(),
        //     'product_id' => $product->id,
        //     'quantity' => 1,
        //     'total_price' => $product->price,
        // ]);

        return redirect()->back()->with('success', 'Product purchased successfully!');
    }

    public function updateStock(Request $request, $id) {
    
        $request->validate([
            'amount' => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($id);
        $product->amount = $request->amount;
        $product->save();

        return redirect()->back()->with('success', 'Stock updated successfully!');
    }
}