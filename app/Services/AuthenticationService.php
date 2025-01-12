<?php

namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\DataBase\QueryException;
use App\Models\Category;
use App\Models\Profile;
use App\Models\{User,Person};
use App\Exceptions\BookException;
use App\Exceptions\NotFoundException;
use App\Exceptions\BadRequestException;
use App\Exceptions\InternalServerErrorException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationService {



    public function login( $email, $password ) {
        
        
        try {
            $user = User::where( 'email', $email )
                ->where('status', 1) 
                ->first();
    
            if( !$user ){
               throw new NotFoundException("Usuario no encontrado");
            }
            
            $isPasswordCorrect = Hash::check( $password, $user->password );
            if( !$isPasswordCorrect ){
                throw new NotFoundException("La contraseña es incorrecta");
            }
    
            $payload = [ 'user_id' => $user->id ];
            $token = JWTAuth::claims($payload)->fromSubject($user);
            
            return response()->json([
                'status' => true,
                'message' => 'Acceso al sistema.',
                'token' => $token
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


    public function registerClient ( Request $request ){

        $userData = collect($request)->all();
        try {

            $existingPerson = Person::where('identification', $userData['identification'])->first();
            $existingUser = User::where('email', $userData['email'])->first();

            if( $existingPerson || $existingUser ){
                throw new BadRequestException("La cédula o el correo ya están registrados.");
            }
              
            $profileName = $userData['profile'];
            $profile = Profile::where('name', $profileName)
                ->where('status', 1)
                ->first();

            if (!$profile) {
                throw new NotFoundException("El perfil proporcionado no existe.");
            }
            

            $person = Person::create([                
                'identification' => $userData['identification'],
                'names' => $userData['names'],
                'surnames' => $userData['surnames'],
                'image' => 'fd7f7587-d385-4e64-b0a0-99d2d89a8ec2_20250106_134020_default.jpg',                
                'phone' => $userData['phone'],
                'status' => true,
            ]);

            $user = User::create([                
                'person_id' => $person->id, 
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'status' => true,
            ]);


            $user->profiles()->attach($profile->id);
            
            $payload = [ 'user_id' => $user->id ];
            $token = JWTAuth::claims($payload)->fromSubject($user);
            
            return response()->json([
                'status' => true,
                'message' => 'Registro realizado con éxito.',
                'token' => $token
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
