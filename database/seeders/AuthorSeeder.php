<?php

namespace Database\Seeders;
use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $authors = [
            ['name' => 'نجيب محفوظ'],
            ['name' => 'طه حسين'],
            ['name' => 'جبران خليل جبران'],
            ['name' => 'مصطفى لطفي المنفلوطي'],
            ['name' => 'أحلام مستغانمي'],
        ];
        foreach ($authors as $author) {
            Author::create($author);
        }

        Author::factory(10)->create();

    }
}
