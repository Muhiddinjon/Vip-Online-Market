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
    protected static ?string $navigationLabel = 'Mahsulotlar';
    protected static string|\UnitEnum|null $navigationGroup = 'Menyu';
    protected static ?int $navigationSort = 2;

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
        $lang = fn ($get) => $get('lang') ?: 'uz';

        return $schema->components([
            Section::make('Restoran & Kategoriya')->components([
                Grid::make(2)->components([
                    Select::make('restaurant_id')
                        ->label('Restoran')
                        ->options(Restaurant::withoutTrashed()->pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                    Select::make('category_id')
                        ->label('Kategoriya')
                        ->options(fn () => Category::withoutTrashed()->where('status', 'active')->get()
                            ->mapWithKeys(fn ($c) => [$c->id => $c->name['uz'] ?? $c->name['en'] ?? $c->name['tr'] ?? '—']))
                        ->searchable()
                        ->required(),
                ]),
            ]),

            Section::make('Nomi va Tavsif')->components([
                TextInput::make('name.uz')->label('Nomi (UZ)')->required()->visible(fn($get) => $lang($get) === 'uz')->dehydratedWhenHidden(),
                TextInput::make('name.en')->label('Name (EN)')->visible(fn($get) => $lang($get) === 'en')->dehydratedWhenHidden(),
                TextInput::make('name.tr')->label('İsim (TR)')->visible(fn($get) => $lang($get) === 'tr')->dehydratedWhenHidden(),
                Textarea::make('description.uz')->label('Tavsif (UZ)')->rows(3)->visible(fn($get) => $lang($get) === 'uz')->dehydratedWhenHidden(),
                Textarea::make('description.en')->label('Description (EN)')->rows(3)->visible(fn($get) => $lang($get) === 'en')->dehydratedWhenHidden(),
                Textarea::make('description.tr')->label('Açıklama (TR)')->rows(3)->visible(fn($get) => $lang($get) === 'tr')->dehydratedWhenHidden(),
            ]),

            Section::make('Rasm')->components([
                FileUpload::make('image')
                    ->label('Mahsulot rasmi')
                    ->image()
                    ->disk('public')
                    ->directory('products')
                    ->maxSize(2048)
                    ->dehydrated(false),
            ]),

            Section::make('Narx & Sozlamalar')->components([
                Grid::make(3)->components([
                    TextInput::make('price')->label('Narxi (so\'m)')->numeric()->required(),
                    Select::make('unit')->label('Birlik')->options(['dona'=>'Dona','porsiya'=>'Porsiya','kg'=>'Kg','gramm'=>'Gramm','litr'=>'Litr'])->default('dona')->required(),
                    Toggle::make('is_available')->label('Mavjud')->default(true)->inline(false),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('restaurant.name')->label('Restoran')->searchable()->sortable(),
                TextColumn::make('category.name.uz')->label('Kategoriya')->sortable(),
                TextColumn::make('name.uz')->label('Mahsulot (UZ)')->searchable()->sortable(),
                TextColumn::make('name.tr')->label('TR')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('price')->label('Narxi')->money('UZS')->sortable(),
                TextColumn::make('unit')->label('Birlik'),
                ToggleColumn::make('is_available')->label('Mavjud'),
                TextColumn::make('deleted_at')->label('O\'chirilgan')->dateTime('d.m.Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()->label('O\'chirilganlar'),
                SelectFilter::make('restaurant_id')->label('Restoran')
                    ->options(Restaurant::withoutTrashed()->pluck('name', 'id')),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()->label('Tahrirlash'),
                    RestoreAction::make()->label('Tiklash'),
                    DeleteAction::make()->label('O\'chirish'),
                    ForceDeleteAction::make()->label('Butunlay o\'chirish'),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('O\'chirish'),
                    RestoreBulkAction::make()->label('Tiklash'),
                    ForceDeleteBulkAction::make()->label('Butunlay o\'chirish'),
                ]),
            ])
            ->defaultSort('restaurant_id');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
