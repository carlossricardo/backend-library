<?php

namespace App\Services;
use Illuminate\Http\Request;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;


use Illuminate\DataBase\QueryException;
use App\Models\Book;
use App\Models\Category;
use App\Models\Profile;
use App\Exceptions\BookException;
use App\Exceptions\NotFoundException;
use App\Exceptions\InternalServerErrorException;


use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

class ToolService {

    public function upload($request)
    {
        $response = [];
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $folder = $request->input('folder');
    
            
            $validExtension = ['png', 'jpg', 'jpeg', 'JPG'];
            $extension = $file->getClientOriginalExtension();
    
            if (in_array($extension, $validExtension)) {
                
                $newFileName = Str::uuid()->toString() . '_' . now()->format('Ymd_His') . '.' . $extension;
    
                
                $file->move(storage_path('app/' . $folder), $newFileName);
    
                $response = [
                    'status' => true,
                    'message' => 'El archivo se ha subido al servidor',
                    'data' => $newFileName, 
                ];
            } else {
                $response = [
                    'status' => false,
                    'message' => 'Extensión de archivo no válida',
                    'data' => '',
                ];
            }
        } else {
            $response = [
                'status' => false,
                'message' => 'No hay archivos para procesar',
                'data' => '',
            ];
        }
    
        return response()->json($response);
    }


    public function getFile($folder, $file){

         
        if (Storage::disk($folder)->exists($file)) {
            
            $fileContent = Storage::disk($folder)->get($file);

            
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $mimeType = match($extension) {
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                default => 'application/octet-stream',
            };

            
            return new Response($fileContent, 200, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $file . '"',
            ]);
        }

        
        return response()->json([
            'status' => false,
            'message' => 'No existe la imagen',
        ], 404);

    }




    
}
