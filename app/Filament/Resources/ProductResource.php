<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $modelLabel = 'Produk';
    protected static ?string $pluralModelLabel = 'Produk';

    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Produk')
                    ->description('Periksa kembali sebelum Anda membuat data produk.')
                    ->icon('heroicon-m-rectangle-stack')
                    ->schema([
                        TextInput::make('product_name')
                            ->label('Nama Produk')
                            ->required()
                            ->maxLength(150)
                            ->autofocus()
                            ->required()
                            ->validationMessages([
                                'required' => 'Nama kategori belum diisi.',
                            ]),
                        Select::make('category')
                            ->label('Kategori')
                            ->options(Category::select('*')->orderBy('name', 'asc')->pluck('name', 'name'))
                            ->native(false)
                            ->searchable()
                            ->required()
                            ->validationMessages([
                                'required' => 'Guru belum dipilih.',
                            ]),
                        TextInput::make('price')
                            ->label('Harga')
                            ->numeric()
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->prefix('Rp')
                            ->required()
                            ->validationMessages([
                                'required' => 'Harga belum diisi.',
                            ]),
                        TextInput::make('discount')
                            ->label('Diskon')
                            ->numeric()
                            ->rules(['between:0,100', 'regex:/^\d+(\.\d{1,2})?$/'])
                            ->suffix('%')
                            ->validationMessages([
                                'between' => 'Nilai diskon berada dalam rentang 0 hingga 100.',
                                'regex' => 'Angka desimal dengan maksimal dua angka di belakang koma.',
                            ]),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product_name')
                    ->label('Nama Produk')
                    ->copyable()
                    ->sortable()
                    ->searchable()
                    ->alignCenter()
                    ->verticallyAlignCenter(),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color('success')
                    ->sortable()
                    ->alignCenter()
                    ->verticallyAlignCenter(),
                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->alignCenter()
                    ->verticallyAlignCenter(),
                TextColumn::make('discount')
                    ->label('Diskon')
                    ->suffix(function ($record) {
                        if ($record->discount) {
                            return '%';
                        } else {
                            false;
                        }
                    })
                    ->default('Tidak ada diskon')
                    ->alignCenter()
                    ->verticallyAlignCenter(),
            ])
            ->poll('1s')
            ->defaultSort('product_name', 'asc')
            ->striped()
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->color('primary'),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make()
                        ->color('warning'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make()
                        ->color('warning'),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
