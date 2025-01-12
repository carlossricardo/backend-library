<?php

namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\DataBase\QueryException;
use App\Models\{Loan,Book,User,Category};
use App\Exceptions\BookException;
use App\Exceptions\NotFoundException;
use App\Exceptions\InternalServerErrorException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardService {

    public function findAll (  ){

        try {    
            
            
            //Week
            $startOfWeek = Carbon::now()->startOfWeek(); 
            $endOfWeek = Carbon::now()->endOfWeek();

            
            $loans = Loan::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                        ->with('details') 
                        ->get();

             
            $totalBooksLoaned = 0;
            
            foreach ($loans as $loan) {
                foreach ($loan->details as $detail) {
                    $totalBooksLoaned += $detail->quantity; 
                }
            }

            //Month
            $startOfMonth = Carbon::now()->startOfMonth(); 
            $endOfMonth = Carbon::now()->endOfMonth();

            $loansMonth = Loan::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->with('details') 
                ->get(); 

            $totalBooksLoanedMonth = 0;

            foreach ($loansMonth as $loan) { 
                foreach ($loan->details as $detail) { 
                    $totalBooksLoanedMonth += $detail->quantity; 
                }
            }

             
             $totalStudents = User::whereHas('profiles', function ($query) {
                $query->where('name', 'STUDENT'); 
            })->count();
             $totalTeachers = User::whereHas('profiles', function ($query) {
                $query->where('name', 'TEACHER'); 
            })->count();
            

            
            $totalAvailableBooks = Book::where('status', true)->count();


            $dashboard = [
                [
                    'name' => 'Prestamos por semana',
                    'icon' => 'pi pi-cart-arrow-down',
                    'value' => $totalBooksLoaned,
                    'color' => 'bg-blue-100'
                ],
                [
                    'name' => 'Prestamos por mes',
                    'icon' => 'pi pi-calendar',
                    'value' => $totalBooksLoanedMonth,
                    'color' => 'bg-yellow-300'
                ],
                [
                    'name' => 'Cantidad de estudiantes',
                    'icon' => 'pi pi-users',
                    'value' => $totalStudents,
                    'color' => 'bg-orange-100'
                ],
                [
                    'name' => 'Libros disponibles',
                    'icon' => 'pi pi-book',
                    'value' => $totalAvailableBooks,
                    'color' => 'bg-cyan-100'
                ],
                [
                    'name' => 'Cantidad de docentes',
                    'icon' => 'pi pi-graduation-cap',
                    'value' => $totalTeachers,
                    'color' => 'bg-green-100'
                ],
         
            ];


            return response()->json([
                'status' => true,
                'message' => 'Data successful',
                'data' => $dashboard,
               
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



    public function findAllClient(){

        try {
            $userId = Auth::id();

            $startOfWeek = Carbon::now()->startOfWeek(); 
            $endOfWeek = Carbon::now()->endOfWeek();

            
            $loans = Loan::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                        ->where('user_id', $userId)
                        ->with('details') 
                        ->get();

             
            $totalBooksLoaned = 0;

            foreach ($loans as $loan) {
                foreach ($loan->details as $detail) {
                    $totalBooksLoaned += $detail->quantity; 
                }
            }

             //Month
             $startOfMonth = Carbon::now()->startOfMonth(); 
             $endOfMonth = Carbon::now()->endOfMonth();
 
             $loansMonth = Loan::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->where('user_id', $userId)
                ->with('details') 
                ->get(); 
 
             $totalBooksLoanedMonth = 0;
 
             foreach ($loansMonth as $loan) { 
                 foreach ($loan->details as $detail) { 
                     $totalBooksLoanedMonth += $detail->quantity; 
                 }
             }

            $totalAvailableBooks = Book::where('status', true)->count();

            $totalAvailableCategories = Category::where('status', true)->count();


              
            $randomBooks = Book::select('title', 'image', 'description', 'autor', 'units')
                ->where('status', 1)
                ->where('units', '>', 0)
                ->inRandomOrder()
                ->take(8)
                ->get();

            $dashboard = [
                [
                    'name' => 'Prestamos por semana',
                    'icon' => 'pi pi-cart-arrow-down',
                    'value' => $totalBooksLoaned,
                    'color' => 'bg-blue-100'
                ],

                [
                    'name' => 'Prestamos por mes',
                    'icon' => 'pi pi-calendar',
                    'value' => $totalBooksLoanedMonth,
                    'color' => 'bg-yellow-300'
                ],

                [
                    'name' => 'Libros disponibles',
                    'icon' => 'pi pi-book',
                    'value' => $totalAvailableBooks,
                    'color' => 'bg-cyan-100'
                ],
                [
                    'name' => 'Categorías disponibles',
                    'icon' => 'pi pi-objects-column',
                    'value' => $totalAvailableCategories,
                    'color' => 'bg-green-100'
                ],

         
            ];


            return response()->json([
                'status' => true,
                'message' => 'Data successful',
                'data' => $dashboard,
                'carrousel' => $randomBooks
               
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
