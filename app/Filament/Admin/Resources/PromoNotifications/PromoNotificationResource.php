<?php

namespace App\Filament\Admin\Resources\PromoNotifications;

use App\Models\PromoNotification;
use App\Services\FcmService;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PromoNotificationResource extends Resource
{
    protected static ?string $model = PromoNotification::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-bell';
    protected static ?int $navigationSort = 5;

    public static function getNavigationLabel(): string
    {
        return __('admin.nav.promo_notifications');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.group_management');
    }

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'moderator']);
    }

    public static function canEdit(Model $record): bool
    {
        return $record->sent_at === null;
    }

    public static function canDelete(Model $record): bool
    {
        return $record->sent_at === null;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('admin.promo_notification.section_content'))->components([
                Tabs::make('translations')->tabs([
                    Tab::make("O'zbekcha")->schema([
                        TextInput::make('title_uz')
                            ->label(__('admin.promo_notification.title'))
                            ->required()
                            ->maxLength(255),
                        Textarea::make('body_uz')
                            ->label(__('admin.promo_notification.body'))
                            ->required()
                            ->rows(4),
                    ]),
                    Tab::make('English')->schema([
                        TextInput::make('title_en')
                            ->label(__('admin.promo_notification.title'))
                            ->required()
                            ->maxLength(255),
                        Textarea::make('body_en')
                            ->label(__('admin.promo_notification.body'))
                            ->required()
                            ->rows(4),
                    ]),
                    Tab::make('Türkçe')->schema([
                        TextInput::make('title_tr')
                            ->label(__('admin.promo_notification.title'))
                            ->required()
                            ->maxLength(255),
                        Textarea::make('body_tr')
                            ->label(__('admin.promo_notification.body'))
                            ->required()
                            ->rows(4),
                    ]),
                ]),
            ]),
            Section::make(__('admin.promo_notification.section_image'))->components([
                FileUpload::make('image')
                    ->label(__('admin.promo_notification.image'))
                    ->image()
                    ->directory('promo-notifications')
                    ->maxSize(4096)
                    ->imagePreviewHeight('200')
                    ->nullable(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('')
                    ->imageSize(60)
                    ->extraImgAttributes(['style' => 'border-radius:4px;object-fit:cover'])
                    ->defaultImageUrl(null),
                TextColumn::make('title_uz')
                    ->label(__('admin.promo_notification.title'))
                    ->searchable()
                    ->limit(40),
                TextColumn::make('sent_at')
                    ->label(__('admin.promo_notification.sent_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('recipients_count')
                    ->label(__('admin.promo_notification.recipients_count'))
                    ->sortable()
                    ->default(0),
                TextColumn::make('created_at')
                    ->label(__('admin.common.created_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Action::make('send')
                    ->label(__('admin.promo_notification.send'))
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->visible(fn (PromoNotification $record) => $record->sent_at === null)
                    ->requiresConfirmation()
                    ->modalHeading(__('admin.promo_notification.send_confirm_heading'))
                    ->modalDescription(__('admin.promo_notification.send_confirm_description'))
                    ->modalSubmitActionLabel(__('admin.promo_notification.send_confirm_button'))
                    ->action(function (PromoNotification $record) {
                        /** @var FcmService $fcm */
                        $fcm   = app(FcmService::class);
                        $count = $fcm->sendPromoNotification($record);

                        $record->update([
                            'sent_at'          => now(),
                            'recipients_count' => $count,
                        ]);

                        Notification::make()
                            ->title(__('admin.promo_notification.sent_success', ['count' => $count]))
                            ->success()
                            ->send();
                    }),

                Action::make('edit')
                    ->label('')
                    ->tooltip(__('admin.common.edit'))
                    ->icon('heroicon-o-pencil')
                    ->color('gray')
                    ->visible(fn (PromoNotification $record) => $record->sent_at === null)
                    ->url(fn (PromoNotification $record) => static::getUrl('edit', ['record' => $record])),

                Action::make('delete')
                    ->label('')
                    ->tooltip(__('admin.common.delete'))
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(fn (PromoNotification $record) => $record->sent_at === null)
                    ->requiresConfirmation()
                    ->action(fn (PromoNotification $record) => $record->delete()),
            ])
            ->bulkActions([])
            ->paginated([25, 50, 100]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPromoNotifications::route('/'),
            'edit'  => Pages\EditPromoNotification::route('/{record}/edit'),
        ];
    }
}
