<?php

namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\DataBase\QueryException;
use App\Models\{Loan,Book};
use App\Exceptions\NotFoundException;
// use App\Exceptions\Exception;
use App\Exceptions\InternalServerErrorException;
use Illuminate\Support\Facades\Auth;
use App\Jobs\UpdateLoanStatusJob;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

class LoanService {



    public function deleteItem( Request $request ){

        try {

            $loan_id = $request->get('loan_id'); 
            


            $loanItem = Loan::where('id', $loan_id)->first();
            $loanItem->delete();
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


    public function patch( Request $request ){


        $loan_id = $request->get('loan_id');
        $newRequest = collect($request)->all();  
        try {

            $userId = Auth::id();

            $loan = Loan::find($loan_id);
            
            $previousStatus = $loan->status; 
            $newStatus = $newRequest['status']; 


            if (!$loan) {
                throw new NotFoundException('El prestamo con id ' . ${loan->id} . ' no existe.');
            }

            if ($newStatus === Loan::STATUS_ACCEPTED && $previousStatus !== Loan::STATUS_ACCEPTED) {
                
                foreach ($loan->details as $detail) {
                    $book = Book::find($detail->book_id);
                    if ($book) {
                        if ($book->units < $detail->quantity) {
                            throw new Exception('No hay suficientes unidades de "' . $book->title . '" disponibles.');
                        }

                    
                        $book->units -= $detail->quantity;
                        $book->save();
                    }
                }
                
                // $jobDelay = max(0, Carbon::parse($loan->date_returned)->diffInSeconds(now())); //New function                                
                // UpdateLoanStatusJob::dispatch($loan->id)->delay($jobDelay);
                
                
            } elseif ($newStatus === Loan::STATUS_RETURNED && $previousStatus === Loan::STATUS_ACCEPTED) {                
                foreach ($loan->details as $detail) {
                    $book = Book::find($detail->book_id);
                    if ($book) {
                        $book->units += $detail->quantity;
                        $book->save();
                    }
                }
            }
    
            
            $loan->reviewed_by = $userId;
            $loan->status = $newStatus;

            $loan->save();  
                          
            $loan = Loan::with(['details.book', 'user', 'reviewer'])->find($loan->id);

            
            $formattedLoan = [
                'id' => $loan->id,
                'status' => $loan->status,
                'total_units' => $loan->total_units,
                'date_returned' => $loan->date_returned,
                'created_at' => $loan->created_at,
                'updated_at' => $loan->updated_at,
                'user' => [
                    'id' => $loan->user->id,                        
                    'email' => $loan->user->email,
                    'status' => $loan->user->status,
                    'updated_at' => $loan->user->updated_at,                        
                    'identification' => $loan->user->person->identification ?? null,
                    'image' => $loan->user->person->image ?? null,
                    'names' => $loan->user->person->names ?? null,
                    'surnames' => $loan->user->person->surnames ?? null,
                    'phone' => $loan->user->person->phone ?? null,
                ],
                'reviewer' => $loan->reviewer ? [
                    'id' => $loan->reviewer->id,
                    'email' => $loan->reviewer->email,
                    'person' => $loan->reviewer->person,
                ] : null,
                'details' => $loan->details->map(function ($detail) {
                    return [
                        'id' => $detail->id,
                        'quantity' => $detail->quantity,
                        'book' => $detail->book,
                    ];
                })->toArray(),
            ];

            return response()->json([
                'status' => true,
                'message' => 'Cambios realizo con éxito.',
                'data' => $formattedLoan,                
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

    public function findAll ( int $offset = 0, int $limit = 10 ){

        try {  
            
            $total_records = Loan::all()->count();

            $loans = Loan::with(['details.book', 'user', 'reviewer']) 
            ->orderBy('updated_at', 'desc')
            ->offset($offset * $limit)
            ->limit($limit)
            ->get()
            ->map(function ($loan) {
                return [
                    'id' => $loan->id,
                    'status' => $loan->status,
                    'total_units' => $loan->total_units,
                    'date_returned' => $loan->date_returned,
                    'note' => $loan->note,
                    'created_at' => $loan->created_at,
                    'updated_at' => $loan->updated_at,
                    'user' => [
                        'id' => $loan->user->id,                        
                        'email' => $loan->user->email,
                        'status' => $loan->user->status,
                        'updated_at' => $loan->user->updated_at,                        
                        'identification' => $loan->user->person->identification ?? null,
                        'image' => $loan->user->person->image ?? null,
                        'names' => $loan->user->person->names ?? null,
                        'surnames' => $loan->user->person->surnames ?? null,
                        'phone' => $loan->user->person->phone ?? null,
                    ],
                    'reviewer' => $loan->reviewer ? [ 
                        'id' => $loan->user->id,                        
                        'email' => $loan->user->email,
                        'status' => $loan->user->status,
                        'updated_at' => $loan->user->updated_at,                        
                        'identification' => $loan->user->person->identification ?? null,
                        'image' => $loan->user->person->image ?? null,
                        'names' => $loan->user->person->names ?? null,
                        'surnames' => $loan->user->person->surnames ?? null,
                        'phone' => $loan->user->person->phone ?? null,
                    ] : null, 
                    'details' => $loan->details->map(function ($detail) {
                        return [
                            'id' => $detail->id,
                            'quantity' => $detail->quantity,
                            'book' => $detail->book, 
                        ];
                    }),
                ];
            });


            return response()->json([
                'status' => true,
                'message' => 'Data successful',
                'data' => $loans,
                'total_records' => $total_records,
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

    public function findAllClient(  int $offset = 0, int $limit = 10 ){

        try {

            $userId = Auth::id();

            $loans = Loan::with(['details.book', 'user', 'reviewer']) 
            ->orderBy('updated_at', 'desc')
            ->where('user_id', $userId)
            ->offset($offset * $limit)
            ->limit($limit)
            ->get()
            ->map(function ($loan) {
                return [
                    'id' => $loan->id,
                    'status' => $loan->status,
                    'total_units' => $loan->total_units,
                    'date_returned' => $loan->date_returned,
                    'created_at' => $loan->created_at,
                    'updated_at' => $loan->updated_at,
                    'user' => [
                        'id' => $loan->user->id,                        
                        'email' => $loan->user->email,
                        'status' => $loan->user->status,
                        'updated_at' => $loan->user->updated_at,                        
                        'identification' => $loan->user->person->identification ?? null,
                        'image' => $loan->user->person->image ?? null,
                        'names' => $loan->user->person->names ?? null,
                        'surnames' => $loan->user->person->surnames ?? null,
                        'phone' => $loan->user->person->phone ?? null,
                    ],
                    'reviewer' => $loan->reviewer ? [
                        'id' => $loan->reviewer->id,                        
                        'email' => $loan->reviewer->email,
                        'status' => $loan->reviewer->status,
                        'updated_at' => $loan->reviewer->updated_at,                        
                        'identification' => $loan->reviewer->person->identification ?? null,
                        'image' => $loan->reviewer->person->image ?? null,
                        'names' => $loan->reviewer->person->names ?? null,
                        'surnames' => $loan->reviewer->person->surnames ?? null,
                        'phone' => $loan->reviewer->person->phone ?? null,
                    ] : null,

                    'details' => $loan->details->map(function ($detail) {
                        return [
                            'id' => $detail->id,
                            'quantity' => $detail->quantity,
                            'book' => $detail->book, 
                        ];
                    }),
                ];
            });


            return response()->json([
                'status' => true,
                'message' => 'Data successful',
                'data' => $loans
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
