<?php
namespace App\Filament\Admin\Resources\Products;

use App\Filament\Admin\Resources\Products\Pages\ListProducts;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Models\Restaurant;
use App\Models\Unit;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Schema as DbSchema;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string { return __('admin.nav.products'); }
    public static function getNavigationGroup(): ?string { return __('admin.nav.group_menu'); }
    public static function canViewAny(): bool { return true; }
    public static function canDelete(Model $record): bool { return true; }
    public static function canForceDelete(Model $record): bool { return true; }
    public static function canRestore(Model $record): bool { return true; }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['images', 'category'])
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([

            Group::make([
                Section::make(__('admin.product.section_restaurant'))->components([
                    Select::make('restaurant_id')
                        ->label(__('admin.nav.restaurants'))
                        ->options(Restaurant::withoutTrashed()->pluck('name', 'id'))
                        ->searchable()->required()
                        ->live(),
                    Select::make('category_id')
                        ->label(__('admin.nav.categories'))
                        ->options(fn () => Category::withoutTrashed()->where('status', 'active')->get()
                            ->mapWithKeys(fn ($c) => [$c->id => $c->name['uz'] ?? $c->name['en'] ?? $c->name['tr'] ?? '—']))
                        ->searchable()->required(),
                ]),

                Section::make(__('admin.product.section_price'))->components([
                    Grid::make(2)->components([
                        TextInput::make('price')->label(__('admin.product.price'))->numeric()->required(),
                        TextInput::make('original_price')->label(__('admin.product.original_price'))
                            ->numeric()->helperText(__('admin.product.original_price_hint')),
                    ]),
                    TextInput::make('sale')
                        ->label(__('admin.product.sale'))
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->step(0.1)
                        ->default(0)
                        ->suffix('%')
                        ->helperText(__('admin.product.sale_hint')),
                    Grid::make(2)->components([
                        Select::make(DbSchema::hasColumn('products', 'unit_id') ? 'unit_id' : 'unit')
                            ->label(__('admin.product.unit'))
                            ->options(fn () => Unit::active()->get()->mapWithKeys(fn ($u) => [
                                DbSchema::hasColumn('products', 'unit_id') ? $u->id : $u->slug =>
                                $u->name[app()->getLocale()] ?? $u->name['uz'] ?? $u->name['en'] ?? '—'
                            ]))->required(),
                        Toggle::make('is_available')->label(__('admin.product.available'))->default(true)->inline(false),
                    ]),
                ]),

                Section::make(__('admin.branch.section_availability'))
                    ->hidden(fn (Get $get) => ! \Illuminate\Support\Facades\Schema::hasTable('branches')
                        || ! Branch::withoutTrashed()->where('restaurant_id', $get('restaurant_id'))->exists())
                    ->components([
                        CheckboxList::make('branch_ids')
                            ->label(__('admin.branch.available_in'))
                            ->options(fn (Get $get) => \Illuminate\Support\Facades\Schema::hasTable('branches')
                                ? Branch::withoutTrashed()
                                    ->where('restaurant_id', $get('restaurant_id'))
                                    ->where('status', 'active')
                                    ->pluck('name', 'id')
                                    ->toArray()
                                : [])
                            ->columns(2)
                            ->helperText(__('admin.branch.availability_hint')),
                    ]),

                Section::make(__('admin.product.section_variants'))->components([
                    Repeater::make('variants')
                        ->label('')
                        ->schema([
                            TextInput::make('name.uz')->label(__('admin.category.name_uz'))->required(),
                            TextInput::make('name.en')->label(__('admin.category.name_en')),
                            TextInput::make('name.tr')->label(__('admin.category.name_tr')),
                            TextInput::make('price')->label(__('admin.product.price'))->numeric()->required(),
                            TextInput::make('sort_order')->label(__('admin.category.sort_order'))->numeric()->default(0),
                        ])
                        ->columns(2)
                        ->addActionLabel(__('admin.product.variant_add'))
                        ->defaultItems(0)
                        ->collapsible()
                        ->reorderableWithButtons()
                        ->cloneable(),
                ]),

                Section::make(__('admin.product.section_images'))->components([
                    FileUpload::make('uploaded_images')
                        ->label(__('admin.product.images'))
                        ->multiple()
                        ->minFiles(1)
                        ->maxFiles(3)
                        ->image()
                        ->disk('public')
                        ->directory('products')
                        ->maxSize(2048)
                        ->reorderable(),
                ]),
            ])->columnSpan(1),

            Section::make(__('admin.product.section_name'))->columnSpan(1)->components([
                TextInput::make('name.uz')->label(__('admin.category.name_uz'))->required(),
                TextInput::make('name.en')->label(__('admin.category.name_en')),
                TextInput::make('name.tr')->label(__('admin.category.name_tr')),
                Textarea::make('description.uz')->label(__('admin.product.desc_uz'))->rows(4),
                Textarea::make('description.en')->label(__('admin.product.desc_en'))->rows(4),
                Textarea::make('description.tr')->label(__('admin.product.desc_tr'))->rows(4),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images.path')->label(__('admin.product.image'))->circular()->limit(1)->disk('public'),
                TextColumn::make('restaurant.name')->label(__('admin.nav.restaurants'))->searchable()->sortable(),
                TextColumn::make('category_name')->label(__('admin.nav.categories'))
                    ->getStateUsing(fn (Product $record): string =>
                        $record->category?->name['uz'] ?? $record->category?->name['en'] ?? $record->category?->name['tr'] ?? '—'),
                TextColumn::make('name.uz')->label(__('admin.product.label_uz'))->searchable()->sortable(),
                TextColumn::make('price')->label(__('admin.product.price'))->money('UZS')->sortable(),
                TextColumn::make('sale')->label(__('admin.product.sale'))
                    ->suffix('%')
                    ->default('0%')
                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray'),
                TextColumn::make('unit')->label(__('admin.product.unit')),
                ToggleColumn::make('is_available')->label(__('admin.product.available')),
                TextColumn::make('deleted_at')->label(__('admin.common.deleted_at'))->dateTime('d.m.Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()->label(__('admin.common.trashed')),
                SelectFilter::make('restaurant_id')->label(__('admin.nav.restaurants'))
                    ->options(Restaurant::withoutTrashed()->pluck('name', 'id')),
            ])
            ->actions([
                EditAction::make()
                    ->label('')->tooltip(__('admin.common.edit'))
                    ->mutateRecordDataUsing(function (array $data, Product $record): array {
                        $data['uploaded_images'] = $record->images()->pluck('path')->toArray();
                        $data['branch_ids']      = $record->branches()->wherePivot('is_available', true)
                            ->pluck('branches.id')->toArray();
                        $data['variants']        = $record->variants()->orderBy('sort_order')->get()
                            ->map(fn ($v) => ['name' => $v->name, 'price' => $v->price, 'sort_order' => $v->sort_order])
                            ->toArray();
                        return $data;
                    })
                    ->after(function (Product $record, array $data): void {
                        $record->images()->delete();
                        foreach (array_values(array_filter($data['uploaded_images'] ?? [])) as $i => $path) {
                            $record->images()->create(['path' => $path, 'sort_order' => $i]);
                        }
                        ListProducts::syncBranches($record, $data);
                        ListProducts::syncVariants($record, $data);
                    }),
                RestoreAction::make()->label('')->tooltip(__('admin.common.restore')),
                DeleteAction::make()->label('')->tooltip(__('admin.common.delete')),
                ForceDeleteAction::make()->label('')->tooltip(__('admin.common.force_delete')),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label(__('admin.common.delete')),
                    RestoreBulkAction::make()->label(__('admin.common.restore')),
                    ForceDeleteBulkAction::make()->label(__('admin.common.force_delete')),
                ]),
            ])
            ->defaultSort('restaurant_id');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return ['index' => Pages\ListProducts::route('/')];
    }
}
