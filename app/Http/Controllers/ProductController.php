<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\category;
use App\Models\product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View ;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function __construct(request $request){

         $categories = category::all();///collection array

         View::share([
             'categories' => $categories ,
             'status_options'=> product::statusOptions()
             ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {

        $products = Product::leftjoin('categories', 'categories.id', '=', 'products.category_id')
            ->select([
                'products.*',
                'categories.name as category_name'
            ])
            ->filter($request->query()) //الكل أو الاستعلام هي نفسها
            // ->withoutGlobalScope('owner')  // نستخدمه لرفض النطاق العام للاستعلامات
            // ->active()
            // ->status('archived')
            ->paginate(5);
        $categories = Category::all();
        return view('admin.products.index', [
            'title' => 'Products List',
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        return view('admin.products.create', [
            'product' => new product(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {

        $data = $request->validated();
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('uploads/images', 'public');
            $data['image'] = $path;
        }
        $data['user_id'] = Auth::id();
        $product = Product::create($data);
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $file->store('uploads/images', 'public'),
                ]);
            }
        }
        return redirect()
            ->route('products.index')
            ->with('success', "product({$product->name})created");  //add flash mesge
    }

    // public function store(Request $request)
    // {
    //     $validator = validator($request->all(), [
    //         'name'=>'required|max:255|min:3',
    //         'slug' => "required",
    //         'category_id'=> 'nullable|int|exists:categories,id',
    //         'descripton'=> 'nullable|string',
    //         'short_description'=> 'nullable|string|max:500',
    //         'price'=>'required|numeric|min:0',
    //         'compare_price'=> 'nullable|numeric|min:0,gt:price',
    //         'status' => 'required| in:active,draft,archived',
    //         'image'=> 'required|image|max:2048|mimes:jpg,png',//kilobayte

    //     ]);
    //     if (!$validator->fails()) {
    //         $admin = new product();
    //         $admin->name = $request->input('name');
    //         $admin->slug = $request->input('slug');
    //         $admin->category_id = $request->input('category_id');
    //         $admin->description = $request->input('descripton');
    //         $admin->short_description = $request->input('short_description');
    //         $admin->price = $request->input('price');
    //         $admin->compare_price = $request->input('compare_price');
    //         $admin->status = $request->input('status');
    //         if ($request->hasFile('image')) {
    //             $imageName = time() . "image" . '.' . $request->file('image')->getClientOriginalExtension();
    //             $request->file('image')->storePubliclyAs('image', $imageName);
    //             $admin->image = 'image/' . $imageName;
    //         }
    //         $issaved = $admin->save();
    //         return response()->json(
    //             ['message' => $issaved ? 'Admin created successfully' : 'Admin created failed'],
    //             $issaved ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
    //         );
    //     } else {
    //         return response()->json(
    //             [
    //                 'message' => $validator->getMessageBag()->first()
    //             ],
    //             Response::HTTP_BAD_REQUEST

    //         );
    //     }
    // }

    /**
     * Display the specified resource.
     */
    public function show(product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(product $product)
    {

        $gallery = ProductImage::where('product_id', '=', $product->id)->get();
        return view('admin.products.edit', [
            'product' => $product,
            'gallery' => $gallery,

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, product $product)
    {


        $data = $request->validated();
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('uploads/images', 'public');
            $data['image'] = $path;
        }
        $old_image = $product->image;
        $product->update($data);

        if ($old_image && $old_image != $product->image) {
            Storage::disk('public')->delete($old_image);
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $file->store('uploads/images', 'public'),
                ]);
            }
        }
        $old_image = $product->image;
        $product->update($data);
        return redirect()
            ->route('products.index')
            ->with('success', "product({$product->name})update"); //get
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(product $product)
    {
        //

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', "product({$product->name})deleted"); //get


    }
    public function trashed()
    {
        $products = product::onlyTrashed()->paginate();
        return view('admin.products.trashed', [
            'products' => $products,
            'title' => 'Trashed List'
        ]);
    }
    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();
        return redirect()->route('products.index')
            ->with('success', "Product ({$product->name}) Restored");
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->forceDelete();
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        return redirect()->route('products.index')
            ->with('success', "Product ({$product->name}) Deleted forever!");
    }
}
