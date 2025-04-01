<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrainingDialogResource\Pages;
use App\Filament\Resources\TrainingDialogResource\RelationManagers;
use App\Models\TrainingDialog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TrainingDialogResource extends Resource
{
    protected static ?string $model = TrainingDialog::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('user_input')
                    ->label('User Input')
                    ->required(),
                Forms\Components\Textarea::make('ai_response')
                    ->label('AI Response')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_input')
                    ->label('User Input')
                    ->limit(50),
                Tables\Columns\TextColumn::make('ai_response')
                    ->label('AI Response')
                    ->limit(50),
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
            'index' => Pages\ListTrainingDialogs::route('/'),
            'create' => Pages\CreateTrainingDialog::route('/create'),
            'edit' => Pages\EditTrainingDialog::route('/{record}/edit'),
        ];
    }
}
