<?php

namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\DataBase\QueryException;
use App\Models\Privilege;
use App\Exceptions\BookException;
use App\Exceptions\NotFoundException;
use App\Exceptions\InternalServerErrorException;

use Illuminate\Support\Facades\DB;

class PrivilegeService {

    public function create ( $request ){

        try {            
            $newPrivilege = Privilege::create([
                'name' => $request['name'],
                'description' => $request['description'],
                'status' => true
            ]);            

            return response()->json([
                'status' => true,
                'message' => 'Data successful',
                'data' => $newPrivilege
            ], 201);

        } catch (QueryException $e) {                         
            if ($e->getCode() === '2002' || strpos($e->getMessage(), 'No connection') !== false) {
                throw new InternalServerErrorException('Error de conexión en la base de datos: ' . $e->getMessage());
            }            
            throw new InternalServerErrorException('Error al guardar en la base de datos: ' . $e->getMessage());
    
        }  catch (\PDOException $th) {            
            throw new InternalServerErrorException('Error de conexión en la base de datos: ' . $th->getMessage());
            
        } catch (Exception $e) {            
            throw new InternalServerErrorException('Error no controlado: ' . $e->getMessage());
        }
                
    }


    
}
