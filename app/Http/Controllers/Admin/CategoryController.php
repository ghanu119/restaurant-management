<?php

namespace App\Http\Controllers\Admin;

use App\Helper\UploadFile;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryCreateRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use UploadFile;

    /**
     * @name index
     *
     */
    public function index ( ) {
        return view('admin.category.view');
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
            'status',
            'created_at',
        ];
        $totalRecords = Category::count();
        $categorys = Category::query();

        if( $request->has('search') ){
            $search = $request->get('search');
            if( !empty( $search['value'] )){

                $categorys = $categorys->allColumnFilter( $search['value'] );
            }
        }

        $fileteredRecords = $categorys->count();

        if( $request->has('order') ){
            $order = $request->get('order')[0];
            $categorys = $categorys->orderBy( $orderColumns[ $order['column'] ], $order['dir'] );
        }
        $categorys = $categorys->latest()
                        ->take( $request->get('length', 10) )
                        ->skip( $request->get('start', 0))
                        ->get();

        $categorys->map(function($item, $key)
                {
                    $item->setAppends(['thumbnail_image_url']);

                    return $item;
                });

        $data = [
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $fileteredRecords,
            'data' => $categorys,
        ];
        return response()->json( $data, 200);
    }
    /**
     * @name create
     *
     */
    public function create ( Category $category ) {
        $category->status = 'n';
        return view('admin.category.create', compact('category'));
    }

    /**
     * @name edit
     *
     */
    public function edit ( Category $category ) {
        return view('admin.category.create', compact('cate$category'));
    }

    /**
     * @name store
     *
     */
    public function store ( CategoryCreateRequest $request ) {
        $messageKey = 'success-message';
        $message = '';
        if( $request->has('id') ){
            $category = Category::find( $request->get('id') );
            $message = 'Category has been updated successfully.';
        }else{
            $category = new Category;
            $message = 'New Category has been created successfully.';

        }
        $newFileName = $this->move_file( $request->get('category_image'), $category->image, $request->get('name'), Category::IMAGE_PATH );

        if( $newFileName !== false ){
            if( $category->name != $request->get('name') ){
                $category->slug = Str::slug( $request->get('name') );
            }
            // $category->user_id = auth()->id();
            $category->name = $request->get('name');
            $category->image = $newFileName;
            $category->description = $request->get('description');
            // $category->meta_keywords = $this->storeMetaKeywords( $request->get('keywords') );
            // $category->meta_description = $request->get('meta_description');
            $category->status = $request->get('status');
            $category->save();
            return redirect()->back()->with( $messageKey, $message );
        }else{

            $message = 'Cover image not found.';
            $messageKey = 'error-message';
            return redirect()->back()->with( $messageKey, $message )->withInput();
        }

    }



    /**
     * @name delete
     *
     * Remove Category complete
     *
     */
    public function delete ( Category $category) {
        $category->delete();
        $data = [
            'message' => 'Success ! Category has been removed.'
        ];
        return response()->json( $data, 200 );
    }

    /**
     * @name changeStatus
     *
     * Change status of Category
     *
     */
    public function changeStatus ( Category $category) {
        if( $category->status == 'y' ){
            $category->status = 'n';
        }else{

            $category->status = 'y';
        }
        $category->save();
        $data = [
            'message' => 'Success ! Category status changed successfully.'
        ];
        return response()->json( $data, 200 );
    }
}
