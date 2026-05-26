<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
					->label('Имя')
                    ->required(),
                TextInput::make('email')
					->label('Email')
                    ->email(),
                // TextInput::make('password')
				// 	->label('Пароль')
                //     ->password(),
				Select::make('role_id')
					->label('Роль')
					->relationship('role', 'name'),
                TextInput::make('platform')
					->label('Платформа'),
                TextInput::make('tg_id')
					->label('Telegram ID'),
                TextInput::make('avatar')
					->label('Аватар'),
            ]);
    }
}
