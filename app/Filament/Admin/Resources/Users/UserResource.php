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
    protected static ?string $navigationLabel = 'Foydalanuvchilar';
    protected static string|\UnitEnum|null $navigationGroup = 'Boshqaruv';
    protected static ?int $navigationSort = 3;

    // Faqat admin
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
                    TextInput::make('name')->label('Ism')->required(),
                    TextInput::make('email')->label('Email')->email()->required()->unique(ignoreRecord: true),
                ]),
                TextInput::make('phone')->label('Telefon')->tel(),
                Grid::make(2)->components([
                    Select::make('role')->label('Rol')->options([
                        'admin'      => 'Admin',
                        'restaurant' => 'Restoran egasi',
                        'courier'    => 'Kuryer',
                        'customer'   => 'Mijoz',
                    ])->required()->default('admin'),
                    Select::make('status')->label('Status')->options([
                        'active'  => 'Faol',
                        'blocked' => 'Bloklangan',
                    ])->required()->default('active'),
                ]),
                TextInput::make('password')->label('Parol')->password()
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                    ->minLength(8)
                    ->placeholder('O\'zgartirmaslik uchun bo\'sh qoldiring'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Ism')->searchable()->sortable(),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('phone')->label('Telefon')->searchable()->default('—'),
                TextColumn::make('role')->label('Rol')->badge()
                    ->color(fn ($state) => match($state) {
                        'admin'      => 'danger',
                        'restaurant' => 'info',
                        'courier'    => 'primary',
                        'customer'   => 'gray',
                        default      => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'admin'      => 'Admin',
                        'restaurant' => 'Restoran egasi',
                        'courier'    => 'Kuryer',
                        'customer'   => 'Mijoz',
                        default      => $state,
                    }),
                TextColumn::make('status')->label('Status')->badge()
                    ->color(fn ($state) => $state === 'active' ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state === 'active' ? 'Faol' : 'Bloklangan'),
                TextColumn::make('created_at')->label('Qo\'shilgan')->date('d.m.Y')->sortable(),
                TextColumn::make('deleted_at')->label('O\'chirilgan')->dateTime('d.m.Y')->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()->label('O\'chirilganlar'),
                SelectFilter::make('role')->label('Rol')->options([
                    'admin'      => 'Admin',
                    'restaurant' => 'Restoran egasi',
                    'courier'    => 'Kuryer',
                    'customer'   => 'Mijoz',
                ]),
                SelectFilter::make('status')->label('Status')->options([
                    'active' => 'Faol', 'blocked' => 'Bloklangan',
                ]),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()->label('Tahrirlash'),
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
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
