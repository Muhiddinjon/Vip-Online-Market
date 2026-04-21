<?php
namespace App\Filament\Admin\Resources\Restaurants;

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
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
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

class RestaurantResource extends Resource
{
    protected static ?string $model = Restaurant::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $navigationLabel = 'Restoranlar';
    protected static string|\UnitEnum|null $navigationGroup = 'Boshqaruv';
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
        $isCreate = $schema->getLivewire() instanceof CreateRecord;
        $lang = fn ($get) => $get('lang') ?: 'uz';

        return $schema->components([
            Section::make('Login ma\'lumotlari')->components([
                Grid::make(2)->components([
                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required($isCreate)
                        ->unique('users', 'email', ignoreRecord: true),
                    TextInput::make('password')
                        ->label('Parol')
                        ->password()
                        ->required($isCreate)
                        ->minLength(8)
                        ->placeholder($isCreate ? '' : 'O\'zgartirmaslik uchun bo\'sh qoldiring')
                        ->visible($isCreate),
                ]),
            ]),

            Section::make('Asosiy ma\'lumotlar')->components([
                Grid::make(2)->components([
                    TextInput::make('name')->label('Restoran nomi (asosiy)')->required(),
                    TextInput::make('phone')->label('Telefon')->tel(),
                ]),
                Select::make('status')->label('Status')->options([
                    'active'   => 'Faol',
                    'inactive' => 'Nofaol',
                    'blocked'  => 'Bloklangan',
                ])->required()->default('active'),
            ]),

            Section::make('Tavsif')->components([
                Textarea::make('description.uz')->label('Tavsif (UZ)')->rows(3)->visible(fn($get) => $lang($get) === 'uz'),
                Textarea::make('description.en')->label('Description (EN)')->rows(3)->visible(fn($get) => $lang($get) === 'en'),
                Textarea::make('description.tr')->label('Açıklama (TR)')->rows(3)->visible(fn($get) => $lang($get) === 'tr'),
            ]),

            Section::make('Joylashuv')->components([
                TextInput::make('address')->label('Manzil'),
                View::make('filament.components.maps-picker'),
                Grid::make(2)->components([
                    TextInput::make('lat')->label('Latitude (avtomatik)')->numeric()->readOnly(),
                    TextInput::make('lng')->label('Longitude (avtomatik)')->numeric()->readOnly(),
                ]),
            ]),

            Section::make('Rasmlar')->components([
                Grid::make(2)->components([
                    FileUpload::make('logo')
                        ->label('Logo')
                        ->image()
                        ->directory('restaurants/logos'),
                    FileUpload::make('cover_image')
                        ->label('Cover rasm')
                        ->image()
                        ->directory('restaurants/covers'),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')->label('')->circular(),
                TextColumn::make('name')->label('Restoran')->searchable()->sortable(),
                TextColumn::make('user.email')->label('Email')->searchable(),
                TextColumn::make('phone')->label('Telefon'),
                TextColumn::make('address')->label('Manzil')->limit(30)->default('—'),
                TextColumn::make('status')->label('Status')->badge()
                    ->color(fn ($state) => match($state) {
                        'active'   => 'success',
                        'inactive' => 'warning',
                        'blocked'  => 'danger',
                        default    => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'active'   => 'Faol',
                        'inactive' => 'Nofaol',
                        'blocked'  => 'Bloklangan',
                        default    => $state,
                    }),
                TextColumn::make('orders_count')
                    ->label('Buyurtmalar')
                    ->counts('orders')
                    ->sortable(),
                TextColumn::make('deleted_at')
                    ->label('O\'chirilgan')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()->label('O\'chirilganlar'),
                SelectFilter::make('status')->label('Status')->options([
                    'active'   => 'Faol',
                    'inactive' => 'Nofaol',
                    'blocked'  => 'Bloklangan',
                ]),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()->label('Tahrirlash'),
                    Action::make('block')
                        ->label('Bloklash')
                        ->icon('heroicon-o-no-symbol')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => $record->status !== 'blocked' && !$record->trashed())
                        ->action(fn ($record) => $record->update(['status' => 'blocked'])),
                    Action::make('activate')
                        ->label('Faollashtirish')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn ($record) => $record->status === 'blocked' && !$record->trashed())
                        ->action(fn ($record) => $record->update(['status' => 'active'])),
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
            'index'  => Pages\ListRestaurants::route('/'),
            'create' => Pages\CreateRestaurant::route('/create'),
            'edit'   => Pages\EditRestaurant::route('/{record}/edit'),
        ];
    }
}
