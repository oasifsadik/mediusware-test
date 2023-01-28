<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $sql = Product::with('variantPrice');

        if($request->title) {
            $sql->where('title', 'like', '%' . $request->title . '%');
        }

        $products = $sql->paginate(10);

        return view('products.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $product = new Product();
        $product->title = $request->product_name;
        $product->sku = $request->product_sku;
        $product->description = $request->product_description;
        $product->save();

        if($product) {
            // Image upload
            if ($request->hasFile('product_images'))
            {
                $fileData = [];
                foreach($request->product_images as $image) {
                    $file = $image;
                    $extention =$file->getClientOriginalExtension();
                    $filename = time().'.'.$extention;
                    $file -> move('product-image/',$filename);

                    $fileData[] = [
                        'product_id' => $product->id,
                        'file_path' => asset('product-image/'. $filename),
                    ];
                }
                ProductImage::insert($fileData);
            }

            // Product variant
            $productVariants = $variantPrices = [];
            foreach ($request->product_variant as $var) {
                foreach ($var['value'] as $val) {
                    $productVariants[] = [
                        'variant_id' => $var['option'],
                        'variant' => $val,
                        'product_id' => $product->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }

            ProductVariant::insert($productVariants);

            foreach ($request->product_preview as $val) {
                $variants = explode('/', $val['variant']);
                $variantPrices[] = [
                    'product_variant_one' => ProductVariant::where(['product_id' => $product->id, 'variant' => $variants[0] ?? null])->first()->id ?? null,
                    'product_variant_two' => ProductVariant::where(['product_id' => $product->id, 'variant' => $variants[1] ?? null])->first()->id ?? null,
                    'product_variant_three' => ProductVariant::where(['product_id' => $product->id, 'variant' => $variants[2] ?? null])->first()->id ?? null,
                    'price' => $val['price'],
                    'stock' => $val['stock'],
                    'product_id' => $product->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            ProductVariantPrice::insert($variantPrices);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $variants = Variant::all();
        return view('products.edit', compact('variants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }

}
