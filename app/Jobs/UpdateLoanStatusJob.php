<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\{Loan};

class UpdateLoanStatusJob implements ShouldQueue
{
    use Queueable;


    public $loanId;

    /**
     * Create a new job instance.
     */
    public function __construct( $loanId )
    {
        $this->loanId = $loanId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        \Log::info('Start handling loan status update for loan id: ' . $this->loanId);
        $loan = Loan::find($this->loanId);

        if (!$loan) {
            \Log::error('No se encontró el préstamo con ID: ' . $this->loanId);
            return;
        }

        if ($loan->status === Loan::STATUS_ACCEPTED && $loan->date_returned <= now()) {
            
            $loan->status = Loan::STATUS_OVERDUE;
            $loan->save();
            \Log::info('El préstamo con ID: ' . $loan->id . ' se marcó como atrasado.');
        }

        
    }
}
