<?php
namespace App\Filament\Admin\Resources\Couriers;

use App\Models\Courier;
use App\Models\User;
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
use Illuminate\Support\Facades\DB;

class CourierResource extends Resource
{
    protected static ?string $model = Courier::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-truck';
    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('admin.nav.couriers');
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
        $isCreate = ! ($schema->getLivewire()?->record instanceof Courier);

        return $schema->components([
            Section::make(__('admin.courier.section_personal'))->components([
                Grid::make(2)->components([
                    TextInput::make('name')->label(__('admin.courier.name'))->required(),
                    TextInput::make('phone')->label(__('admin.courier.phone'))->tel()->required(),
                ]),
                TextInput::make('password')->label(__('admin.user.password'))->password()
                    ->required($isCreate)->minLength(6)
                    ->visible($isCreate),
            ]),
            Section::make(__('admin.courier.section_details'))->components([
                Grid::make(2)->components([
                    Select::make('vehicle_type')->label(__('admin.courier.vehicle_type'))->options([
                        'bike'    => __('admin.courier.vehicle_bike'),
                        'scooter' => __('admin.courier.vehicle_scooter'),
                        'car'     => __('admin.courier.vehicle_car'),
                        'other'   => __('admin.courier.vehicle_other'),
                    ])->required()->default('bike'),
                    TextInput::make('plate_number')->label(__('admin.courier.plate_number')),
                ]),
                Select::make('status')->label(__('admin.common.status'))->options([
                    'available' => __('admin.courier.status_available'),
                    'busy'      => __('admin.courier.status_busy'),
                    'offline'   => __('admin.courier.status_offline'),
                ])->default('offline'),
                FileUpload::make('avatar')->label(__('admin.courier.avatar'))->image()->directory('couriers'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')->label('')->circular(),
                TextColumn::make('user.name')->label(__('admin.courier.name'))->searchable()->sortable(),
                TextColumn::make('user.phone')->label(__('admin.courier.phone'))->searchable(),
                TextColumn::make('vehicle_type')->label(__('admin.courier.vehicle_type'))->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'bike'    => __('admin.courier.vehicle_bike'),
                        'scooter' => __('admin.courier.vehicle_scooter'),
                        'car'     => __('admin.courier.vehicle_car'),
                        default   => __('admin.courier.vehicle_other'),
                    }),
                TextColumn::make('plate_number')->label(__('admin.courier.plate_number')),
                TextColumn::make('status')->label(__('admin.common.status'))->badge()
                    ->color(fn ($state) => match($state) {
                        'available' => 'success', 'busy' => 'warning', 'offline' => 'gray', default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'available' => __('admin.courier.status_available'),
                        'busy'      => __('admin.courier.status_busy'),
                        'offline'   => __('admin.courier.status_offline'),
                        default     => $state,
                    }),
                TextColumn::make('user.status')->label(__('admin.courier.account'))->badge()
                    ->color(fn ($state) => $state === 'active' ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state === 'active' ? __('admin.common.active') : __('admin.common.blocked')),
                TextColumn::make('orders_count')->label(__('admin.courier.orders'))->counts('orders')->sortable(),
                TextColumn::make('deleted_at')->label(__('admin.common.deleted_at'))->dateTime('d.m.Y')->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()->label(__('admin.common.trashed')),
                SelectFilter::make('status')->label(__('admin.common.status'))->options([
                    'available' => __('admin.courier.status_available'),
                    'busy'      => __('admin.courier.status_busy'),
                    'offline'   => __('admin.courier.status_offline'),
                ]),
                SelectFilter::make('vehicle_type')->label(__('admin.courier.vehicle_type'))->options([
                    'bike'    => __('admin.courier.vehicle_bike'),
                    'scooter' => __('admin.courier.vehicle_scooter'),
                    'car'     => __('admin.courier.vehicle_car'),
                    'other'   => __('admin.courier.vehicle_other'),
                ]),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()
                        ->label(__('admin.common.edit'))
                        ->mutateRecordDataUsing(function (array $data, Courier $record): array {
                            $data['name']  = $record->user?->name ?? '';
                            $data['phone'] = $record->user?->phone ?? '';
                            return $data;
                        })
                        ->using(function (Courier $record, array $data): Courier {
                            $record->user?->update([
                                'name'  => $data['name'] ?? $record->user->name,
                                'phone' => $data['phone'] ?? $record->user->phone,
                            ]);
                            $record->update([
                                'vehicle_type' => $data['vehicle_type'],
                                'plate_number' => $data['plate_number'] ?? null,
                                'avatar'       => $data['avatar'] ?? $record->avatar,
                                'status'       => $data['status'] ?? $record->status,
                            ]);
                            return $record;
                        }),
                    Action::make('block')
                        ->label(__('admin.common.block'))
                        ->icon('heroicon-o-no-symbol')->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => $record->user?->status !== 'blocked' && !$record->trashed())
                        ->action(fn ($record) => $record->user?->update(['status' => 'blocked'])),
                    Action::make('activate')
                        ->label(__('admin.common.activate'))
                        ->icon('heroicon-o-check-circle')->color('success')
                        ->visible(fn ($record) => $record->user?->status === 'blocked' && !$record->trashed())
                        ->action(fn ($record) => $record->user?->update(['status' => 'active'])),
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
            'index' => Pages\ListCouriers::route('/'),
        ];
    }
}
