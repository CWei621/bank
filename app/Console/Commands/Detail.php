<?php

namespace App\Console\Commands;

use Redis;

use Illuminate\Console\Command;
use App\Models\Detail as Details;

class Detail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'detail
                            {user_id : The ID of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert tarnsaction detial';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userId = $this->argument('user_id');

        try {
            $res = Details::where('user_id', $userId)->orderBy('id', 'desc')->paginate(10)->toArray();

            $jsonRes = [];

            foreach ($res['data'] as $key => $detail) {
                array_push($jsonRes, json_encode($detail));
            }
        } catch (\Exception $err) {
            return response()
                ->json([
                    'result' => 'error',
                    'msg' => $err->getMessage(),
                    'code' => $err->getCode(),
                ]);
        }

        $this->info(json_encode($jsonRes));
    }
}
