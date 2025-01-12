<?php

namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\DataBase\QueryException;
use App\Models\Category;
use App\Exceptions\BookException;
use App\Exceptions\NotFoundException;
use App\Exceptions\InternalServerErrorException;

use Illuminate\Support\Facades\DB;

class CategoryService {



    public function deleteItem( Request $request ){

        try {

            $category_id = $request->get('category_id'); 
            


            $categoryItem = Category::where('id', $category_id)->first();
            $categoryItem->delete();
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

    public function create ( $request ){

        try {            
            $newCategory = Category::create([
                'code' => $request['code'],
                'name' => $request['name'],
                'description' => $request['description'],
                'status' => $request['status']
            ]);            

            return response()->json([
                'status' => true,
                'message' => 'Registro creado con éxito.',
                'data' => $newCategory
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

    public function patch( $category_id, $request ){

        try {
            

            $category = Category::find($category_id);
    
            if (!$category) {
                throw new NotFoundException('La categoría ' . $category_id . ' no existe.');
            }

            $category->update([
                'code' => $request['code'] ?? $category->code, 
                'name' => $request['name'] ?? $category->name,
                'description' => $request['description'] ?? $category->description,                
                'status' => $request['status'] ?? $category->status,                    
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Registro actualizado con éxito.',
                'data' => $category,
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
            $total_records = Category::all()->count(); 
            $categories = Category::orderBy('updated_at', 'desc')    
            ->offset($offset * $limit)           
            ->limit($limit)                         
            ->get();


            return response()->json([
                'status' => true,
                'message' => 'Data successful',
                'data' => $categories,
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



    public function findAllClient(){

        try {
            $total_records = Category::where('status', 1)->count(); 
            $categories = Category::where('status', 1)
                ->orderBy('updated_at', 'desc')                       
                ->get()
                ->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'code' => $category->code,
                        'name' => $category->name,
                        'description' => $category->description,
                        // 'image' => $category->image,                   
                        'status' => $category->status,                        
                    ];
                });


            return response()->json([
                'status' => true,
                'message' => 'Data successful',
                'data' => $categories,
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
