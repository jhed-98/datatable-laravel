<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ImageColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class ArticleTable extends DataTableComponent
{
    ///Muestra los datos sin restrincción
    protected $model = Article::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setOfflineIndicatorEnabled();
        $this->setEmptyMessage('No se encontraron elementos. Intente ampliar su búsqueda');
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")
                ->sortable(),
            Column::make("Title", "title")
                ->sortable(),
            Column::make("User id", "user.name")
                ->sortable(),
            ///Usamos el componente q nos brinda o podemos personalizar
            BooleanColumn::make("Is published", "is_published")
                // ->setSuccessValue(false) ///Para invertir - pero no es necesario
                // ->yesNo()
                ->setView('components.check_published') ///component personalizado
                ->sortable(),
            ///Columna q muestra img
            ImageColumn::make('Avatar')
                ->location(
                    // fn ($row) => storage_path('app/public/avatars/' . $row->id . '.jpg')
                    fn ($row) => asset('avatars/testimonial-1.jpg')
                ),
            Column::make("Created at", "created_at")
                ->sortable(),
            ///Columna con enlaces
            LinkColumn::make('Link Columns')
                ->title(fn ($row) => 'Edit')
                ->location(fn ($row) => route('dashboard', $row->id))
                ->attributes(function ($row) {
                    return [
                        'class' => 'btn btn-blue',
                    ];
                }),
            ///Columna agrupando varios botones
            ButtonGroupColumn::make('Button Group Column')
                ->attributes(function ($row) {
                    return [
                        'class' => 'space-x-2',
                    ];
                })
                ->buttons([
                    LinkColumn::make('View') // make() has no effect in this case but needs to be set anyway
                        ->title(fn ($row) => 'View ' . $row->name)
                        ->location(fn ($row) => route('dashboard', $row->id))
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-blue',
                            ];
                        }),
                    LinkColumn::make('Edit')
                        ->title(fn ($row) => 'Edit ' . $row->name)
                        ->location(fn ($row) => route('dashboard', $row->id))
                        ->attributes(function ($row) {
                            return [
                                'target' => '_blank',
                                'class' => 'btn btn-green',
                            ];
                        }),
                ]),
        ];
    }

    ///Personaliza las consultas
    public function builder(): Builder
    {
        return Article::query()
            ->with('user');
    }
}
