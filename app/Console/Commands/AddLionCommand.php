<?php

namespace App\Console\Commands;

use App\Models\Lion;
use App\Models\User;
use Illuminate\Console\Command;

class AddLionCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'add:lion {user=1}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    $userId = $this->argument('user');
    $user = User::find($userId);
    $this->info('Creating a random lion for user ' . $user->name . ' (' . $userId . ')');

    $newLion = Lion::randomLion();
    $newLion->user_id = $userId;
    $newLion->save();

    $this->info('SUCCESS');
    return Command::SUCCESS;
  }
}
