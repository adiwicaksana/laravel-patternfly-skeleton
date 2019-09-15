<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Log;

class SyncStoresFromMDM extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:stores';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize Stores From MDM';

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
     * @return mixed
     */
    public function handle()
    {
        try{
            DB::beginTransaction();

            $this->info('[KERNEL-SERVICE] Start Synchronize Stores From MDM');
            Log::info("[KERNEL-SERVICE] Start Synchronize Stores From MDM");

            $this->info('[KERNEL-SERVICE] Get Data Stores From MDM');
            Log::info("[KERNEL-SERVICE] Get Data Stores From MDM");

            //get data store from mdm
            $storeMDMs = DB::connection('mdm')->table('stores')->select('*')->get();

            $this->info('[KERNEL-SERVICE] Truncate Table store_mdm');
            Log::info("[KERNEL-SERVICE] Truncate Table store_mdm");

            //truncate db oms in table store_mdm
            DB::statement('truncate table store_mdm restart identity');

            //insert into db oms in table store_mdm

            $store_mdm = array();
            foreach ($storeMDMs as $storeMDM){
                array_push($store_mdm, array("store_code" => $storeMDM->store_code, "initials_code" => $storeMDM->initials_code, "store_desc" => $storeMDM->store_desc, "start_date" => $storeMDM->start_date, "end_date" => $storeMDM->end_date, "store_status" => $storeMDM->store_status, "created_at" => $storeMDM->created_at, "updated_at" => $storeMDM->updated_at));
            }

            $this->info('[KERNEL-SERVICE] Insert Data Store to Table store_mdm');
            Log::info("[KERNEL-SERVICE] Insert Data Store to Table store_mdm");

            DB::table('store_mdm')->insert($store_mdm);

            $this->info('[KERNEL-SERVICE] Insert or Update Data Store from Table store_mdm to Table stores');
            Log::info("[KERNEL-SERVICE] Insert or Update Data Store from Table store_mdm to Table stores");

            DB::statement(
                "with get_store_mdm as (
                  select *
                  from store_mdm
                )
                INSERT INTO stores (store_code, initials_code, store_desc, store_status, created_at, updated_at)
                SELECT store_code, initials_code, store_desc, store_status, created_at, updated_at
                FROM get_store_mdm
                ON CONFLICT (store_code)
                DO UPDATE SET initials_code = excluded.initials_code, store_desc = excluded.store_desc, store_status = excluded.store_status, created_at = excluded.created_at, updated_at = excluded.updated_at"
            );

            $this->info('[KERNEL-SERVICE] Finish Synchronize Stores From MDM');
            Log::info("[KERNEL-SERVICE] Finish Synchronize Stores From MDM");

            DB::commit();
        }catch (Exception $e){
            DB::rollBack();

            $this->info('[KERNEL-SERVICE] Error and Transaction Rollback');
            Log::info("[KERNEL-SERVICE] Error and Transaction Rollback");
        }
    }
}
