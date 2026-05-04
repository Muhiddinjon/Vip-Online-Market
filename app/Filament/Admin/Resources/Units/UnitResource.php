<?php
namespace App\Filament\Admin\Resources\Units;

use App\Models\Unit;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-beaker';
    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('admin.nav.units');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.group_menu');
    }

    public static function canViewAny(): bool { return true; }
    public static function canDelete(Model $record): bool { return true; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('admin.unit.label'))->columns(3)->components([
                TextInput::make('name.uz')->label(__('admin.category.name_uz'))->required(),
                TextInput::make('name.en')->label(__('admin.category.name_en')),
                TextInput::make('name.tr')->label(__('admin.category.name_tr')),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->orderBy('id'))
            ->columns([
                TextColumn::make('name.uz')
                    ->label(__('admin.category.name_uz'))
                    ->searchable()
                    ->getStateUsing(fn (Unit $record): string =>
                        $record->name['uz'] ?? $record->name['en'] ?? $record->name['tr'] ?? '—'
                    ),
                TextColumn::make('name.en')->label(__('admin.category.name_en'))
                    ->getStateUsing(fn (Unit $record): string => $record->name['en'] ?? '—'),
                TextColumn::make('name.tr')->label(__('admin.category.name_tr'))
                    ->getStateUsing(fn (Unit $record): string => $record->name['tr'] ?? '—'),
                TextColumn::make('status')->label(__('admin.common.status'))
                    ->badge()
                    ->color(fn ($state) => $state == 1 ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state == 1 ? __('admin.common.active') : __('admin.common.inactive')),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('admin.common.status'))
                    ->options([
                        1  => __('admin.common.active'),
                        -1 => __('admin.common.inactive'),
                    ])
                    ->default(1),
            ])
            ->actions([
                EditAction::make()
                    ->label('')
                    ->tooltip(__('admin.common.edit')),
                Action::make('delete')
                    ->label('')
                    ->tooltip(__('admin.common.delete'))
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Unit $record) => $record->status == 1)
                    ->action(fn (Unit $record) => $record->update(['status' => -1])),
                Action::make('restore')
                    ->label('')
                    ->tooltip(__('admin.common.restore'))
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->visible(fn (Unit $record) => $record->status == -1)
                    ->action(fn (Unit $record) => $record->update(['status' => 1])),
            ])
            ->bulkActions([BulkActionGroup::make([])]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUnits::route('/'),
        ];
    }
}
