<?php

namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\DataBase\QueryException;
use App\Models\User;
use App\Exceptions\BookException;
use App\Exceptions\NotFoundException;
use App\Exceptions\InternalServerErrorException;

use Illuminate\Support\Facades\DB;

class UserService {

    public function patch( $user_id, $request ){

    
        try {
            

            $user = User::with('person')->findOrFail($user_id);
    
            if (!$user) {
                throw new NotFoundException('El usuario con id ' . $user_id . ' no existe.');
            }
            
            $user->email = $request['email'];
            $user->status = $request['status'];
            $user->save();

            
            $person = $user->person;
            $person->identification = $request['identification'];
            $person->names = $request['names'];
            $person->surnames = $request['surnames'];
            $person->image = $request['image'];
            $person->phone = $request['phone'];

            $person->save();


            $data = [
                'id' => $user->id,
                'email' => $user->email, 
                'status' => $user->status,               
                'identification' => $user->person->identification ?? null,
                'image' => $user->person->image ?? null,
                'names' => $user->person->names ?? null,
                'surnames' => $user->person->surnames ?? null,
                'phone' => $user->person->phone ?? null,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];

            return response()->json([
                'status' => true,
                'message' => 'Registro actualizado con éxito.',
                'data' => $data,
            ], 200);


        }   catch (QueryException $e) {                         
            if ($e->getCode() === '2002' || strpos($e->getMessage(), 'No connection') !== false) {
                throw new InternalServerErrorException('Error de conexión en la base de datos: ' . $e->getMessage());
            }            
            throw new InternalServerErrorException('Error al guardar en la base de datos: ' . $e->getMessage());
    
        }   catch (\PDOException $th) {            
            throw new InternalServerErrorException('Error de conexión en la base de datos: ' . $th->getMessage());
            
        }   catch (Exception $e) {            
            throw new InternalServerErrorException('Error no controlado: ' . $e->getMessage());
        }

    }


    public function deleteItem( Request $request ){

        try {

            $user_id = $request->get('user_id'); 
            


            $userItem = User::where('id', $user_id)->first();

            $userItem->delete();
            return response()->json([
                'status' => true,
                'message' => 'Registro eliminado con éxito.',
                'data' => null
            ], 200);  


        }   catch (QueryException $e) {                         
            if ($e->getCode() === '2002' || strpos($e->getMessage(), 'No connection') !== false) {
                throw new InternalServerErrorException('Error de conexión en la base de datos: ' . $e->getMessage());
            }            
            throw new InternalServerErrorException('Error al guardar en la base de datos: ' . $e->getMessage());
    
        }   catch (\PDOException $th) {            
            throw new InternalServerErrorException('Error de conexión en la base de datos: ' . $th->getMessage());
            
        }   catch (Exception $e) {            
            throw new InternalServerErrorException('Error no controlado: ' . $e->getMessage());
        }

    }

    public function findAll( int $offset = 0, int $limit = 10 ){

        try {
            $total_records = User::all()->count(); 
            $users = User::with('person')
                ->orderBy('updated_at', 'desc')    
                ->offset($offset * $limit)           
                ->limit($limit)                         
                ->get()
                ->map(function ($user) {

                    return [
                        'id' => $user->id,                        
                        'email' => $user->email,
                        'status' => $user->status,
                        'updated_at' => $user->updated_at,                        
                        'identification' => $user->person->identification ?? null,
                        'image' => $user->person->image ?? null,
                        'names' => $user->person->names ?? null,
                        'surnames' => $user->person->surnames ?? null,
                        'phone' => $user->person->phone ?? null,
                    ];
                });
                
            return response()->json([
                'status' => true,
                'message' => 'Data successful',
                'data' => $users,
                'total_records' => $total_records,
            ], 200);

        } catch (QueryException $e) {                              
            throw new InternalServerErrorException('Error en los query de la base de datos: ' . $e->getMessage());
    
        }  catch (\PDOException $th) {            
            throw new InternalServerErrorException('Error de conexión en la base de datos: ' . $th->getMessage());
            
        } catch (Exception $e) {            
            throw new InternalServerErrorException('Error no controlado: ' . $e->getMessage());
        }

    }







    
}
