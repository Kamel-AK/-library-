<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;

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
    }
}
