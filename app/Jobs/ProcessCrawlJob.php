<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Vanguard\Http\Controllers\Web\CrawlController;
use Vanguard\Repositories\Crawl\PotentialProductRepository;
use Vanguard\Repositories\User\UserRepository;

class ProcessCrawlJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    protected $user_id;
    protected $userRepository;
    protected $potentialProduct;

    /**
     * ProcessCrawlJob constructor.
     *
     * @param $user_id
     * @param $userRepository
     * @param $potentialProduct
     */
    public function __construct($user_id, UserRepository $userRepository, PotentialProductRepository $potentialProduct)
    {
        $this->user_id = $user_id;
        $this->userRepository = $userRepository;
        $this->potentialProduct = $potentialProduct;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $crawl = new CrawlController($this->potentialProduct, $this->userRepository);
        $crawl->getProducts();

        // send mail
        $user = $this->userRepository->find($this->user_id);
        if (!empty($user->email)) {
            try {
                \Mail::raw('Grabbing data jobs finished plz check', function ($msg) use ($user) {
                    $msg->to($user->email)->subject('Grabbing data finished');
                });
            } catch (\Exception $e) {
                \Log::error('Send mail fail:' . $e->getMessage());
            }
        }
    }
}
