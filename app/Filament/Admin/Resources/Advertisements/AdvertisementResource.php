<?php
namespace App\Filament\Admin\Resources\Advertisements;

use App\Models\Advertisement;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AdvertisementResource extends Resource
{
    protected static ?string $model = Advertisement::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('admin.nav.advertisements');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.group_management');
    }

    public static function canViewAny(): bool { return true; }
    public static function canDelete(Model $record): bool { return true; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('admin.advertisement.section_main'))->components([
                TextInput::make('title')
                    ->label(__('admin.advertisement.title'))
                    ->required(),
                TextInput::make('url')
                    ->label(__('admin.advertisement.url'))
                    ->url()
                    ->placeholder('https://'),
                Textarea::make('details')
                    ->label(__('admin.advertisement.details'))
                    ->rows(5),
                Grid::make(2)->components([
                    TextInput::make('queue')
                        ->label(__('admin.advertisement.queue'))
                        ->numeric()
                        ->default(0),
                    Select::make('status')
                        ->label(__('admin.common.status'))
                        ->options([
                            1  => __('admin.common.active'),
                            0  => __('admin.common.inactive'),
                            -1 => __('admin.common.deleted'),
                        ])
                        ->default(1)
                        ->required(),
                ]),
            ]),
            Section::make(__('admin.advertisement.section_image'))->components([
                FileUpload::make('image_path')
                    ->label(__('admin.advertisement.image'))
                    ->image()
                    ->directory('advertisements')
                    ->maxSize(4096)
                    ->imagePreviewHeight('200')
                    ->helperText(__('admin.advertisement.image_hint')),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('')
                    ->size(60)
                    ->extraImgAttributes(['style' => 'border-radius:4px;object-fit:cover;aspect-ratio:4/3']),
                TextColumn::make('title')
                    ->label(__('admin.advertisement.title'))
                    ->searchable()->sortable(),
                TextColumn::make('url')
                    ->label(__('admin.advertisement.url'))
                    ->limit(30)
                    ->default('—'),
                TextColumn::make('queue')
                    ->label(__('admin.advertisement.queue'))
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('admin.common.status'))
                    ->badge()
                    ->color(fn ($state) => match ((int) $state) {
                        1  => 'success',
                        0  => 'warning',
                        -1 => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ((int) $state) {
                        1  => __('admin.common.active'),
                        0  => __('admin.common.inactive'),
                        -1 => __('admin.common.deleted'),
                        default => $state,
                    }),
                TextColumn::make('created_at')
                    ->label(__('admin.common.created_at'))
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('admin.common.status'))
                    ->options([
                        1  => __('admin.common.active'),
                        0  => __('admin.common.inactive'),
                        -1 => __('admin.common.deleted'),
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
            ->defaultSort('queue');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdvertisements::route('/'),
        ];
    }
}
