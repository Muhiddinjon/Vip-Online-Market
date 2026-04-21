<?php
namespace App\Filament\Admin\Resources\Restaurants;

use App\Models\Restaurant;
use Filament\Actions\Action;
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
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RestaurantResource extends Resource
{
    protected static ?string $model = Restaurant::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('admin.nav.restaurants');
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
        $isCreate = ! ($schema->getLivewire()?->record instanceof Restaurant);
        $lang = fn ($get) => $get('lang') ?: 'uz';

        return $schema->components([
            Section::make(__('admin.restaurant.section_login'))->components([
                Grid::make(2)->components([
                    TextInput::make('email')
                        ->label('Email')->email()
                        ->required($isCreate)
                        ->unique('users', 'email', ignoreRecord: true),
                    TextInput::make('password')
                        ->label(__('admin.user.password'))->password()
                        ->required($isCreate)->minLength(8)
                        ->placeholder($isCreate ? '' : __('admin.user.password_hint'))
                        ->visible($isCreate),
                ]),
            ]),

            Section::make(__('admin.restaurant.section_main'))->components([
                Grid::make(2)->components([
                    TextInput::make('name')->label(__('admin.restaurant.name'))->required(),
                    TextInput::make('phone')->label(__('admin.courier.phone'))->tel(),
                ]),
                Select::make('status')->label(__('admin.common.status'))->options([
                    'active'   => __('admin.common.active'),
                    'inactive' => __('admin.common.inactive'),
                    'blocked'  => __('admin.common.blocked'),
                ])->required()->default('active'),
            ]),

            Section::make(__('admin.restaurant.section_description'))->components([
                Textarea::make('description.uz')->label(__('admin.category.name_uz'))->rows(3)->visible(fn($get) => $lang($get) === 'uz'),
                Textarea::make('description.en')->label(__('admin.category.name_en'))->rows(3)->visible(fn($get) => $lang($get) === 'en'),
                Textarea::make('description.tr')->label(__('admin.category.name_tr'))->rows(3)->visible(fn($get) => $lang($get) === 'tr'),
            ]),

            Section::make(__('admin.restaurant.section_address'))->components([
                TextInput::make('address')->label(__('admin.restaurant.address')),
                Grid::make(2)->components([
                    TextInput::make('lat')->label('Latitude')->numeric()->readOnly(),
                    TextInput::make('lng')->label('Longitude')->numeric()->readOnly(),
                ]),
            ]),

            Section::make(__('admin.restaurant.section_images'))->components([
                Grid::make(2)->components([
                    FileUpload::make('logo')->label('Logo')->image()->directory('restaurants/logos'),
                    FileUpload::make('cover_image')->label(__('admin.restaurant.cover'))->image()->directory('restaurants/covers'),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')->label('')->circular(),
                TextColumn::make('name')->label(__('admin.restaurant.label'))->searchable()->sortable(),
                TextColumn::make('user.email')->label('Email')->searchable(),
                TextColumn::make('phone')->label(__('admin.courier.phone')),
                TextColumn::make('address')->label(__('admin.restaurant.address'))->limit(30)->default('—'),
                TextColumn::make('status')->label(__('admin.common.status'))->badge()
                    ->color(fn ($state) => match($state) {
                        'active'   => 'success', 'inactive' => 'warning', 'blocked'  => 'danger', default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'active'   => __('admin.common.active'),
                        'inactive' => __('admin.common.inactive'),
                        'blocked'  => __('admin.common.blocked'),
                        default    => $state,
                    }),
                TextColumn::make('orders_count')->label(__('admin.restaurant.orders'))->counts('orders')->sortable(),
                TextColumn::make('deleted_at')->label(__('admin.common.deleted_at'))->dateTime('d.m.Y')->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()->label(__('admin.common.trashed')),
                SelectFilter::make('status')->label(__('admin.common.status'))->options([
                    'active'   => __('admin.common.active'),
                    'inactive' => __('admin.common.inactive'),
                    'blocked'  => __('admin.common.blocked'),
                ]),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()
                        ->label(__('admin.common.edit'))
                        ->mutateRecordDataUsing(function (array $data, Restaurant $record): array {
                            $data['email'] = $record->user?->email ?? '';
                            return $data;
                        })
                        ->using(function (Restaurant $record, array $data): Restaurant {
                            if (!empty($data['email'])) {
                                $record->user?->update([
                                    'name'  => $data['name'],
                                    'email' => $data['email'],
                                ]);
                            }
                            $record->update([
                                'name'        => $data['name'],
                                'description' => $data['description'] ?? null,
                                'address'     => $data['address'] ?? null,
                                'lat'         => $data['lat'] ?? null,
                                'lng'         => $data['lng'] ?? null,
                                'logo'        => $data['logo'] ?? $record->logo,
                                'cover_image' => $data['cover_image'] ?? $record->cover_image,
                                'phone'       => $data['phone'] ?? null,
                                'status'      => $data['status'],
                            ]);
                            return $record;
                        }),
                    Action::make('block')
                        ->label(__('admin.common.block'))
                        ->icon('heroicon-o-no-symbol')->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => $record->status !== 'blocked' && !$record->trashed())
                        ->action(fn ($record) => $record->update(['status' => 'blocked'])),
                    Action::make('activate')
                        ->label(__('admin.common.activate'))
                        ->icon('heroicon-o-check-circle')->color('success')
                        ->visible(fn ($record) => $record->status === 'blocked' && !$record->trashed())
                        ->action(fn ($record) => $record->update(['status' => 'active'])),
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
            ]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRestaurants::route('/'),
        ];
    }
}
