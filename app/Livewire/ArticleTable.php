<?php

namespace App\Livewire;

use App\Exports\ArticleExport;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Maatwebsite\Excel\Facades\Excel;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\DateColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ImageColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class ArticleTable extends DataTableComponent
{
    use LivewireAlert;

    //Oyente de una confirmacion
    protected $listeners = [
        'delete'
    ];

    ///Muestra los datos sin restrincción
    // protected $model = Article::class;

    public Article $article;

    public function bulkActions(): array
    {
        return [
            // 'deletedSelected' => 'Eliminar',
            'exportSelected' => 'Export',
        ];
    }

    public function deleteConfirmation(Article $article)
    {
        $this->article = $article;
        $this->alert('warning', '¿Estás seguro que deseas eliminar este articulo?', [
            'showConfirmButton' => true,
            'confirmButtonText' => 'Sí',
            'onConfirmed' => 'delete',
            'showCancelButton' => true,
            'cancelButtonText' => 'No',
            'toast' => false,
            'position' => 'center',
            'timer' => null,
        ]);
    }

    public function delete()
    {
        try {
            $this->article->delete();
            $this->alert('success', 'Articulo eliminado', [
                'toast' => false,
                'position' => 'center',
                'timerProgressBar' => true,
                'timer' => 1500,
            ]);
        } catch (\Throwable $th) {
            if ($th->errorInfo[1] == 1451) {
                $this->alert('error', 'No puedes eliminar este registro debido a que existen tablas asociados a este', [
                    'toast' => false,
                    'position' => 'center',
                    'timerProgressBar' => true,
                    'timer' => 4000,
                ]);
            }
        }
    }

    // public function deletedSelected()
    // {
    //     if ($this->getSelected()) {
    //         Article::whereIn('id', $this->getSelected())->delete();
    //         $this->clearSelected();
    //     } else {
    //         // $this->emit('alert', 'No hay Registro seleccionados');
    //         $this->alert('warning', 'No hay Registro seleccionados');
    //     }
    // }

    public function exportSelected()
    {
        if ($this->getSelected()) {
            $articles = $this->getSelected();
            return Excel::download(new ArticleExport($articles), 'articles.xlsx');
        } else {
            // $this->emit('alert', 'No hay Registro seleccionados');
            $this->alert('warning', 'No hay Registro seleccionados');
        }
        $this->clearSelected();
    }

    public function changeArticleSort($items)
    {
        foreach ($items as $item) {
            Article::find($item[$this->getPrimaryKey()])->update(['sort' => (int)$item[$this->getDefaultReorderColumn()]]);
        }
    }


    public function configure(): void
    {
        $this->setPrimaryKey('id');
        //Agregar componentes en el tab de table
        $this->setConfigurableAreas([
            'toolbar-right-end' => [
                'components.button', [
                    'slot' => 'Nuevo usuario',
                    'route' => route('dashboard'),
                ]
            ]
        ]);
        $this->setTableWrapperAttributes([
            'class' => 'rounded-4 shadow',
        ]);
        $this->setTrAttributes(function ($row, $index) {
            if ($index % 2 === 0) {
                return [
                    'class' => 'table-secondary',
                ];
            }

            return [];
        });
        // $this->setOfflineIndicatorEnabled();
        /// Habilitar la carga anticipada de relaciones de columnas.
        $this->setEagerLoadAllRelationsEnabled();
        $this->setEmptyMessage('No se encontraron elementos. Intente ampliar su búsqueda');
        /// Habilitar o deasabilitar la clacificacion unica por cada componente
        $this->setSingleSortingDisabled();
        /// Filas en las que se puede hacer click
        $this->setTableRowUrl(function ($row) {
            return route('dashboard', $row);
        });
        ///Ordenar
        $this->setDefaultSort('sort', 'desc');
        ///Métodos disponible de paginación
        $this->setPaginationEnabled();
        // $this->setPerPage(10);
        ///Reordenar
        $this->setReorderStatus(true);
        $this->setDefaultReorderSort('sort', 'desc');
        $this->setReorderMethod('changeArticleSort');
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")
                ///Tener un mejor control con una columna cuando se filtra
                ->sortable(fn ($query, $direction) => $query->orderBy('id', $direction))
                ->collapseOnTablet(),
            // Column::make("Sort", "sort"),
            Column::make("Title", "title")
                /// En esta consulta siempre iniciar con un orWhere
                ->searchable(fn ($query, $searchTeam) => $query->orWhere('title', 'like', '%' . $searchTeam . '%'))
                ->sortable(),
            Column::make("User id", "user.name")
                ///Muestra el nombre y los atributos de un componente
                ->format(
                    fn ($value, $row, Column $column) => '<div class="d-flex justify-content-start justify-content-sm-center align-items-center" style="height: 100%;">
                    <span class="badge text-bg-primary text-wrap">' . $value . '</span>
                </div>'
                )
                ->searchable()
                ->sortable()
                ->collapseOnTablet()
                ->html(),
            Column::make("Email", "user.email")
                ->searchable()
                ->sortable()
                ->collapseOnTablet(),
            ///Usamos el componente q nos brinda o podemos personalizar
            BooleanColumn::make("Is published", "is_published")
                // ->setSuccessValue(false) ///Para invertir - pero no es necesario
                // ->yesNo()
                ->setView('components.check_published') ///component personalizado
                ->collapseOnTablet()
                ->sortable(),
            ///Columna q muestra img
            ImageColumn::make('Avatar')
                ->location(
                    // fn ($row) => storage_path('app/public/avatars/' . $row->id . '.jpg')
                    fn ($row) => asset('avatars/testimonial-1.jpg')
                )
                ->collapseOnTablet(),
            ///V1 de column
            DateColumn::make("Created at", "created_at")
                ->outputFormat('d/m/Y')
                ->sortable()
                ->collapseOnTablet(),
            ///V2 de column formateada
            Column::make("Updated at", "updated_at")
                ->format(fn ($value) => $value->format('d/m/Y'))
                ->sortable()
                ->collapseOnTablet(),
            ///Columna con enlaces
            // LinkColumn::make('Link Columns')
            //     ->title(fn ($row) => 'Edit')
            //     ->location(fn ($row) => route('dashboard', $row->id))
            //     ->attributes(function ($row) {
            //         return [
            //             'class' => 'btn btn-blue',
            //         ];
            //     })
            //     ->collapseOnTablet(),
            ///Columna agrupando varios botones
            // ButtonGroupColumn::make('Button Group Column')
            //     ->attributes(function ($row) {
            //         return [
            //             'class' => 'space-x-2',
            //         ];
            //     })
            //     ->buttons([
            //         LinkColumn::make('View') // make() has no effect in this case but needs to be set anyway
            //             ->title(fn ($row) => 'View ' . $row->name)
            //             ->location(fn ($row) => route('dashboard', $row->id))
            //             ->attributes(function ($row) {
            //                 return [
            //                     'class' => 'btn btn-blue',
            //                 ];
            //             }),
            //         LinkColumn::make('Edit')
            //             ->title(fn ($row) => 'Edit ' . $row->name)
            //             ->location(fn ($row) => route('dashboard', $row->id))
            //             ->attributes(function ($row) {
            //                 return [
            //                     'target' => '_blank',
            //                     'class' => 'btn btn-green',
            //                 ];
            //             }),
            //     ])
            //     ->collapseOnTablet(),
            // Columna Labels - Representar vista en una celda
            Column::make('Columna Labels')
                // Note: The view() method is reserved for columns that have a field
                ->label(
                    fn ($row, Column $column) => view('components.actions-butons', [
                        'route' => 'dashboard',
                        'param' => 'user',
                        'id' => $row->id,
                    ])
                )
                /// Filas en las que se puede hacer click
                ->unclickable(),

        ];
    }

    ///Personaliza las consultas
    public function builder(): Builder
    {
        return Article::query()
            ->with('user');
    }
}
