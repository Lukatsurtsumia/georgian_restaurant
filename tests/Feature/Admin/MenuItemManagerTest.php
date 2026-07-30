<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\MenuItemManager;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MenuItemManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_menu_item(): void
    {
        $admin = User::factory()->create();
        $category = MenuCategory::create(['name_en' => 'Starters', 'name_fr' => 'Entrées', 'slug' => 'starters']);

        Livewire::actingAs($admin)
            ->test(MenuItemManager::class)
            ->call('create')
            ->set('menu_category_id', $category->id)
            ->set('name_en', 'Test Dish')
            ->set('name_fr', 'Plat Test')
            ->set('price', '12.50')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('menu_items', ['name_en' => 'Test Dish', 'menu_category_id' => $category->id]);
    }

    public function test_creating_an_item_without_a_category_fails_validation(): void
    {
        $admin = User::factory()->create();

        Livewire::actingAs($admin)
            ->test(MenuItemManager::class)
            ->call('create')
            ->set('name_en', 'Orphan Dish')
            ->set('name_fr', 'Plat Orphelin')
            ->set('price', '9.00')
            ->call('save')
            ->assertHasErrors('menu_category_id');

        $this->assertDatabaseMissing('menu_items', ['name_en' => 'Orphan Dish']);
    }

    public function test_admin_can_edit_an_existing_item(): void
    {
        $admin = User::factory()->create();
        $category = MenuCategory::create(['name_en' => 'Starters', 'name_fr' => 'Entrées', 'slug' => 'starters']);
        $item = MenuItem::create([
            'menu_category_id' => $category->id,
            'name_en' => 'Old Name', 'name_fr' => 'Ancien Nom', 'price' => 10,
        ]);

        Livewire::actingAs($admin)
            ->test(MenuItemManager::class)
            ->call('edit', $item->id)
            ->assertSet('name_en', 'Old Name')
            ->set('name_en', 'New Name')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('New Name', $item->fresh()->name_en);
    }

    public function test_admin_can_delete_an_item(): void
    {
        $admin = User::factory()->create();
        $category = MenuCategory::create(['name_en' => 'Starters', 'name_fr' => 'Entrées', 'slug' => 'starters']);
        $item = MenuItem::create([
            'menu_category_id' => $category->id,
            'name_en' => 'Doomed Dish', 'name_fr' => 'Plat Condamné', 'price' => 10,
        ]);

        Livewire::actingAs($admin)
            ->test(MenuItemManager::class)
            ->call('delete', $item->id);

        $this->assertDatabaseMissing('menu_items', ['id' => $item->id]);
    }
}
