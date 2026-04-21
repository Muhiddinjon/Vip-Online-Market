<?php
namespace App\Filament\Restaurant\Resources;

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
    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('admin.nav.products');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('restaurant_id', auth()->user()?->restaurant?->id);
    }

    public static function form(Schema $schema): Schema
    {
        $lang = fn ($get) => $get('lang') ?: 'uz';

        return $schema->components([
            Section::make(__('admin.nav.categories'))->components([
                Select::make('category_id')
                    ->label(__('admin.nav.categories'))
                    ->options(fn () => Category::where('status', 'active')->get()
                        ->mapWithKeys(fn ($c) => [$c->id => $c->name['uz'] ?? $c->name['en'] ?? $c->name['tr'] ?? '—']))
                    ->required(),
            ]),

            Section::make(__('admin.product.section_name'))->components([
                TextInput::make('name.uz')->label(__('admin.category.name_uz'))->required()->visible(fn($get) => $lang($get) === 'uz')->dehydratedWhenHidden(),
                TextInput::make('name.en')->label(__('admin.category.name_en'))->visible(fn($get) => $lang($get) === 'en')->dehydratedWhenHidden(),
                TextInput::make('name.tr')->label(__('admin.category.name_tr'))->visible(fn($get) => $lang($get) === 'tr')->dehydratedWhenHidden(),
                Textarea::make('description.uz')->label(__('admin.product.desc_uz'))->rows(3)->visible(fn($get) => $lang($get) === 'uz')->dehydratedWhenHidden(),
                Textarea::make('description.en')->label(__('admin.product.desc_en'))->rows(3)->visible(fn($get) => $lang($get) === 'en')->dehydratedWhenHidden(),
                Textarea::make('description.tr')->label(__('admin.product.desc_tr'))->rows(3)->visible(fn($get) => $lang($get) === 'tr')->dehydratedWhenHidden(),
            ]),

            Section::make(__('admin.product.section_image'))->components([
                FileUpload::make('image')->label(__('admin.product.image'))
                    ->image()->disk('public')->directory('products')->maxSize(2048)->dehydrated(false),
            ]),

            Section::make(__('admin.product.section_price'))->components([
                Grid::make(3)->components([
                    TextInput::make('price')->label(__('admin.product.price'))->numeric()->required(),
                    Select::make('unit')->label(__('admin.product.unit'))->options([
                        'dona'    => __('admin.product.unit_dona'),
                        'porsiya' => __('admin.product.unit_porsiya'),
                        'kg'      => 'Kg',
                        'gramm'   => __('admin.product.unit_gramm'),
                        'litr'    => __('admin.product.unit_litr'),
                    ])->default('dona')->required(),
                    Toggle::make('is_available')->label(__('admin.product.available'))->default(true)->inline(false),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name.uz')->label(__('admin.product.label_uz'))->searchable()->sortable(),
                TextColumn::make('name.tr')->label('TR')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('category.name.uz')->label(__('admin.nav.categories'))->sortable(),
                TextColumn::make('price')->label(__('admin.product.price'))->money('UZS')->sortable(),
                TextColumn::make('unit')->label(__('admin.product.unit')),
                ToggleColumn::make('is_available')->label(__('admin.product.available')),
            ])
            ->actions([
                EditAction::make()
                    ->label(__('admin.common.edit'))
                    ->mutateRecordDataUsing(function (array $data, Product $record): array {
                        $data['image'] = $record->images()->first()?->path;
                        return $data;
                    })
                    ->after(function (Product $record, array $data): void {
                        $path = $data['image'] ?? null;
                        $record->images()->delete();
                        if ($path) {
                            $record->images()->create(['path' => $path, 'sort_order' => 0]);
                        }
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
        ];
    }
}
