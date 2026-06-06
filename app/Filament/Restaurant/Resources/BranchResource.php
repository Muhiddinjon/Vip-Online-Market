<?php
namespace App\Filament\Restaurant\Resources;

use App\Filament\Restaurant\Resources\BranchResource\Pages\ListBranches;
use App\Models\Branch;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('admin.nav.branches');
    }

    public static function canViewAny(): bool
    {
        return \Illuminate\Support\Facades\Schema::hasTable('branches');
    }

    public static function canCreate(): bool { return true; }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('restaurant_id', auth()->user()?->restaurant?->id);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('admin.branch.section_main'))->components([
                Grid::make(2)->components([
                    TextInput::make('name')->label(__('admin.branch.name'))->required(),
                    TextInput::make('phone')->label(__('admin.courier.phone'))->tel(),
                ]),
                Grid::make(2)->components([
                    Select::make('status')->label(__('admin.common.status'))->options([
                        'active'   => __('admin.common.active'),
                        'inactive' => __('admin.common.inactive'),
                    ])->required()->default('active'),
                    TextInput::make('queue')->label(__('admin.restaurant.queue'))->numeric()->default(0),
                ]),
            ]),

            Section::make(__('admin.restaurant.section_address'))->components([
                TextInput::make('address')->label(__('admin.restaurant.address'))->columnSpanFull(),
                View::make('filament.components.maps-picker'),
                Grid::make(2)->components([
                    TextInput::make('lat')->label('Latitude')->numeric()->readOnly()
                        ->extraInputAttributes(['data-map-lat' => 'true']),
                    TextInput::make('lng')->label('Longitude')->numeric()->readOnly()
                        ->extraInputAttributes(['data-map-lng' => 'true']),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('admin.branch.name'))->searchable()->sortable(),
                TextColumn::make('address')->label(__('admin.restaurant.address'))->limit(35)->default('—'),
                TextColumn::make('phone')->label(__('admin.courier.phone'))->default('—'),
                TextColumn::make('status')->label(__('admin.common.status'))->badge()
                    ->color(fn ($state) => $state === 'active' ? 'success' : 'warning')
                    ->formatStateUsing(fn ($state) => $state === 'active' ? __('admin.common.active') : __('admin.common.inactive')),
                TextColumn::make('queue')->label(__('admin.restaurant.queue'))->sortable(),
                TextColumn::make('products_count')->label(__('admin.branch.products_count'))
                    ->counts('products')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->label(__('admin.common.status'))->options([
                    'active'   => __('admin.common.active'),
                    'inactive' => __('admin.common.inactive'),
                ]),
            ])
            ->actions([
                EditAction::make()->label('')->tooltip(__('admin.common.edit')),
                DeleteAction::make()->label('')->tooltip(__('admin.common.delete')),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label(__('admin.common.delete')),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => ListBranches::route('/')];
    }
}
