<?php
namespace App\Filament\Admin\Resources\Users;

use App\Models\User;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = null;
    protected static string|\UnitEnum|null $navigationGroup = null;
    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('admin.nav.users');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.group_management');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function canForceDelete(Model $record): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function canRestore(Model $record): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->components([
                Grid::make(2)->components([
                    TextInput::make('name')->label(__('admin.user.name'))->required(),
                    TextInput::make('email')->label('Email')->email()->required()->unique(ignoreRecord: true),
                ]),
                TextInput::make('phone')->label(__('admin.user.phone'))->tel(),
                Grid::make(2)->components([
                    Select::make('role')->label(__('admin.user.role'))->options([
                        'admin'      => __('admin.user.role_admin'),
                        'restaurant' => __('admin.user.role_restaurant'),
                        'courier'    => __('admin.user.role_courier'),
                        'customer'   => __('admin.user.role_customer'),
                    ])->required()->default('admin'),
                    Select::make('status')->label(__('admin.common.status'))->options([
                        'active'  => __('admin.common.active'),
                        'blocked' => __('admin.common.blocked'),
                    ])->required()->default('active'),
                ]),
                TextInput::make('password')->label(__('admin.user.password'))->password()
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                    ->minLength(8)
                    ->placeholder(__('admin.user.password_hint')),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('admin.user.name'))->searchable()->sortable(),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('phone')->label(__('admin.user.phone'))->searchable()->default('—'),
                TextColumn::make('role')->label(__('admin.user.role'))->badge()
                    ->color(fn ($state) => match($state) {
                        'admin'      => 'danger',
                        'restaurant' => 'info',
                        'courier'    => 'primary',
                        'customer'   => 'gray',
                        default      => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'admin'      => __('admin.user.role_admin'),
                        'restaurant' => __('admin.user.role_restaurant'),
                        'courier'    => __('admin.user.role_courier'),
                        'customer'   => __('admin.user.role_customer'),
                        default      => $state,
                    }),
                TextColumn::make('status')->label(__('admin.common.status'))->badge()
                    ->color(fn ($state) => $state === 'active' ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state === 'active' ? __('admin.common.active') : __('admin.common.blocked')),
                TextColumn::make('created_at')->label(__('admin.common.created_at'))->date('d.m.Y')->sortable(),
                TextColumn::make('deleted_at')->label(__('admin.common.deleted_at'))->dateTime('d.m.Y')->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()->label(__('admin.common.trashed')),
                SelectFilter::make('role')->label(__('admin.user.role'))->options([
                    'admin'      => __('admin.user.role_admin'),
                    'restaurant' => __('admin.user.role_restaurant'),
                    'courier'    => __('admin.user.role_courier'),
                    'customer'   => __('admin.user.role_customer'),
                ]),
                SelectFilter::make('status')->label(__('admin.common.status'))->options([
                    'active' => __('admin.common.active'), 'blocked' => __('admin.common.blocked'),
                ]),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()->label(__('admin.common.edit')),
                    RestoreAction::make()->label(__('admin.common.restore')),
                    DeleteAction::make()->label(__('admin.common.delete')),
                    ForceDeleteAction::make()->label(__('admin.common.force_delete')),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label(__('admin.common.delete')),
                    RestoreBulkAction::make()->label(__('admin.common.restore')),
                    ForceDeleteBulkAction::make()->label(__('admin.common.force_delete')),
                ]),
            ]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
        ];
    }
}
