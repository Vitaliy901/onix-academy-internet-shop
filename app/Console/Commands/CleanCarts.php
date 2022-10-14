<?php

namespace App\Console\Commands;

use App\Models\Cart;
use Illuminate\Console\Command;

class CleanCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:cart {daies=3}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean carts.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $daies = $this->argument('daies');

        Cart::where('created_at', '<', now()->subDays($daies))->delete();

        $this->info('Cart cleared successfully!');
    }
}
