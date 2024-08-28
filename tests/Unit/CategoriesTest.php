<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoriesTest extends TestCase
{
   use RefreshDatabase;

   public function setup(): void
   {
       parent::setUp();
   }
    public function test_category_name_input(): void
    {
        $user = User::factory()->create();

        $data = [
            'categoryName' => 'test category name',
        ];

        $response = $this->actingAs($user)->post('/categories',  $data);

        $response->assertSessionHas('success', 'Category created successfully!');


    }

    public function test_delete_category(): void
    {
        \Artisan::call('cache:clear');

        $user = User::factory()->create();
        $category = Category::factory()->create();


        $response = $this->actingAs($user)->delete('/categories/'.  $category);


        $this->assertDatabaseMissing('categories', ['id' => $category->id]);



    }


}
