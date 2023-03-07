<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Vanguard\Models\QueueProcess;
use Vanguard\Repositories\PotentialProduct\PotentialProductRepository;

/**
 * disbatch colltion job
 * Each task that needs to be collected is divided into directories
 */
class PotentialFetchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $products;

    protected $proc;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PotentialProductRepository $products, QueueProcess $proc)
    {
        $this->products = $products;
        $this->proc = $proc;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $affectRow = $this->products->fetchProducts();
            $this->proc->status = 1;
            $this->proc->total = $affectRow;
            $this->proc->affect = $affectRow;
            $this->proc->save();
        } catch (\Exception $e) {
            $this->proc->status = -1;
            $this->proc->message = $e->getMessage();
            $this->proc->save();
        }
        $this->delete();
    }
}
