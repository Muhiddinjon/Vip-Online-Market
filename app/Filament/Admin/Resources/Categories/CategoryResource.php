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
    protected static ?string $navigationLabel = null;
    protected static string|\UnitEnum|null $navigationGroup = null;
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('admin.nav.categories');
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
        $lang = fn ($get) => $get('lang') ?: 'uz';

        return $schema->components([
            Section::make(__('admin.category.section_title'))->components([
                TextInput::make('name.uz')->label(__('admin.category.name_uz'))->required()->visible(fn($get) => $lang($get) === 'uz')->dehydratedWhenHidden(),
                TextInput::make('name.en')->label(__('admin.category.name_en'))->visible(fn($get) => $lang($get) === 'en')->dehydratedWhenHidden(),
                TextInput::make('name.tr')->label(__('admin.category.name_tr'))->visible(fn($get) => $lang($get) === 'tr')->dehydratedWhenHidden(),
                Grid::make(2)->components([
                    TextInput::make('sort_order')->label(__('admin.category.sort_order'))->numeric()->default(0),
                    Select::make('status')->label(__('admin.common.status'))->options([
                        'active'   => __('admin.common.active'),
                        'inactive' => __('admin.common.inactive'),
                    ])->default('active')->required(),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name.uz')->label(__('admin.category.label'))
                    ->getStateUsing(fn ($record) => $record->name['uz'] ?? $record->name['en'] ?? $record->name['tr'] ?? '—')
                    ->searchable()->sortable(),
                TextColumn::make('name.en')->label('EN')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name.tr')->label('TR')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('products_count')->label(__('admin.category.products'))->counts('products')->sortable(),
                TextColumn::make('sort_order')->label(__('admin.category.sort_order'))->sortable(),
                TextColumn::make('status')->label(__('admin.common.status'))->badge()
                    ->color(fn ($state) => $state === 'active' ? 'success' : 'gray')
                    ->formatStateUsing(fn ($state) => $state === 'active' ? __('admin.common.active') : __('admin.common.inactive')),
                TextColumn::make('deleted_at')->label(__('admin.common.deleted_at'))->dateTime('d.m.Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()->label(__('admin.common.trashed')),
                SelectFilter::make('status')->label(__('admin.common.status'))
                    ->options(['active' => __('admin.common.active'), 'inactive' => __('admin.common.inactive')]),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()->label(__('admin.common.edit')),
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
            ->defaultSort('sort_order');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
        ];
    }
}
