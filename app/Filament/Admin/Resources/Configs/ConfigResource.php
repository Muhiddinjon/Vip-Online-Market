<?php
namespace App\Filament\Admin\Resources\Configs;

use App\Models\Config;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ConfigResource extends Resource
{
    protected static ?string $model = Config::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 99;

    public static function getNavigationLabel(): string
    {
        return __('admin.config.nav_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.group_management');
    }

    public static function canCreate(): bool  { return false; }
    public static function canDelete(Model $record): bool  { return false; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title_uz')
                    ->label(__('admin.config.title'))
                    ->getStateUsing(function (Config $record) {
                        $lang = app()->getLocale();
                        return $record->title[$lang] ?? $record->title['uz'] ?? $record->title['en'] ?? '—';
                    })
                    ->searchable(query: fn ($query, $search) => $query->whereRaw(
                        'JSON_UNQUOTE(JSON_EXTRACT(title, "$.uz")) LIKE ?', ["%{$search}%"]
                    )),
                TextColumn::make('keyword')
                    ->label(__('admin.config.keyword'))
                    ->badge()->color('gray')
                    ->copyable(),
                TextColumn::make('value')
                    ->label(__('admin.config.value'))
                    ->limit(50)
                    ->placeholder('—')
                    ->tooltip(fn (Config $record) => $record->value),
            ])
            ->actions([
                EditAction::make()
                    ->label(__('admin.common.edit'))
                    ->slideOver()
                    ->form(fn (Config $record) => [
                        Section::make((fn () => $record->title[app()->getLocale()] ?? $record->title['uz'] ?? $record->title['en'] ?? $record->keyword)())
                            ->description(__('admin.config.keyword') . ': ' . $record->keyword)
                            ->components(match ($record->type) {
                                'textarea' => [
                                    Textarea::make('value')
                                        ->label(__('admin.config.value'))
                                        ->helperText(__('admin.config.textarea_hint'))
                                        ->rows(5),
                                ],
                                'switch' => [
                                    Toggle::make('value')
                                        ->label(__('admin.config.value'))
                                        ->formatStateUsing(fn ($state) => in_array($state, ['1', 'true', true]))
                                        ->dehydrateStateUsing(fn ($state) => $state ? '1' : '0'),
                                ],
                                default => [
                                    TextInput::make('value')
                                        ->label(__('admin.config.value'))
                                        ->helperText(__('admin.config.text_hint')),
                                ],
                            }),
                    ]),
            ])
            ->bulkActions([])
            ->paginated(false);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return ['index' => Pages\ListConfigs::route('/')];
    }
}
