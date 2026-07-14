<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<User> $query
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('role_badge', function (User $user) {
                $label      = $user->role_label; // dari getRoleLabelAttribute()
                $badgeClass = match ($user->role) {
                    'admin'                       => 'bg-danger',
                    'koordinatorakreditasifikes'  => 'bg-primary',
                    'koordinatorprodi'            => 'bg-success',
                    'timpenyusun'                 => 'bg-warning text-dark',
                    'gpmfikes'                    => 'bg-info text-dark',
                    'timlpmrektorat'              => 'bg-secondary',
                    'dekan'                       => 'bg-dark',
                    default                       => 'bg-secondary',
                };
                return '<span class="badge rounded-pill ' . $badgeClass . '">' . $label . '</span>';
            })
            ->addColumn('is_active_badge', function (User $user) {
                return $user->is_active
                    ? '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Aktif</span>'
                    : '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Nonaktif</span>';
            })
            ->addColumn('action', function (User $user) {
                $currentRole = auth()->user()->role ?? '';

                // Hanya admin & koordinator akreditasi yang bisa kelola user
                if (!in_array($currentRole, ['admin', 'koordinatorakreditasifikes'])) {
                    return '<span class="text-muted">—</span>';
                }

                $btn  = '<div class="d-flex justify-content-center align-items-center gap-1">';

                // Update Password
                $btn .= '<a href="' . route('user.updatePasswordForm', $user->id) . '"
                             class="btn btn-sm btn-info text-white rounded shadow-sm"
                             style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;"
                             title="Update Password">
                             <i class="bi bi-key-fill" style="font-size:11px;"></i>
                         </a>';

                // Edit Data
                $btn .= '<a href="' . route('user.edit', $user->id) . '"
                             class="btn btn-sm btn-warning text-white rounded shadow-sm"
                             style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;"
                             title="Edit Data">
                             <i class="bi bi-pencil-fill" style="font-size:11px;"></i>
                         </a>';

                // Hapus (hanya admin yang bisa hapus)
                if ($currentRole === 'admin') {
                    $btn .= '<form action="' . route('user.destroy', $user->id) . '" method="POST" class="m-0">'
                          . csrf_field()
                          . method_field('DELETE')
                          . '<button type="submit"
                                 class="btn btn-sm btn-danger rounded shadow-sm"
                                 style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;"
                                 title="Hapus"
                                 onclick="return confirm(\'Yakin ingin menghapus pengguna ini?\')">
                                 <i class="bi bi-trash-fill" style="font-size:11px;"></i>
                             </button>'
                          . '</form>';
                }

                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['action', 'role_badge', 'is_active_badge'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<User>
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()
            ->select(['id', 'name', 'email', 'role', 'is_active', 'created_at'])
            ->orderBy('name');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('user-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
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
            Column::make('DT_RowIndex')
                ->title('No.')
                ->width('5%')
                ->addClass('text-center')
                ->searchable(false)
                ->orderable(false),
            Column::make('name')
                ->title('Nama Pengguna'),
            Column::make('email')
                ->title('Email'),
            Column::computed('role_badge')
                ->title('Hak Akses')
                ->searchable(false)
                ->orderable(false),
            Column::computed('is_active_badge')
                ->title('Status')
                ->searchable(false)
                ->orderable(false),
            Column::computed('action')
                ->title('Aksi')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center')
                ->orderable(false),
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
