<?php
namespace App\Filament\Admin\Resources\Categories;

use App\Models\Category;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Kategoriyalar';
    protected static string|\UnitEnum|null $navigationGroup = 'Menyu';
    protected static ?int $navigationSort = 1;

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
            Section::make('Kategoriya')->components([
                TextInput::make('name.uz')->label('Nomi (UZ)')->required()->visible(fn($get) => $lang($get) === 'uz')->dehydratedWhenHidden(),
                TextInput::make('name.en')->label('Name (EN)')->visible(fn($get) => $lang($get) === 'en')->dehydratedWhenHidden(),
                TextInput::make('name.tr')->label('İsim (TR)')->visible(fn($get) => $lang($get) === 'tr')->dehydratedWhenHidden(),
                Grid::make(2)->components([
                    TextInput::make('sort_order')->label('Tartib')->numeric()->default(0),
                    Select::make('status')->label('Holat')->options([
                        'active'   => 'Faol',
                        'inactive' => 'Nofaol',
                    ])->default('active')->required(),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name.uz')->label('Kategoriya')
                    ->getStateUsing(fn ($record) => $record->name['uz'] ?? $record->name['en'] ?? $record->name['tr'] ?? '—')
                    ->searchable()->sortable(),
                TextColumn::make('name.en')->label('EN')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name.tr')->label('TR')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('products_count')->label('Mahsulotlar')->counts('products')->sortable(),
                TextColumn::make('sort_order')->label('Tartib')->sortable(),
                TextColumn::make('status')->label('Holat')->badge()
                    ->color(fn ($state) => $state === 'active' ? 'success' : 'gray')
                    ->formatStateUsing(fn ($state) => $state === 'active' ? 'Faol' : 'Nofaol'),
                TextColumn::make('deleted_at')->label('O\'chirilgan')->dateTime('d.m.Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()->label('O\'chirilganlar'),
                SelectFilter::make('status')->label('Holat')
                    ->options(['active' => 'Faol', 'inactive' => 'Nofaol']),
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
            ->defaultSort('sort_order');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit'   => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
