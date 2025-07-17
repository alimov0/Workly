<?php

namespace App\Observers;

use Exception;
use Illuminate\Support\Str;

class CategoryObserver
{
    public function creating(Category $category)
    {
        // Slug generatsiyasi
        $category->slug = $this->generateUniqueSlug($category->title);
        
        // Agar parent kategoriya bo'lmasa, null qilish
        if ($category->parent_id === 0) {
            $category->parent_id = null;
        }
    }

    public function updating(Category $category)
    {
        // Agar title o'zgartirilgan bo'lsa, yangi slug generatsiya qilish
        if ($category->isDirty('title')) {
            $category->slug = $this->generateUniqueSlug($category->title, $category->id);
        }
        
        // O'zini o'ziga parent qilib qo'yishdan himoya qilish
        if ($category->parent_id == $category->id) {
            $category->parent_id = $category->getOriginal('parent_id');
        }
    }

    public function deleting(Category $category)
    {
        // Kategoriyaga tegishli vakansiyalar bo'lsa, o'chirishga yo'l qo'ymaslik
        if ($category->vacancies()->count() > 0) {
            throw new Exception("Cannot delete category with active vacancies");
        }

        // Child kategoriyalarni o'chirish
        $category->children()->each(function ($child) {
            $child->delete();
        });
    }

    public function restored(Category $category)
    {
        // Child kategoriyalarni restore qilish
        $category->children()->withTrashed()->get()->each(function ($child) {
            $child->restore();
        });
    }

    private function generateUniqueSlug(string $title, int $id = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (Category::where('slug', $slug)
            ->when($id, function ($query) use ($id) {
                $query->where('id', '!=', $id);
            })
            ->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}

