<?php
namespace App\Filament\Admin\Resources\Orders;

use App\Models\Order;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Buyurtmalar';
    protected static string|\UnitEnum|null $navigationGroup = 'Buyurtmalar';
    protected static ?int $navigationSort = 1;

    public static function canCreate(): bool { return false; }

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'moderator']);
    }

    public static function canDelete(Model $record): bool { return true; }
    public static function canForceDelete(Model $record): bool { return true; }
    public static function canRestore(Model $record): bool { return true; }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function form(Schema $schema): Schema { return $schema->components([]); }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('#')->sortable(),
                TextColumn::make('customer.user.name')->label('Mijoz')->searchable(),
                TextColumn::make('restaurant.name')->label('Restoran')->searchable(),
                TextColumn::make('courier.user.name')->label('Kuryer')->default('—'),
                TextColumn::make('status')->label('Status')->badge()
                    ->color(fn ($state) => match($state) {
                        'pending'    => 'gray',    'confirmed'  => 'info',
                        'preparing'  => 'warning', 'ready'      => 'primary',
                        'delivering' => 'indigo',  'delivered'  => 'success',
                        'cancelled'  => 'danger',  default      => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pending'    => 'Kutilmoqda',    'confirmed'  => 'Qabul qilindi',
                        'preparing'  => 'Tayyorlanmoqda','ready'      => 'Tayyor',
                        'delivering' => 'Yetkazilmoqda', 'delivered'  => 'Yetkazildi',
                        'cancelled'  => 'Bekor qilindi', default      => $state,
                    }),
                TextColumn::make('total')->label('Summa')->money('UZS')->sortable(),
                TextColumn::make('payment_method')->label('To\'lov')->badge()
                    ->color(fn ($state) => $state === 'cash' ? 'gray' : 'success')
                    ->formatStateUsing(fn ($state) => $state === 'cash' ? 'Naqd' : 'Karta'),
                TextColumn::make('delivery_address')->label('Manzil')->limit(25),
                TextColumn::make('created_at')->label('Sana')->dateTime('d.m.Y H:i')->sortable(),
                TextColumn::make('deleted_at')->label('O\'chirilgan')->dateTime('d.m.Y')->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TrashedFilter::make()->label('O\'chirilganlar'),
                SelectFilter::make('status')->label('Status')->options([
                    'pending'    => 'Kutilmoqda',    'confirmed'  => 'Qabul qilindi',
                    'preparing'  => 'Tayyorlanmoqda','ready'      => 'Tayyor',
                    'delivering' => 'Yetkazilmoqda', 'delivered'  => 'Yetkazildi',
                    'cancelled'  => 'Bekor qilindi',
                ]),
                SelectFilter::make('payment_method')->label('To\'lov')
                    ->options(['cash' => 'Naqd', 'card' => 'Karta']),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make()->label('Ko\'rish'),
                    RestoreAction::make()->label('Tiklash'),
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
            ->paginated([25, 50, 100]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return ['index' => Pages\ListOrders::route('/')];
    }
}
