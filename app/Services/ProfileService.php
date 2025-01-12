<?php

namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\DataBase\QueryException;
use App\Models\Book;
use App\Models\Category;
use App\Models\Profile;
use App\Exceptions\BookException;
use App\Exceptions\NotFoundException;
use App\Exceptions\InternalServerErrorException;

use Illuminate\Support\Facades\DB;

class ProfileService {

    public function create($request)
    {

        $data_profile = collect($request)->all();

        try {
                       
            $newProfile = Profile::create([
                'name' => $data_profile['name'],
                'description' => $data_profile['description'],
                'status' => true,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Profile created.',
                'data' => $newProfile,
            ], 201);

        } catch (QueryException $e) {
            throw new InternalServerErrorException('Error al interactuar con la base de datos. ' . $e->getMessage());

        } catch (\PDOException $e) {
            throw new InternalServerErrorException('Error de conexión con la base de datos. ' . $e->getMessage());
            
        } catch (\Exception $e) {            
            throw new InternalServerErrorException('Ocurrió un error inesperado. ' . $e->getMessage());
            
        }
    }




    
}
