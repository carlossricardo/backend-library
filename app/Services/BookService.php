<?php

namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\DataBase\QueryException;
use App\Models\Book;
use App\Models\Category;
use App\Exceptions\BookException;
use App\Exceptions\NotFoundException;
use App\Exceptions\InternalServerErrorException;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

class BookService {


    public function deleteItem( Request $request ){

        try {

            $book_id = $request->get('book_id'); 
            


            $bookItem = Book::where('id', $book_id)->first();
            $bookItem->delete();
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


    public function findAllClientByCategoryUuid ( $categoryUuid, int $offset = 0, int $limit = 10 ) {

        try {            

            $category = Category::where('id', $categoryUuid)->first();
            
            if( !$category ){
                throw new NotFoundException("Categoria no encontrada");
            }

            $total_records = $category->books()->where('status', 1)->count();

            $books = $category->books()
                ->where('status', 1)
                ->orderBy('created_at', 'desc')
                ->offset($offset * $limit)
                ->limit($limit)
                ->get()
                ->map(function ($book) {
                    return [
                        'id' => $book->id,
                        'title' => $book->title,
                        'description' => $book->description,
                        'image' => $book->image,
                        'autor' => $book->autor,
                        'emission' => $book->emission,
                        'units' => $book->units,
                        'status' => $book->status,
                        'categories' => $book->categories->pluck('name')->toArray(),
                    ];
                });
            return response()->json([
                'status' => true,
                'message' => 'Data successful',
                'data' => $books,
                'total_records' => $total_records,
            ], 201);


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

    public function patch( $book_id, $request )
    {                
        try {
            $book = Book::find($book_id);
    
            if (!$book) {
                return response()->json([
                    'status' => false,
                    'message' => 'Este libro no existe.',
                    'missing_categories' => null
                ], 404);
            }
    
            
            $categories = Category::whereIn('id', $request['categories'])->get();
            $missingCategories = array_diff($request['categories'], $categories->pluck('id')->toArray());
    
            if (!empty($missingCategories)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Algunas categorías no existen.',
                    'missing_categories' => $missingCategories,
                ], 404);
            }

            $defaultImage = '53336135-f0fe-4fd2-9fb8-7730a5900a45_20241219_215014_default.jpg';
            $image = !empty($request['image']) ? $request['image'] : $defaultImage;
        
            $book->update([
                'title' => $request['title'] ?? $book->title, 
                'description' => $request['description'] ?? $book->description,                
                'image' => $image ?? $book->image,                
                'autor' => $request['autor'] ?? $book->autor,
                'status' => $request['status'] ?? $book->status,            
                'emission' => Carbon::parse($request['emission'])->timezone(config('app.timezone')) ?? $book->emission,                
                'units' => $request['units'] ?? $book->units,
            ]);
    
            
            $book->categories()->sync($categories->pluck('id')->toArray());
    
            
            $response = $book->toArray();
            $response['categories'] = $book->categories()->pluck('id')->toArray();
    
    
    
            return response()->json([
                'status' => true,
                'message' => 'Registro actualizado con éxito.',
                'data' => $response,
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

    public function create($request)
    {
        try {

            $categories = Category::whereIn('id', $request['categories'])->get();
            
            $missingCategories = array_diff( $request['categories'], $categories->pluck('id')->toArray());
            if (!empty($missingCategories)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Algunas categorías no existen.',
                    'missing_categories' => $missingCategories,
                ], 404);
            }

            $defaultImage = '53336135-f0fe-4fd2-9fb8-7730a5900a45_20241219_215014_default.jpg';
            $image = !empty($request['image']) ? $request['image'] : $defaultImage;
            
            $newBook = Book::create([
                'title' => $request['title'],
                'description' => $request['description'],
                // 'image' => $request['image'],
                'image' => $image,
                'autor' => $request['autor'],
                'status' => $request['status'],
                // 'emission' => Carbon::parse($request['emission'])->toDateString(),
                'emission' =>  Carbon::parse($request['emission'])->timezone(config('app.timezone')),
                'units' => $request['units'],
            ]);
            
            $newBook->categories()->attach($categories->pluck('id')->toArray());


            $response = $newBook->toArray();
            $response['categories'] = $newBook->categories()->pluck('id')->toArray();
            
            return response()->json([
                'status' => true,
                'message' => 'Registro creado con éxito.',
                'data' => $response,
            ], 201);

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



    public function findAll( int $offset = 0, int $limit = 10 ) {


        try {

            $total_records = Book::where('status', 1)->count(); 
            $books = Book::orderBy('updated_at', 'desc')
            ->offset($offset * $limit)           
            ->limit($limit)                      
            ->get()
            ->map( function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'description' => $book->description,
                    'created_at' => $book->created_at,
                    'updated_at' => $book->updated_at,
                    'image' => $book->image,
                    'autor' => $book->autor,
                    'emission' => $book->emission,
                    'units' => $book->units,
                    'status' => $book->status,
                    'categories' => $book->categories->pluck('id')->toArray()
                ];
            });

            
            return response()->json([
                'status' => true,
                'message' => 'Data successful',
                'data' => $books,
                'total_records' => $total_records,
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


    public function findAllClient( int $offset = 0, int $limit = 10 ) {


        try {
            $total_records = Book::where('status', 1)->count(); 
            $books = Book::where('status', 1)
                ->orderBy('created_at', 'desc')
                ->offset($offset * $limit)           
                ->limit($limit)                      
                ->get()
                ->map( function ($book) {
                    return [
                        'id' => $book->id,
                        'title' => $book->title,
                        'description' => $book->description,
                        'image' => $book->image,
                        'autor' => $book->autor,
                        'emission' => $book->emission,
                        'units' => $book->units,
                        'status' => $book->status,
                        'categories' => $book->categories->pluck('name')->toArray()
                    ];
                });
            
            return response()->json([
                'status' => true,
                'message' => 'Data successful',
                'data' => $books,
                'total_records' => $total_records,
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
