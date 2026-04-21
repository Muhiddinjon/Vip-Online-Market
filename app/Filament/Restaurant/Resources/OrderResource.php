<?php
namespace App\Filament\Restaurant\Resources;

use App\Filament\Restaurant\Resources\OrderResource\Pages\ListOrders;
use App\Models\Order;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Buyurtmalar';
    protected static ?string $modelLabel = 'Buyurtma';
    protected static ?string $pluralModelLabel = 'Buyurtmalar';
    protected static ?int $navigationSort = 3;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('restaurant_id', auth()->user()?->restaurant?->id)
            ->with(['customer.user']);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('customer.user.name')
                    ->label('Mijoz')
                    ->searchable(),
                TextColumn::make('total')
                    ->label('Summa')
                    ->money('UZS')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Holat')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending'   => 'warning',
                        'confirmed' => 'info',
                        'preparing' => 'primary',
                        'ready'     => 'success',
                        'delivering'=> 'info',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending'    => 'Kutilmoqda',
                        'confirmed'  => 'Qabul qilindi',
                        'preparing'  => 'Tayyorlanmoqda',
                        'ready'      => 'Tayyor',
                        'delivering' => 'Yetkazilmoqda',
                        'delivered'  => 'Yetkazildi',
                        'cancelled'  => 'Bekor qilindi',
                        default      => $state,
                    }),
                TextColumn::make('created_at')
                    ->label('Vaqt')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Action::make('confirm')
                    ->label('Qabul qilish')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->visible(fn (Order $record) => $record->status === 'pending')
                    ->action(fn (Order $record) => $record->update(['status' => 'confirmed'])),
                Action::make('prepare')
                    ->label('Tayyorlanmoqda')
                    ->color('primary')
                    ->icon('heroicon-o-fire')
                    ->visible(fn (Order $record) => $record->status === 'confirmed')
                    ->action(fn (Order $record) => $record->update(['status' => 'preparing'])),
                Action::make('ready')
                    ->label('Tayyor')
                    ->color('info')
                    ->icon('heroicon-o-check-badge')
                    ->visible(fn (Order $record) => $record->status === 'preparing')
                    ->action(fn (Order $record) => $record->update(['status' => 'ready'])),
                Action::make('cancel')
                    ->label('Bekor qilish')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->visible(fn (Order $record) => in_array($record->status, ['pending', 'confirmed']))
                    ->requiresConfirmation()
                    ->action(fn (Order $record) => $record->update(['status' => 'cancelled'])),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Holat')
                    ->options([
                        'pending'    => 'Kutilmoqda',
                        'confirmed'  => 'Qabul qilindi',
                        'preparing'  => 'Tayyorlanmoqda',
                        'ready'      => 'Tayyor',
                        'delivering' => 'Yetkazilmoqda',
                        'delivered'  => 'Yetkazildi',
                        'cancelled'  => 'Bekor qilindi',
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
        ];
    }
}
