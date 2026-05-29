<?php

namespace App\Filament\Admin\Resources\Orders;

use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Actions as SchemaActions;
use Filament\Schemas\Components\Section as InfoSection;
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
            ->withoutGlobalScopes([SoftDeletingScope::class])
            ->with(['customer.user', 'items', 'restaurant', 'courier.user']);
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
                    ->color(fn ($state) => match ($state) {
                        'pending'    => 'warning',
                        'confirmed'  => 'info',
                        'rejected'   => 'danger',
                        'preparing'  => 'primary',
                        'ready'      => 'success',
                        'delivering' => 'indigo',
                        'delivered'  => 'success',
                        'cancelled'  => 'danger',
                        default      => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending'    => __('admin.order.status_pending'),
                        'confirmed'  => __('admin.order.status_confirmed'),
                        'rejected'   => __('admin.order.status_rejected'),
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
                    'rejected'   => __('admin.order.status_rejected'),
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
                // Icon only: tafsilotlar
                Action::make('details')
                    ->label('')
                    ->tooltip(__('admin.order.details'))
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading(fn (Order $record) => __('admin.order.details') . ' #' . $record->id)
                    ->modalSubmitAction(false)
                    ->infolist([
                        InfoSection::make(__('admin.order.customer_info'))->schema([
                            TextEntry::make('customer.user.name')->label(__('admin.order.customer')),
                            TextEntry::make('customer.user.phone')->label(__('admin.order.phone')),
                            TextEntry::make('delivery_address')
                                ->label(__('admin.order.address'))
                                ->columnSpanFull(),
                            SchemaActions::make([
                                Action::make('view_map')
                                    ->label(__('admin.order.view_on_map'))
                                    ->icon('heroicon-o-map-pin')
                                    ->color('info')
                                    ->url(fn (Order $record) => $record->delivery_lat && $record->delivery_lng
                                        ? "https://www.google.com/maps/search/?api=1&query={$record->delivery_lat},{$record->delivery_lng}"
                                        : null)
                                    ->openUrlInNewTab()
                                    ->hidden(fn (Order $record) => ! $record->delivery_lat || ! $record->delivery_lng),
                            ])->columnSpanFull(),
                            TextEntry::make('note')->label(__('admin.order.note'))->placeholder('—')->columnSpanFull(),
                            TextEntry::make('reject_reason')->label(__('admin.order.reject_reason'))
                                ->placeholder('—')->columnSpanFull()
                                ->hidden(fn (Order $record) => empty($record->reject_reason)),
                            TextEntry::make('cancel_reason')->label(__('admin.order.cancel_reason'))
                                ->columnSpanFull()
                                ->color('danger')
                                ->icon('heroicon-o-x-circle')
                                ->hidden(fn (Order $record) => empty($record->cancel_reason)),
                        ])->columns(2),
                        InfoSection::make(__('admin.order.items'))->schema([
                            RepeatableEntry::make('items')->schema([
                                TextEntry::make('name')->label(__('admin.order.item_name')),
                                TextEntry::make('quantity')->label(__('admin.order.quantity')),
                                TextEntry::make('price')->label(__('admin.order.price'))->money('UZS'),
                                TextEntry::make('unit')->label(__('admin.order.unit')),
                            ])->columns(4),
                        ]),
                    ]),

                // Icon only: chop etish
                Action::make('print')
                    ->label('')
                    ->tooltip(__('admin.order.print'))
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(fn (Order $record) => route('orders.print', $record))
                    ->openUrlInNewTab(),

                // To'g'ridan-to'g'ri: tasdiqlash (faqat pending da)
                Action::make('confirm')
                    ->label(__('admin.order.status_confirmed'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Order $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(fn (Order $record) => $record->update(['status' => 'confirmed'])),

                // To'g'ridan-to'g'ri: rad etish (pending va confirmed da)
                Action::make('reject')
                    ->label(__('admin.order.status_rejected'))
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Order $record) => in_array($record->status, ['pending', 'confirmed']))
                    ->form([
                        Textarea::make('reason')
                            ->label(__('admin.order.reject_reason'))
                            ->required()
                            ->rows(3),
                    ])
                    ->action(fn (Order $record, array $data) => $record->update([
                        'status'        => 'rejected',
                        'reject_reason' => $data['reason'],
                    ])),

                Action::make('prepare')
                    ->label(__('admin.order.status_preparing'))
                    ->icon('heroicon-o-fire')
                    ->color('primary')
                    ->visible(fn (Order $record) => $record->status === 'confirmed')
                    ->requiresConfirmation()
                    ->action(fn (Order $record) => $record->update(['status' => 'preparing'])),

                Action::make('ready')
                    ->label(__('admin.order.status_ready'))
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (Order $record) => $record->status === 'preparing')
                    ->requiresConfirmation()
                    ->action(fn (Order $record) => $record->update(['status' => 'ready'])),

                Action::make('deliver')
                    ->label(__('admin.order.status_delivering'))
                    ->icon('heroicon-o-truck')
                    ->color('indigo')
                    ->visible(fn (Order $record) => $record->status === 'ready')
                    ->requiresConfirmation()
                    ->action(fn (Order $record) => $record->update(['status' => 'delivering'])),

                // 3 nuqta ichida: faqat boshqaruv amallar
                ActionGroup::make([
                    RestoreAction::make()->label(__('admin.common.restore')),
                    ForceDeleteAction::make()->label(__('admin.common.force_delete')),
                ]),
            ])
            ->bulkActions([])
            ->paginated([25, 50, 100]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return ['index' => Pages\ListOrders::route('/')];
    }
}
