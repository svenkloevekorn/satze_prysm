<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property int $id
 * @property ?int $parent_id
 * @property string $name
 * @property string $slug
 */
#[Fillable(['name', 'slug', 'description', 'is_active', 'sort_order', 'parent_id'])]
class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    use LogsActivity;

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    public function qualityCriteria(): BelongsToMany
    {
        return $this->belongsToMany(QualityCriterion::class);
    }

    public function competitorProducts(): HasMany
    {
        return $this->hasMany(CompetitorProduct::class);
    }

    public function isTopLevel(): bool
    {
        return $this->parent_id === null;
    }

    public function fullName(): string
    {
        /** @var ?Category $parent */
        $parent = $this->parent;

        return $parent ? "{$parent->name} › {$this->name}" : $this->name;
    }

    protected static function booted(): void
    {
        static::saving(function (Category $category) {
            if (blank($category->slug)) {
                $category->slug = Str::slug($category->name);
            }

            // Constraint: max 2 Ebenen (Oberkategorie + Unterkategorie)
            if ($category->parent_id) {
                if ($category->parent_id === $category->id) {
                    throw ValidationException::withMessages([
                        'parent_id' => 'Eine Kategorie kann nicht ihr eigener Parent sein.',
                    ]);
                }

                $parent = self::find($category->parent_id);
                if ($parent && $parent->parent_id !== null) {
                    throw ValidationException::withMessages([
                        'parent_id' => 'Maximal 2 Ebenen erlaubt – diese Kategorie ist bereits eine Unterkategorie.',
                    ]);
                }

                // Wenn dieses Category schon Kinder hat, darf es nicht selbst Kind werden
                if ($category->exists && $category->children()->exists()) {
                    throw ValidationException::withMessages([
                        'parent_id' => 'Diese Kategorie hat bereits Unterkategorien und kann selbst keine Unterkategorie werden.',
                    ]);
                }
            }
        });
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }
}
