<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AuthDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<User> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (User $user) {
                $editBtn = '<a href="javascript:void(0)" class="btn btn-sm btn-warning me-1" 
                                data-bs-toggle="modal" data-bs-target="#editModal" 
                                data-id="' . $user->id . '"
                                data-name="' . $user->name . '"
                                data-email="' . $user->email . '"
                                data-role="' . $user->role . '"
                                data-is_active="' . $user->is_active . '">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>';
                $deleteBtn = '<form action="' . route('users.destroy', $user->id) . '" method="POST" class="d-inline" 
                                onsubmit="return confirm(\'Yakin ingin menghapus pengguna ini?\')">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>';
                return $editBtn . $deleteBtn;
            })
            ->addColumn('status', function (User $user) {
                return $user->is_active
                    ? '<span class="badge bg-success">Aktif</span>'
                    : '<span class="badge bg-danger">Nonaktif</span>';
            })
            ->addColumn('role_badge', function (User $user) {
                $colors = [
                    'admin'      => 'primary',
                    'operator'   => 'info',
                    'asesor'     => 'warning',
                    'prodi'      => 'secondary',
                ];
                $color = $colors[$user->role] ?? 'dark';
                return '<span class="badge bg-' . $color . '">' . ucfirst($user->role) . '</span>';
            })
            ->rawColumns(['action', 'status', 'role_badge'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<User>
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()->select(['id', 'name', 'email', 'role', 'is_active', 'created_at']);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('users-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(0)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload'),
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('No.')->width(50),
            Column::make('name')->title('Nama Lengkap'),
            Column::make('email')->title('Email'),
            Column::computed('role_badge')->title('Role')->exportable(false)->printable(false),
            Column::computed('status')->title('Status')->exportable(false)->printable(false),
            Column::make('created_at')->title('Terdaftar'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(150)
                  ->addClass('text-center')
                  ->title('Aksi'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'DataPengguna_' . date('YmdHis');
    }
}
