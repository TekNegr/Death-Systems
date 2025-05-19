<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EntrepriseResource\Pages;
use App\Filament\Resources\EntrepriseResource\RelationManagers;
use App\Models\Entreprise;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EntrepriseResource extends Resource
{
    protected static ?string $model = Entreprise::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Job Applications';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company_name')
                    ->label('Company Name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('recipient_name')
                    ->label('Recipient Name')
                    ->nullable()
                    ->maxLength(255),

                Forms\Components\Select::make('recipient_gender')
                    ->label('Recipient Gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other',
                    ])
                    ->nullable(),

                Forms\Components\TextInput::make('email_to_apply')
                    ->label('Email to Apply')
                    ->email()
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('work_domain')
                    ->label('Work Domain')
                    ->options([
                        'Developpement' => 'Developpement',
                        'Game Dev' => 'Game Dev',
                        'Data & IA' => 'Data & IA',
                        'Other' => 'Other',
                    ])
                    ->nullable(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')->label('Company Name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('recipient_name')->label('Recipient Name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('recipient_gender')->label('Recipient Gender')->sortable(),
                Tables\Columns\TextColumn::make('email_to_apply')->label('Email to Apply')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('work_domain')->label('Work Domain')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => EntrepriseResource\Pages\ListEntreprises::route('/'),
            'create' => EntrepriseResource\Pages\CreateEntreprise::route('/create'),
            'edit' => EntrepriseResource\Pages\EditEntreprise::route('/{record}/edit'),
        ];
    }
}
