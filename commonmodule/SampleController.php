<?php

// namespace App\Http\Controllers\Admin;
namespace {NAMESPACE};

use App\Http\Controllers\Controller;
use {CUSTOM_FORM_REQUEST_NAMESPACE}\{CUSTOM_FORM_REQUEST};
use {MODEL_NAMESPACE}\{MODEL_CLASS};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
{USE_UPLOAD_FILE}

class {CONTROLLER_NAME} extends Controller
{
 
    {UPLOAD_FILE}

    /**
     * @name index
     * 
     */
    public function index ( ) {
        return view('{BLADE_VIEW_NAME}');
    }

    /**
     * @name ajaxTableData
     * 
     * Return datatable ajax data 
     */
    public function ajaxTableData ( Request $request ){
        // $orderColumns = [
        //     'id',
        //     'name',
        //     '',
        //     'price',
        //     'status',
        //     'created_at',
        // ];
        $orderColumns = {MODEL_CLASS}::DATATABLE_ORDER_COLUMN;
        $totalRecords = {MODEL_CLASS}::count();
        {DATATABLE_OBJ} = {MODEL_CLASS}::query();
        
        if( $request->has('search') ){
            $search = $request->get('search');
            if( !empty( $search['value'] )){

                {DATATABLE_OBJ} = {DATATABLE_OBJ}->allColumnFilter( $search['value'] );
            }
        }
        
        $fileteredRecords = {DATATABLE_OBJ}->count();
        
        if( $request->has('order') ){
            $order = $request->get('order')[0];
            {DATATABLE_OBJ} = {DATATABLE_OBJ}->orderBy( $orderColumns[ $order['column'] ], $order['dir'] );
        }
        {DATATABLE_OBJ} = {DATATABLE_OBJ}->latest()
                        ->take( $request->get('length', 10) )
                        ->skip( $request->get('start', 0))
                        ->get();

        {DATATABLE_OBJ}->map(function($item, $key)
                {
                    $item->setAppends(['thumbnail_image_url']);

                    return $item;
                });
        
        $data = [
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $fileteredRecords,
            'data' => {DATATABLE_OBJ},
        ];
        return response()->json( $data, 200);
    }
    /**
     * @name create
     * 
     */
    public function create ( {MODEL_CLASS} ${MODEL_ROUTE_VAR} ) {
        ${MODEL_ROUTE_VAR}->status = 'n'; 
        return view('{BLADE_VIEW_CREATE}', compact('{MODEL_ROUTE_VAR}'));
    }
 
    /**
     * @name edit
     * 
     */
    public function edit ( {MODEL_CLASS} ${MODEL_ROUTE_VAR} ) {
        return view('{BLADE_VIEW_CREATE}', compact('{MODEL_ROUTE_VAR}'));
    }

    /**
     * @name store
     * 
     */
    public function store ( {CUSTOM_FORM_REQUEST} $request ) {
        $messageKey = 'success-message';
        $message = '';
        if( $request->has('id') ){
            ${MODEL_ROUTE_VAR} = {MODEL_CLASS}::find( $request->get('id') );
            $message = '{MODULE_NAME} has been updated successfully.';
        }else{
            ${MODEL_ROUTE_VAR} = new {MODEL_CLASS};
            $message = 'New {MODULE_NAME} created successfully.';

        }
        
        $newFileName = $this->move_file( $request->get('{MODEL_ROUTE_VAR}_image'), ${MODEL_ROUTE_VAR}->image, $request->get('name'), {MODEL_ROUTE_VAR}::IMAGE_PATH );

        if( $newFileName !== false ){
            if( ${MODEL_ROUTE_VAR}->name != $request->get('name') ){
                ${MODEL_ROUTE_VAR}->slug = Str::slug( $request->get('name') );
            }
            ${MODEL_ROUTE_VAR}->name = $request->get('name');
            ${MODEL_ROUTE_VAR}->image = $newFileName;
            ${MODEL_ROUTE_VAR}->description = $request->get('description');
            ${MODEL_ROUTE_VAR}->price = $request->get('price');
            ${MODEL_ROUTE_VAR}->status = $request->get('status');
            ${MODEL_ROUTE_VAR}->save();
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
     * Remove {MODULE_NAME} complete
     * 
     */
    public function delete ( {MODEL_CLASS} ${MODEL_ROUTE_VAR} ) {
        ${MODEL_ROUTE_VAR}->delete();
        $data = [
            'message' => 'Success ! {MODULE_NAME} has been removed.'
        ];
        return response()->json( $data, 200 );
    }

    /**
     * @name changeStatus
     * 
     * Change status of {MODULE_NAME}
     * 
     */
    public function changeStatus ( {MODEL_CLASS} ${MODEL_ROUTE_VAR} ) {
        if( ${MODEL_ROUTE_VAR}->status == 'y' ){
            ${MODEL_ROUTE_VAR}->status = 'n';
        }else{
            
            ${MODEL_ROUTE_VAR}->status = 'y';
        }
        ${MODEL_ROUTE_VAR}->save();
        $data = [
            'message' => 'Success ! {MODULE_NAME} status changed successfully.'
        ];
        return response()->json( $data, 200 );
    }
}
