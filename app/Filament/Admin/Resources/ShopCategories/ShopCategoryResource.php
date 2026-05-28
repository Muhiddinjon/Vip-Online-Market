<?php
namespace App\Filament\Admin\Resources\ShopCategories;

use App\Models\ShopCategory;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShopCategoryResource extends Resource
{
    protected static ?string $model = ShopCategory::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?int $navigationSort = 0;

    public static function getNavigationLabel(): string
    {
        return __('admin.nav.shop_categories');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.group_management');
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
        return $schema->components([
            Section::make(__('admin.shop_category.section_main'))->components([
                TextInput::make('name.uz')->label(__('admin.category.name_uz'))->required(),
                TextInput::make('name.en')->label(__('admin.category.name_en')),
                TextInput::make('name.tr')->label(__('admin.category.name_tr')),
                Grid::make(2)->components([
                    TextInput::make('badge_text')->label(__('admin.shop_category.badge_text')),
                    TextInput::make('sort_order')->label(__('admin.category.sort_order'))->numeric()->default(0),
                ]),
                Toggle::make('status')
                    ->label(__('admin.common.status'))
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger'),
            ]),
            Section::make(__('admin.shop_category.section_image'))->components([
                FileUpload::make('image')
                    ->label(__('admin.shop_category.image'))
                    ->image()
                    ->directory('shop-categories')
                    ->maxSize(2048),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->label('')->circular()->size(40),
                TextColumn::make('name.uz')
                    ->label(__('admin.shop_category.label'))
                    ->getStateUsing(fn ($record) => $record->name['uz'] ?? $record->name['en'] ?? $record->name['tr'] ?? '—')
                    ->searchable()->sortable(),
                TextColumn::make('badge_text')->label(__('admin.shop_category.badge_text'))->default('—'),
                TextColumn::make('sort_order')->label(__('admin.category.sort_order'))->sortable(),
                ToggleColumn::make('status')
                    ->label(__('admin.common.status'))
                    ->onColor('success')
                    ->offColor('danger'),
                TextColumn::make('deleted_at')->label(__('admin.common.deleted_at'))->dateTime('d.m.Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()->label(__('admin.common.trashed')),
            ])
            ->actions([
                EditAction::make()->label('')->tooltip(__('admin.common.edit')),
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
            ->defaultSort('sort_order');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShopCategories::route('/'),
        ];
    }
}
