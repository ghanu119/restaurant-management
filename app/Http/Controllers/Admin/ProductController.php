<?php

namespace App\Http\Controllers\Admin;

use App\Helper\UploadFile;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductCreateRequest;
use App\Models\Category;
use App\Models\ExtraCharge;
use App\Models\Product;
use App\Models\ProductExtraCharges;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    use UploadFile;

    /**
     * @name index
     *
     */
    public function index ( ) {
        return view('admin.product.view');
    }

    /**
     * @name ajaxTableData
     *
     * Return datatable ajax data
     */
    public function ajaxTableData ( Request $request ){
        $orderColumns = [
            'id',
            'name',
            '',
            'price',
            'status',
            'created_at',
        ];
        $totalRecords = Product::count();
        $products = Product::query();

        if( $request->has('search') ){
            $search = $request->get('search');
            if( !empty( $search['value'] )){

                $products = $products->allColumnFilter( $search['value'] );
            }
        }

        $fileteredRecords = $products->count();

        if( $request->has('order') ){
            $order = $request->get('order')[0];
            $products = $products->orderBy( $orderColumns[ $order['column'] ], $order['dir'] );
        }
        $products = $products->latest()
                        ->take( $request->get('length', 10) )
                        ->skip( $request->get('start', 0))
                        ->get();

        $products->map(function($item, $key)
                {
                    $item->setAppends(['thumbnail_image_url']);

                    return $item;
                });

        $data = [
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $fileteredRecords,
            'data' => $products,
        ];
        return response()->json( $data, 200);
    }
    /**
     * @name create
     *
     */
    public function create ( Product $product ) {
        $product->status = 'n';
        $categoryList = Category::get();
        $extraChargesList = ExtraCharge::get();
        return view('admin.product.create', compact('product', 'categoryList', 'extraChargesList'));
    }

    /**
     * @name edit
     *
     */
    public function edit ( Product $product ) {
        $categoryList = Category::get();
        $extraChargesList = ExtraCharge::get();
        $product->load('chargesList.extraCharge');
        return view('admin.product.create', compact('product', 'categoryList', 'extraChargesList'));
    }

    /**
     * @name store
     *
     */
    public function store ( ProductCreateRequest $request ) {
        $messageKey = 'success-message';
        $message = '';
        if( $request->has('id') ){
            $product = Product::find( $request->get('id') );
            $message = 'Product has been updated successfully.';
        }else{
            $product = new Product;
            $message = 'New Product created successfully.';

        }

        $newFileName = $this->move_file( $request->get('flavour_image'), $product->image, $request->get('name'), Product::IMAGE_PATH );

        if( $newFileName !== false ){
            if( $product->name != $request->get('name') ){
                $product->slug = Str::slug( $request->get('name') );
            }
            $product->category_id = $request->get('category');
            $product->name = $request->get('name');
            $product->image = $newFileName;
            $product->description = $request->get('description');
            $product->price = $request->get('price');
            $product->status = $request->get('status');
            $product->save();

            $product->chargesList()->delete();
            $charges = $request->get('charges', []);
            if( !empty( $charges ) ){
                foreach( $charges as $c ){
                    $productExtraCharges = new ProductExtraCharges();
                    $productExtraCharges->product_id = $product->id;
                    $productExtraCharges->extra_charges_id = data_get( $c, 'id');
                    $productExtraCharges->price = data_get( $c, 'price', 0);
                    $productExtraCharges->save();
                }
            }
            return redirect()->back()->with( $messageKey, $message );
        }else{

            $message = 'Image not found.';
            $messageKey = 'error-message';
            return redirect()->back()->with( $messageKey, $message )->withInput();
        }

    }



    /**
     * @name delete
     *
     * Remove Product complete
     *
     */
    public function delete ( Product $product) {
        $product->delete();
        $data = [
            'message' => 'Success ! Product has been removed.'
        ];
        return response()->json( $data, 200 );
    }

    /**
     * @name changeStatus
     *
     * Change status of Product
     *
     */
    public function changeStatus ( Product $product) {
        if( $product->status == 'y' ){
            $product->status = 'n';
        }else{

            $product->status = 'y';
        }
        $product->save();
        $data = [
            'message' => 'Success ! Product status changed successfully.'
        ];
        return response()->json( $data, 200 );
    }
}
