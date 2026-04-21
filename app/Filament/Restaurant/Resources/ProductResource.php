<?php
namespace App\Filament\Restaurant\Resources;

use App\Filament\Restaurant\Resources\ProductResource\Pages\CreateProduct;
use App\Filament\Restaurant\Resources\ProductResource\Pages\EditProduct;
use App\Filament\Restaurant\Resources\ProductResource\Pages\ListProducts;
use App\Models\Category;
use App\Models\Product;
use Filament\Actions\EditAction;
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
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Mahsulotlar';
    protected static ?string $modelLabel = 'Mahsulot';
    protected static ?string $pluralModelLabel = 'Mahsulotlar';
    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('restaurant_id', auth()->user()?->restaurant?->id);
    }

    public static function form(Schema $schema): Schema
    {
        $lang = fn ($get) => $get('lang') ?: 'uz';

        return $schema->components([
            Section::make('Kategoriya')->components([
                Select::make('category_id')
                    ->label('Kategoriya')
                    ->options(fn () => Category::where('status', 'active')->get()
                        ->mapWithKeys(fn ($c) => [$c->id => $c->name['uz'] ?? $c->name['en'] ?? $c->name['tr'] ?? '—']))
                    ->required(),
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
                TextColumn::make('name.uz')->label('Mahsulot (UZ)')->searchable()->sortable(),
                TextColumn::make('name.tr')->label('TR')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('category.name.uz')->label('Kategoriya')->sortable(),
                TextColumn::make('price')->label('Narxi')->money('UZS')->sortable(),
                TextColumn::make('unit')->label('Birlik'),
                ToggleColumn::make('is_available')->label('Mavjud'),
            ])
            ->actions([EditAction::make()->label('Tahrirlash')])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit'   => EditProduct::route('/{record}/edit'),
        ];
    }
}
