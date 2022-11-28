<?php

// namespace App\Http\Controllers\Admin;
namespace App\Http\Controllers\Admin;

use App\Helper\UploadFile;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateTableRequest;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class TablesController extends Controller
{

    use UploadFile;

    /**
     * @name index
     *
     */
    public function index ( ) {
        return view('admin.table.view');
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
            'capacity',
            'price',
            'status',
            'created_at',
        ];
        // $orderColumns = Table::DATATABLE_ORDER_COLUMN;
        $totalRecords = Table::count();
        $table = Table::query();

        if( $request->has('search') ){
            $search = $request->get('search');
            if( !empty( $search['value'] )){

                $table = $table->allColumnFilter( $search['value'] );
            }
        }

        $fileteredRecords = $table->count();

        if( $request->has('order') ){
            $order = $request->get('order')[0];
            $table = $table->orderBy( $orderColumns[ $order['column'] ], $order['dir'] );
        }
        $table = $table->latest()
                        ->take( $request->get('length', 10) )
                        ->skip( $request->get('start', 0))
                        ->get();

        $table->map(function($item, $key)
                {
                    $item->setAppends(['thumbnail_image_url']);

                    return $item;
                });

        $data = [
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $fileteredRecords,
            'data' => $table,
        ];
        return response()->json( $data, 200);
    }
    /**
     * @name create
     *
     */
    public function create ( Table $table ) {
        $table->status = 'n';
        return view('admin.table.create', compact('table'));
    }

    /**
     * @name edit
     *
     */
    public function edit ( Table $table ) {
        return view('admin.table.create', compact('table'));
    }

    /**
     * @name store
     *
     */
    public function store ( CreateTableRequest $request ) {
        $messageKey = 'success-message';
        $message = '';
        if( $request->has('id') ){
            $table = Table::find( $request->get('id') );
            $message = 'Table has been updated successfully.';
        }else{
            $table = new Table;
            $table->dining_status = 0;
            $message = 'New Table created successfully.';

        }


        if( $table->name != $request->get('name') ){
            $table->slug = Str::slug( $request->get('name') );
        }
        $table->name = $request->get('name');
        $table->description = $request->get('description');
        $table->capacity = $request->get('capacity');
        $table->status = $request->get('status');
        $table->save();
        return redirect()->back()->with( $messageKey, $message );

    }



    /**
     * @name delete
     *
     * Remove Table complete
     *
     */
    public function delete ( Table $table ) {
        $table->delete();
        $data = [
            'message' => 'Success ! Table has been removed.'
        ];
        return response()->json( $data, 200 );
    }

    /**
     * @name changeStatus
     *
     * Change status of Table
     *
     */
    public function changeStatus ( Table $table ) {
        if( $table->status == 'y' ){
            $table->status = 'n';
        }else{

            $table->status = 'y';
        }
        $table->save();
        $data = [
            'message' => 'Success ! Table status changed successfully.'
        ];
        return response()->json( $data, 200 );
    }
}
