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
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('admin.nav.orders');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.group_orders');
    }

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
                TextColumn::make('customer.user.name')->label(__('admin.order.customer'))->searchable(),
                TextColumn::make('restaurant.name')->label(__('admin.order.restaurant'))->searchable(),
                TextColumn::make('courier.user.name')->label(__('admin.order.courier'))->default('—'),
                TextColumn::make('status')->label('Status')->badge()
                    ->color(fn ($state) => match($state) {
                        'pending'    => 'gray',    'confirmed'  => 'info',
                        'preparing'  => 'warning', 'ready'      => 'primary',
                        'delivering' => 'indigo',  'delivered'  => 'success',
                        'cancelled'  => 'danger',  default      => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pending'    => __('admin.order.status_pending'),
                        'confirmed'  => __('admin.order.status_confirmed'),
                        'preparing'  => __('admin.order.status_preparing'),
                        'ready'      => __('admin.order.status_ready'),
                        'delivering' => __('admin.order.status_delivering'),
                        'delivered'  => __('admin.order.status_delivered'),
                        'cancelled'  => __('admin.order.status_cancelled'),
                        default      => $state,
                    }),
                TextColumn::make('total')->label(__('admin.order.total'))->money('UZS')->sortable(),
                TextColumn::make('payment_method')->label(__('admin.order.payment'))->badge()
                    ->color(fn ($state) => $state === 'cash' ? 'gray' : 'success')
                    ->formatStateUsing(fn ($state) => $state === 'cash' ? __('admin.order.payment_cash') : __('admin.order.payment_card')),
                TextColumn::make('delivery_address')->label(__('admin.order.address'))->limit(25),
                TextColumn::make('created_at')->label(__('admin.order.date'))->dateTime('d.m.Y H:i')->sortable(),
                TextColumn::make('deleted_at')->label(__('admin.common.deleted_at'))->dateTime('d.m.Y')->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TrashedFilter::make()->label(__('admin.common.trashed')),
                SelectFilter::make('status')->label('Status')->options([
                    'pending'    => __('admin.order.status_pending'),
                    'confirmed'  => __('admin.order.status_confirmed'),
                    'preparing'  => __('admin.order.status_preparing'),
                    'ready'      => __('admin.order.status_ready'),
                    'delivering' => __('admin.order.status_delivering'),
                    'delivered'  => __('admin.order.status_delivered'),
                    'cancelled'  => __('admin.order.status_cancelled'),
                ]),
                SelectFilter::make('payment_method')->label(__('admin.order.payment'))
                    ->options(['cash' => __('admin.order.payment_cash'), 'card' => __('admin.order.payment_card')]),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make()->label(__('admin.common.edit')),
                    RestoreAction::make()->label(__('admin.common.restore')),
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
            ->paginated([25, 50, 100]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return ['index' => Pages\ListOrders::route('/')];
    }
}
