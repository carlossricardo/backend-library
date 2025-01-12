<?php

namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\DataBase\QueryException;
use App\Models\{User};
use App\Exceptions\BookException;
use App\Exceptions\NotFoundException;
use App\Exceptions\InternalServerErrorException;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

class SecurityService {


    public function patch( $user_id, $request ){

        try {
            

            $user = User::with('person')->findOrFail($user_id);
    
            if (!$user) {
                throw new NotFoundException('El usuario con id ' . $user_id . ' no existe.');
            }
            
            $user->email = $request['email'];
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
                'identification' => $user->person->identification ?? null,
                'image' => $user->person->image ?? null,
                'names' => $user->person->names ?? null,
                'surnames' => $user->person->surnames ?? null,
                'phone' => $user->person->phone ?? null,
                'profile' => $user->profiles->pluck('name')->first()
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




    public function checkAuthStatus ( Request $request ) {

        try {

            $user_id = Auth::id();

            $user = User::with('person')->find($user_id);

            $isAdmin = $user->profiles->contains(function ($profile) {
                return $profile->name === 'ADMIN'; 
            });
                         

            $data = [
                'id' => $user->id,
                'email' => $user->email,
                'isAdmin' => $isAdmin,
                'identification' => $user->person->identification ?? null,
                'image' => $user->person->image ?? null,
                'names' => $user->person->names ?? null,
                'surnames' => $user->person->surnames ?? null,
            ];

            return response()->json([
                'status' => true,
                'message' => 'Data successfully',
                'data' => $data
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



    public function findUserActive ( Request $request ) {

        try {

            $user_id = Auth::id();

            $user = User::with('person')->find($user_id);

            $isAdmin = $user->profiles->contains(function ($profile) {
                return $profile->name === 'ADMIN'; 
            });
             
            $data['isAdmin'] = $isAdmin;


            
            $data = [
                'id' => $user->id,
                'email' => $user->email,                
                'identification' => $user->person->identification ?? null,
                'image' => $user->person->image ?? null,
                'names' => $user->person->names ?? null,
                'surnames' => $user->person->surnames ?? null,
                'phone' => $user->person->phone ?? null,
                'profile' => $user->profiles->pluck('name')->first()
            ];

            return response()->json([
                'status' => true,
                'message' => 'Data successfully',
                'data' => $data
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

    public function findAllPermissions( Request $request ){

        try {

            $user_id = Auth::id();

            $user = User::find($user_id);

            $options = $user->profiles()
                ->with(['options' => function ($query) {
                    $query->orderBy('order', 'asc');
                }])
                ->get()
                ->pluck('options')
                ->flatten()
                ->unique('id')
                ->map(function ($option) {
                    return [
                        'id' => $option->id,
                        'name' => $option->name,
                        'url' => $option->url,
                        'icon' => $option->icon,
                        'parent_id' => $option->parent_id,
                        'status' => $option->status,
                    ];
                });
    
    
            $menus = $options->whereNull('parent_id') 
                ->map(function ($menu) use ($options) {
                    $menu['children'] = $options->where('parent_id', $menu['id'])->values()->toArray(); 
                    return $menu;
                })->values(); 
    
            return response()->json([
                'status' => true,
                'message' => 'Data retrieved successfully',
                'data' => $menus
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







    
}
