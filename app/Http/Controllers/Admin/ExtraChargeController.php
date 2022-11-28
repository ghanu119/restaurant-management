<?php

// namespace App\Http\Controllers\Admin;
namespace App\Http\Controllers\Admin;

use App\Helper\UploadFile;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateExtraChargeRequest;
use App\Models\ExtraCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ExtraChargeController extends Controller
{

    use UploadFile;

    /**
     * @name index
     *
     */
    public function index ( ) {
        return view('admin.extra-charge.view');
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
        // $orderColumns = ExtraCharge::DATATABLE_ORDER_COLUMN;
        $totalRecords = ExtraCharge::count();
        $extraCharge = ExtraCharge::query();

        if( $request->has('search') ){
            $search = $request->get('search');
            if( !empty( $search['value'] )){

                $extraCharge = $extraCharge->allColumnFilter( $search['value'] );
            }
        }

        $fileteredRecords = $extraCharge->count();

        if( $request->has('order') ){
            $order = $request->get('order')[0];
            $extraCharge = $extraCharge->orderBy( $orderColumns[ $order['column'] ], $order['dir'] );
        }
        $extraCharge = $extraCharge->latest()
                        ->take( $request->get('length', 10) )
                        ->skip( $request->get('start', 0))
                        ->get();

        $extraCharge->map(function($item, $key)
                {
                    $item->setAppends(['thumbnail_image_url']);

                    return $item;
                });

        $data = [
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $fileteredRecords,
            'data' => $extraCharge,
        ];
        return response()->json( $data, 200);
    }
    /**
     * @name create
     *
     */
    public function create ( ExtraCharge $extraCharge ) {
        $extraCharge->status = 'n';
        return view('admin.extra-charge.create', compact('extraCharge'));
    }

    /**
     * @name edit
     *
     */
    public function edit ( ExtraCharge $extraCharge ) {
        return view('admin.extra-charge.create', compact('extraCharge'));
    }

    /**
     * @name store
     *
     */
    public function store ( CreateExtraChargeRequest $request ) {
        $messageKey = 'success-message';
        $message = '';
        if( $request->has('id') ){
            $extraCharge = ExtraCharge::find( $request->get('id') );
            $message = 'Extra Charge has been updated successfully.';
        }else{
            $extraCharge = new ExtraCharge;
            $message = 'New Extra Charge created successfully.';

        }

        $newFileName = $this->move_file( $request->get('extra_charge_image'), $extraCharge->image, $request->get('name'), ExtraCharge::IMAGE_PATH );

        if( $newFileName !== false ){
            if( $extraCharge->name != $request->get('name') ){
                $extraCharge->slug = Str::slug( $request->get('name') );
            }
            $extraCharge->name = $request->get('name');
            $extraCharge->image = $newFileName;
            $extraCharge->description = $request->get('description');
            $extraCharge->price = $request->get('price');
            $extraCharge->status = $request->get('status');
            $extraCharge->save();
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
     * Remove Extra Charge complete
     *
     */
    public function delete ( ExtraCharge $extraCharge ) {
        $extraCharge->delete();
        $data = [
            'message' => 'Success ! Extra Charge has been removed.'
        ];
        return response()->json( $data, 200 );
    }

    /**
     * @name changeStatus
     *
     * Change status of ExtraCharge
     *
     */
    public function changeStatus ( ExtraCharge $extraCharge ) {
        if( $extraCharge->status == 'y' ){
            $extraCharge->status = 'n';
        }else{

            $extraCharge->status = 'y';
        }
        $extraCharge->save();
        $data = [
            'message' => 'Success ! Extra Charge status changed successfully.'
        ];
        return response()->json( $data, 200 );
    }
}
