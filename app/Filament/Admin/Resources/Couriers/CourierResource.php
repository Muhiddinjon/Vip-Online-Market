<?php
namespace App\Filament\Admin\Resources\Couriers;

use App\Models\Courier;
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
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourierResource extends Resource
{
    protected static ?string $model = Courier::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationLabel = 'Kuryerlar';
    protected static string|\UnitEnum|null $navigationGroup = 'Boshqaruv';
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
        $isCreate = $schema->getLivewire() instanceof CreateRecord;

        return $schema->components([
            Section::make('Shaxsiy ma\'lumotlar')->components([
                Grid::make(2)->components([
                    TextInput::make('name')->label('Ism Familiya')->required(),
                    TextInput::make('phone')->label('Telefon')->tel()->required(),
                ]),
                TextInput::make('password')->label('Parol')->password()
                    ->required($isCreate)->minLength(6)
                    ->visible($isCreate),
            ]),

            Section::make('Kuryer ma\'lumotlari')->components([
                Grid::make(2)->components([
                    Select::make('vehicle_type')->label('Transport turi')->options([
                        'bike'    => 'Velosiped',
                        'scooter' => 'Scooter',
                        'car'     => 'Avtomobil',
                        'other'   => 'Boshqa',
                    ])->required()->default('bike'),
                    TextInput::make('plate_number')->label('Davlat raqami'),
                ]),
                Select::make('status')->label('Holat')->options([
                    'available' => 'Mavjud',
                    'busy'      => 'Band',
                    'offline'   => 'Offline',
                ])->default('offline'),
                FileUpload::make('avatar')->label('Rasm')->image()->directory('couriers'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')->label('')->circular(),
                TextColumn::make('user.name')->label('Ism')->searchable()->sortable(),
                TextColumn::make('user.phone')->label('Telefon')->searchable(),
                TextColumn::make('vehicle_type')->label('Transport')->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'bike'    => 'Velosiped',
                        'scooter' => 'Scooter',
                        'car'     => 'Avtomobil',
                        default   => 'Boshqa',
                    }),
                TextColumn::make('plate_number')->label('Raqami'),
                TextColumn::make('status')->label('Holat')->badge()
                    ->color(fn ($state) => match($state) {
                        'available' => 'success', 'busy' => 'warning', 'offline' => 'gray', default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'available' => 'Mavjud', 'busy' => 'Band', 'offline' => 'Offline', default => $state,
                    }),
                TextColumn::make('user.status')->label('Hisob')->badge()
                    ->color(fn ($state) => $state === 'active' ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state === 'active' ? 'Faol' : 'Bloklangan'),
                TextColumn::make('orders_count')->label('Buyurtmalar')->counts('orders')->sortable(),
                TextColumn::make('deleted_at')->label('O\'chirilgan')->dateTime('d.m.Y')->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()->label('O\'chirilganlar'),
                SelectFilter::make('status')->label('Holat')->options([
                    'available' => 'Mavjud', 'busy' => 'Band', 'offline' => 'Offline',
                ]),
                SelectFilter::make('vehicle_type')->label('Transport')->options([
                    'bike' => 'Velosiped', 'scooter' => 'Scooter', 'car' => 'Avtomobil', 'other' => 'Boshqa',
                ]),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()->label('Tahrirlash'),
                    Action::make('block')->label('Bloklash')->icon('heroicon-o-no-symbol')->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => $record->user?->status !== 'blocked' && !$record->trashed())
                        ->action(fn ($record) => $record->user?->update(['status' => 'blocked'])),
                    Action::make('activate')->label('Faollashtirish')->icon('heroicon-o-check-circle')->color('success')
                        ->visible(fn ($record) => $record->user?->status === 'blocked' && !$record->trashed())
                        ->action(fn ($record) => $record->user?->update(['status' => 'active'])),
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
            ]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCouriers::route('/'),
            'create' => Pages\CreateCourier::route('/create'),
            'edit'   => Pages\EditCourier::route('/{record}/edit'),
        ];
    }
}
