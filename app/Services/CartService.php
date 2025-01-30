<?php

namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\DataBase\QueryException;
use App\Models\{Cart, Loan, LoanDetail, Book};
use App\Exceptions\BookException;
use App\Exceptions\NotFoundException;
use App\Exceptions\InternalServerErrorException;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CartService {



    public function createLoanCart( Request $request ){

        $newRequest = collect($request)->all();          
        try {
            $userId = Auth::id();

            $carts = Cart::where('user_id', $userId)->get();

            $loan = Loan::create([
                'user_id' => $userId, 
                'total_units' => $newRequest['total_units'],                       
                'note' => $newRequest['note'],                          
                'date_returned' => Carbon::parse($newRequest['date_returned'])->timezone(config('app.timezone')),           
                'status' => Loan::STATUS_ACTIVE, 
            ]);

            foreach ($carts as $cart) {


                $book = Book::find($cart->book_id);
                if (!$book) {
                    throw new NotFoundException('El libro con ID ' . $cart->book_id . ' no existe.');
                }
                if ($book->units < $cart->quantity) {
                    throw new NotFoundException('El libro "' . $book->title . '" no tiene suficiente stock.');
                }
    
                
                LoanDetail::create([
                    'loan_id' => $loan->id,
                    'book_id' => $cart->book_id,
                    'quantity' => $cart->quantity,
                ]);

            }

            
            Cart::where('user_id', $userId)->delete();

            
            return response()->json([
                'status' => true,
                'message' => 'Prestamo realizado  con  éxito.',  
                'data' => $newRequest['date_returned']                                
            ], 200);
            
            

        }   catch (QueryException $e) {                              
            throw new InternalServerErrorException('Error en los query de la base de datos: ' . $e->getMessage());
    
        }   catch (\PDOException $th) {            
            throw new InternalServerErrorException('Error de conexión en la base de datos: ' . $th->getMessage());
            
        }   catch (Exception $e) {            
            throw new InternalServerErrorException('Error no controlado: ' . $e->getMessage());
        }
    }


    public function findAllCart( Request $request ){

        try {

            $userId = Auth::id();

            $cartItems = Cart::with('book')
                            ->orderBy('created_at', 'desc')
                            ->where('user_id', $userId)
                            ->get()
                            ->map( function ($cart) {
                                return [
                                    'id' => $cart->id,
                                    'quantity' => $cart->quantity,
                                    'book' => [
                                        'id' => $cart->book->id,
                                        'title' => $cart->book->title,
                                        'description' => $cart->book->description,
                                        'image' => $cart->book->image,
                                        'autor' => $cart->book->autor,
                                        'units' => $cart->book->units,
                                        'emission' => $cart->book->emission,
                                        'categories' => $cart->book->categories->pluck('name')->toArray()
                                    ],
                                  
                                ];
                            });

            return response()->json([
                'status' => true,
                'message' => 'Datos recuperados con éxito.',
                'data' => $cartItems
            ], 200);
            
        }   catch (QueryException $e) {                              
            throw new InternalServerErrorException('Error en los query de la base de datos: ' . $e->getMessage());
    
        }   catch (\PDOException $th) {            
            throw new InternalServerErrorException('Error de conexión en la base de datos: ' . $th->getMessage());
            
        }   catch (Exception $e) {            
            throw new InternalServerErrorException('Error no controlado: ' . $e->getMessage());
        }

    }



    public function deleteAllCart( Request $request ){

        
        try {         

            $user_id = Auth::id();
                        
            $deletedRows = Cart::where('user_id', $user_id)->delete();

            if ($deletedRows === 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'No hay registros para eliminar.',
                    'data' => null
                ], 200);
            }
            
            return response()->json([
                'status' => true,
                'message' => 'Carrito eliminado con éxito.',  
                'data' => null         
            ], 200);

        }   catch (QueryException $e) {                              
            throw new InternalServerErrorException('Error en los query de la base de datos: ' . $e->getMessage());
    
        }   catch (\PDOException $th) {            
            throw new InternalServerErrorException('Error de conexión en la base de datos: ' . $th->getMessage());
            
        }   catch (Exception $e) {            
            throw new InternalServerErrorException('Error no controlado: ' . $e->getMessage());
        }

    }

    public function addBooksToCart( Request $request ){

        $user_id = Auth::id();
        $book_id = $request->get('book_id');
        $quantity = $request->get('quantity');

        
        try {

          
            
            $cartItem = Cart::where('user_id', $user_id)
                ->where('book_id', $book_id)
                ->first();

            if ($cartItem) {

                $cartItem->quantity += $quantity;
                $cartItem->save();                

                $data = [
                    'id' => $cartItem->id,
                    'quantity' => $cartItem->quantity,
                    'book' => [
                        'id' => $cartItem->book->id,
                        'title' => $cartItem->book->title,
                        'description' => $cartItem->book->description,
                        'image' => $cartItem->book->image,
                        'autor' => $cartItem->book->autor,
                        'units' => $cartItem->book->units,
                        'emission' => $cartItem->book->emission,
                        'categories' => $cartItem->book->categories->pluck('name')->toArray()
                    ],
                ];

                return response()->json([
                    'status' => true,
                    'message' => 'Cantidad actualizada con éxito.',
                    'data' => $data
                ], 200);
    
            }

            $cart = Cart::create([
                'user_id' => $user_id,
                'book_id' => $book_id,
                'quantity' => $quantity,
            ]);

            

            $data = [
                'id' => $cart->id,
                'quantity' => $cart->quantity,
                'book' => [
                    'id' => $cart->book->id,
                    'title' => $cart->book->title,
                    'description' => $cart->book->description,
                    'image' => $cart->book->image,
                    'autor' => $cart->book->autor,
                    'units' => $cart->book->units,
                    'emission' => $cart->book->emission,
                    'categories' => $cart->book->categories->pluck('name')->toArray()
                ],
            ];

            return response()->json([
                'status' => true,
                'message' => 'Libro agregado con éxito.',
                'data' => $data
            ], 200);

          


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



    public function reduceBooksToCart( Request $request ){

        try {

            $cart_id = $request->get('cart_id'); 
            $cartItem = Cart::where('id', $cart_id)->first();   
      
            $cartItem->quantity -= 1;
            
            if ($cartItem->quantity <= 0) {
                $cartItem->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'El libro fue eliminado del carrito porque la cantidad es menor o igual a 0.',
                    'data' => null
                ], 200);                
            }


            $cartItem->save();
            
            $data = [
                'id' => $cartItem->id,
                'quantity' => $cartItem->quantity,
                'book' => [
                    'id' => $cartItem->book->id,
                    'title' => $cartItem->book->title,
                    'description' => $cartItem->book->description,
                    'image' => $cartItem->book->image,
                    'autor' => $cartItem->book->autor,
                    'units' => $cartItem->book->units,
                    'emission' => $cartItem->book->emission,
                    'categories' => $cartItem->book->categories->pluck('name')->toArray()
                ],
            ];

            return response()->json([
                'status' => true,
                'message' => 'Cantidad actualizada con éxito.',
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

    public function deleteItemCartBook( Request $request ){

        $cart_id = $request->get('cart_id'); 

        try {
            
            $cartItem = Cart::where('id', $cart_id)->first();
            $cartItem->delete();
            return response()->json([
                'status' => true,
                'message' => 'El libro fue eliminado del carrito.',
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




  


    
}
