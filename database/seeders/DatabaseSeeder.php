<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seeder as SeederModel;

class DatabaseSeeder extends Seeder
{
    protected $classes = [];
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->populate();
        foreach ($this->classes as $class => $name) {
            $seed = SeederModel::where('class', $class)->exists();
            if ($seed) continue ;

            $object = (object)$name;
            $this->call($object->scalar);
            SeederModel::create(['class' => $name]);
        }
    }

    function populate() {
        $this->classes = [
            AddUserAdminSeeder::class
        ];
    }
}
