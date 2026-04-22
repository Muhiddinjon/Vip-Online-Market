<?php
namespace App\Filament\Admin\Resources\Products;

use App\Models\Category;
use App\Models\Product;
use App\Models\Restaurant;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('admin.nav.products');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.group_menu');
    }

    public static function canViewAny(): bool { return true; }
    public static function canDelete(Model $record): bool { return true; }
    public static function canForceDelete(Model $record): bool { return true; }
    public static function canRestore(Model $record): bool { return true; }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function form(Schema $schema): Schema
    {
        // 3-column grid: left Group spans 1 col, right Section spans 2 cols
        return $schema->columns(2)->components([

            Group::make([
                Section::make(__('admin.product.section_restaurant'))->components([
                    Select::make('restaurant_id')
                        ->label(__('admin.nav.restaurants'))
                        ->options(Restaurant::withoutTrashed()->pluck('name', 'id'))
                        ->searchable()->required(),
                    Select::make('category_id')
                        ->label(__('admin.nav.categories'))
                        ->options(fn () => Category::withoutTrashed()->where('status', 'active')->get()
                            ->mapWithKeys(fn ($c) => [$c->id => $c->name['uz'] ?? $c->name['en'] ?? $c->name['tr'] ?? '—']))
                        ->searchable()->required(),
                ]),

                Section::make(__('admin.product.section_price'))->components([
                    Grid::make(2)->components([
                        TextInput::make('price')
                            ->label(__('admin.product.price'))
                            ->numeric()->required(),
                        // dehydrated(false) until DB migration runs (original_price column)
                        TextInput::make('original_price')
                            ->label(__('admin.product.original_price'))
                            ->numeric()
                            ->dehydrated(false)
                            ->helperText(__('admin.product.original_price_hint')),
                    ]),
                    Grid::make(2)->components([
                        Select::make('unit')
                            ->label(__('admin.product.unit'))
                            ->options([
                                'dona'    => __('admin.product.unit_dona'),
                                'porsiya' => __('admin.product.unit_porsiya'),
                                'kg'      => 'Kg',
                                'gramm'   => __('admin.product.unit_gramm'),
                                'litr'    => __('admin.product.unit_litr'),
                            ])->default('dona')->required(),
                        Toggle::make('is_available')
                            ->label(__('admin.product.available'))
                            ->default(true)->inline(false),
                    ]),
                ]),

                Section::make(__('admin.product.section_images'))->components([
                    FileUpload::make('images')
                        ->label(__('admin.product.images'))
                        ->multiple()
                        ->maxFiles(3)
                        ->image()
                        ->disk('public')
                        ->directory('products')
                        ->maxSize(2048)
                        ->reorderable()
                        ->dehydrated(false),
                ]),
            ])->columnSpan(1),

            Section::make(__('admin.product.section_name'))
                ->columnSpan(1)
                ->components([
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
                TextColumn::make('restaurant.name')->label(__('admin.nav.restaurants'))->searchable()->sortable(),
                TextColumn::make('category.name.uz')->label(__('admin.nav.categories'))->sortable(),
                TextColumn::make('name.uz')->label(__('admin.product.label_uz'))->searchable()->sortable(),
                TextColumn::make('price')->label(__('admin.product.price'))->money('UZS')->sortable(),
                TextColumn::make('original_price')->label(__('admin.product.original_price'))
                    ->money('UZS')->sortable()->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
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
                ActionGroup::make([
                    EditAction::make()
                        ->label(__('admin.common.edit'))
                        ->mutateRecordDataUsing(function (array $data, Product $record): array {
                            $data['images'] = $record->images()->pluck('path')->toArray();
                            return $data;
                        })
                        ->after(function (Product $record, array $data): void {
                            $record->images()->delete();
                            foreach ($data['images'] ?? [] as $i => $path) {
                                if ($path) {
                                    $record->images()->create(['path' => $path, 'sort_order' => $i]);
                                }
                            }
                        }),
                    RestoreAction::make()->label(__('admin.common.restore')),
                    DeleteAction::make()->label(__('admin.common.delete')),
                    ForceDeleteAction::make()->label(__('admin.common.force_delete')),
                ]),
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
        return [
            'index' => Pages\ListProducts::route('/'),
        ];
    }
}
