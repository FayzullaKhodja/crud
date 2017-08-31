<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Model};
use Datatables;
use Validator;
use Upload;
use Config;

class {Controller} extends Controller
{

    public function getIndex()
    {
    	return view('backend.{name}.index');
    }

    /**
	* Get data for Datatable
	* 
	* @return json
	*/
    public function getData(Request $request)
    {
    	return Datatables::of({Model}::query())->make(true);
    }

     /**
     * Delete
     * 
     * @param  Request $request
     * @return json           
     */
    public function postDelete(Request $request)
    {
        if($request->has('id')) {
            $item = {Model}::find($request->input('id'));
            //Upload::removeFile('{name}', $item->id);
            $item->delete();

            $response = [
                'status' => 'success',
                'message' => trans('alert.success.delete')
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Пожалуйста попробуйте снова'
            ];
        }
        
        return response()->json($response);
    }

    /**
	 * Create and edit form
     * 
	 * @param  Request $request
	 * @return json
	 */
    public function postForm(Request $request)
    {
        $fields = [/*'title', 'description', 'url'*/];
        $data_f = [];
        foreach ($fields as $field) {
            foreach (Config::get('app.supported_locales') as $lang) {
                $data_f[] = $field.'_'.$lang;
            }
        }

    	$data = $request->only($data_f);

        $rules = [
            // 'title' => 'required|max:255',
            // 'url' => 'required|max:255'
        ];

        $rules = collect($rules)->mapWithKeys(function ($item, $key) {
            return [$key.'_'.Config::get('app.locale') => $item];
        })->toArray();

    	$validator = Validator::make($data, $rules);

        if ($validator->fails()){
            return response()->json([
                'status' => 'error',
                'errors' => $validator->messages()
            ]);
        }

        if ($request->has('id')){
            $item = {Model}::find($request->input('id'));
            if($item){
                $item->update($data);
            }
        } else {
            $item = {Model}::create($data);
        }

        // if ($request->hasFile('image')){
        // 	Upload::saveFile('{name}', $item->id, $request->file('image'),
        // 		['name' => $item->alias]);
        // }

        return response()->json(['status' => 'success']);

    }
}
