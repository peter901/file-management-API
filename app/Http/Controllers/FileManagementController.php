<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\FileManagement;

class FileManagementController extends Controller
{
    
   
    public function create(Request $request)
    {
        //todo Validate request
        $request->validate([
            'path' => 'required|file' //max:X|mimes:jpeg,png,pdf|dimensions:min_width=100,min_height=100 - additional rules can be added depending on the requirements
        ]);

        $path = $request->file('path')->store('uploads');
        $uploaded_file = FileManagement::create(['path'=>$path]);

        return response()->json([
            'status'=>'success',
            'message'=>'File uploaded successfully',
            'id'=>$uploaded_file['id'],
        ],201);
    
    }

    public function read($id)
    {
        //check if reference exists in the database
        $file = FileManagement::find($id);
        if(empty($file)){
            return response()->json([
                'status'=>'error',
                'message' => 'File reference not found',
            ],404);
        }

        //check if file exists on the file system
        if(!Storage::exists($file['path'])){
            return response()->json([
                'status'=>'error',
                'message'=>'File not found on this server'
            ],404);
        }

        $url = Storage::url($file['path']);
        
        return response()->json([
            'status' =>'success',
            'message' =>'File retrived successfully',    
            'url'=>$url
        ],200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'path'=> 'required|file'
        ]);

        //check if reference exists in the database
        $file = FileManagement::find($id);
        if(empty($file)){
            return response()->json([
                'status'=>'error',
                'message' => 'File reference not found',
            ],404);
        }

        //check if file exists on the file system
        if(!Storage::exists($file['path'])){
            return response()->json([
                'status'=>'error',
                'message'=>'File not found'
            ],404);
        }


        //delete from storage old file 
        Storage::delete($file['path']);

        //upload new file
        $path = $request->file('path')->store('uploads');

        //update reference
        $file->update(['path'=>$path]);

        return response()->json([
            'status'=>'success',
            'message'=>'File updated successfully',
            'id'=>$id,
        ],200);
    }


    public function delete($id)
    {
        //check if reference exists in the database
        $file = FileManagement::find($id);
        if(empty($file)){
            return response()->json([
                'status'=>'error',
                'message' => 'File reference not found',
            ],404);
        }

        //check if file exists on the file system
        if(!Storage::exists($file['path'])){
            return response()->json([
                'status'=>'error',
                'message'=>'File not found'
            ],404);
        }

        $path = $file['path'];
        $url = Storage::url($path);

        Storage::delete($path);
        $file->delete();

        return response()->json([
            'status'=>'success',
            'message'=>"file deleted",
            'url'=>$url,
            'id'=>$id
        ]);
    }

}
