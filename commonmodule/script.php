<?php 

function create_module ( $data ){
    die ( $_SERVER['DOCUMENT_ROOT'] );
    $moduleName = $data['module_name'];
    if( !empty( $moduleName ) ){
        $lowerCaseModuleName = strtolower( $moduleName );
        $lowerCaseModuleName = str_replace( ' ', '', $lowerCaseModuleName );
        $ucModelName = ucwords( $moduleName );
        $ucModelName = str_replace(' ', '', $ucModelName);
        $camelModelName = lcfirst( $ucModelName );
        $camelModelName = str_replace(' ', '', $camelModelName);
        $slugModelName = strtolower( Str::slug($moduleName) );
        $controllerNamespace= $data['controller_namespace'] ?? 'App\Http\Controllers\Admin';
        $controllerName = $data['controller_name'] ?? ($ucModelName. 'Controller');
        $modelNamespace = $data['model_namespace'] ?? 'App\Models';
        $modelName = $data['model_name'] ?? ($ucModelName);
        $formReqNamespace = $data['form_req_namespace'] ?? 'App\Http\Requests\Admin';
        $customFormReq = $data['form_request_class_name'] ?? 'Create'. $ucModelName. 'Request';
        $useUploadFile = '';
        if( $data['upload_file'] ){
            $useUploadFile = 'use App\Helper\UploadFile;';
            $defineUploadFile = 'use UploadFile;';
        }
        $bladeViewName = $data['blade_view_name'] ?? 'admin.'.$lowerCaseModuleName.'.view';
        $bladeCreateName = $data['blade_create_name'] ?? 'admin.'.$lowerCaseModuleName.'.create';


        $replaceData = [
            '{MODULE_NAME}' => $moduleName,
            '{NAMESPACE}' => $controllerNamespace,
            '{CUSTOM_FORM_REQUEST_NAMESPACE}' => $formReqNamespace,
            '{CUSTOM_FORM_REQUEST}' => $customFormReq,
            '{MODEL_NAMESPACE}' => $modelNamespace,
            '{MODEL_CLASS}' => $$modelName,
            '{USE_UPLOAD_FILE}' => $useUploadFile,
            '{CONTROLLER_NAME}' => $controllerName,
            '{UPLOAD_FILE}' => $defineUploadFile,
            '{BLADE_VIEW_NAME}' => $bladeViewName,
            '{MODEL_CLASS}' => $modelName,
            '{DATATABLE_OBJ}' => '$'. $camelModelName,
            '{MODEL_ROUTE_VAR}' => $camelModelName,
            '{BLADE_VIEW_CREATE}' => $camelModelName,
            '{MODEL_TABLE_NAME}' => $camelModelName,
            '{MODEL_ROUTE_PREFIX}' => $camelModelName,
            '{MODULE_ROUTE_NAME}' => $camelModelName,

        ];

        $controllerLocation = $data['controller_path'] ?? 'app\Http\Controllers\Admin';
        $modelLocation = $data['model_path'] ?? 'app\Models';
        $viewLocation = $data['view_path'] ?? "resources\views\admin";
        $viewLocation .= '//' . $slugModelName;

        if( !file_exists( $controllerLocation ) ){
            mkdir( $controllerLocation, 0644, true );
        }
        if( !file_exists( $modelLocation ) ){
            mkdir( $modelLocation, 0644, true );
        }
        if( !file_exists( $viewLocation ) ){
            mkdir( $viewLocation, 0644, true );
        }

        $searchArr = array_keys( $replaceData );
        $replaceArr = array_values( $replaceData );

        $controllerContent = file_get_contents('SampleController.php');
        $controllerContent = str_replace( $searchArr, $replaceArr, $controllerContent );
        
        $modelContent = file_get_contents('SampleModel.php');
        $modelContent = str_replace( $searchArr, $replaceArr, $modelContent );
        
        $jsFileContent = file_get_contents('sample.js');
        $jsFileContent = str_replace( $searchArr, $replaceArr, $jsFileContent );
        
        $createFileContent = file_get_contents('sampleview/create.blade.php');
        $createFileContent = str_replace( $searchArr, $replaceArr, $createFileContent );

        $viewFileContent = file_get_contents('sampleview/view.blade.php');
        $viewFileContent = str_replace( $searchArr, $replaceArr, $viewFileContent );
        
        $controllerFileName = $ucModelName. 'Controller.php';
        $modelFileName = $ucModelName. '.php';
        $createViewFile = 'create.blade.php';
        $indexViewFile = 'view.blade.php';
        $jsFile = $camelModelName . '.js';

        file_put_contents( $controllerLocation . '//' . $controllerFileName, $controllerContent);
        file_put_contents( $modelLocation . '//' . $modelFileName, $modelContent);
        file_put_contents( 'public\assets\admin\js//' . $jsFile, $jsFileContent);
        file_put_contents( $viewLocation . '//' . $createViewFile, $createFileContent);
        file_put_contents( $viewLocation . '//' . $indexViewFile, $viewFileContent);

    }else{
        die('Module name required');
    }
    
}